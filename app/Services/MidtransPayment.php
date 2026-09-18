<?php

namespace App\Services;

use App\Models\PdfOrder;
use Illuminate\Support\Facades\Log;

/**
 * Satu-satunya tempat status pembayaran Midtrans diterjemahkan menjadi status
 * PdfOrder.
 *
 * Dipakai dua jalur yang berbeda dan keduanya memang diperlukan:
 *   - webhook dari Midtrans (jalur utama, otomatis);
 *   - pengecekan ulang saat pengguna kembali dari Snap (jalur cadangan).
 *
 * Jalur cadangan bukan kemewahan: webhook tidak bisa mencapai localhost saat
 * pengembangan, dan di produksi pun bisa telat beberapa detik. Tanpa itu,
 * pengguna yang sudah membayar akan melihat halaman "menunggu pembayaran".
 */
class MidtransPayment
{
    /**
     * Pasang kredensial ke SDK Midtrans. Harus dipanggil sebelum memakai
     * \Midtrans\Snap atau \Midtrans\Transaction.
     */
    public function configure(): void
    {
        \Midtrans\Config::$serverKey    = (string) config('midtrans.server_key');
        \Midtrans\Config::$isProduction = (bool) config('midtrans.is_production', false);
        \Midtrans\Config::$isSanitized  = true;
        \Midtrans\Config::$is3ds        = true;
    }

    public function isConfigured(): bool
    {
        return ! empty(config('midtrans.server_key'));
    }

    /**
     * Cocokkan signature_key webhook dengan hitungan kita sendiri.
     *
     * Rumus Midtrans: sha512(order_id + status_code + gross_amount + server_key).
     * gross_amount WAJIB diambil apa adanya dari payload (Midtrans mengirim
     * "5000.00", bukan "5000") — memakai nilai dari database akan selalu gagal.
     */
    public function signatureIsValid(array $payload): bool
    {
        $serverKey = (string) config('midtrans.server_key');

        if ($serverKey === '' || empty($payload['signature_key'])) {
            return false;
        }

        $expected = hash('sha512',
            ($payload['order_id']     ?? '') .
            ($payload['status_code']  ?? '') .
            ($payload['gross_amount'] ?? '') .
            $serverKey
        );

        return hash_equals($expected, (string) $payload['signature_key']);
    }

    /**
     * Terjemahkan transaction_status Midtrans ke status internal kita.
     *
     * `capture` hanya dianggap lunas bila fraud_status-nya `accept`; status
     * `challenge` berarti Midtrans meminta peninjauan manual, dan memperlakukan
     * itu sebagai lunas berarti memberikan PDF untuk pembayaran yang bisa saja
     * dibatalkan kemudian.
     */
    public function mapStatus(array $payload): ?string
    {
        $status = $payload['transaction_status'] ?? null;
        $fraud  = $payload['fraud_status']       ?? null;

        return match ($status) {
            'capture'    => $fraud === 'accept' ? 'paid' : 'pending',
            'settlement' => 'paid',
            'pending'    => 'pending',
            'deny', 'cancel', 'failure' => 'failed',
            'expire'     => 'expired',
            default      => null,
        };
    }

    /**
     * Terapkan payload ke order. Aman dipanggil berkali-kali untuk payload yang
     * sama — order yang sudah `paid` tidak pernah diturunkan statusnya, karena
     * Midtrans dapat mengirim notifikasi susulan setelah pelunasan.
     */
    public function applyPayload(PdfOrder $order, array $payload): void
    {
        $newStatus = $this->mapStatus($payload);

        if ($newStatus === null) {
            Log::warning('Midtrans: transaction_status tidak dikenali', [
                'order_number' => $order->order_number,
                'status'       => $payload['transaction_status'] ?? null,
            ]);

            return;
        }

        // Nominal ikut ditandatangani dalam signature, jadi ini bukan penjagaan
        // terhadap pemalsuan — melainkan terhadap salah konfigurasi harga, yang
        // jauh lebih mungkin terjadi dan diam-diam merugikan.
        $grossAmount = (float) ($payload['gross_amount'] ?? 0);

        if ($newStatus === 'paid' && (int) round($grossAmount) !== (int) $order->amount) {
            Log::error('Midtrans: nominal dibayar tidak sama dengan nominal order', [
                'order_number'  => $order->order_number,
                'amount_order'  => $order->amount,
                'amount_dibayar' => $grossAmount,
            ]);

            return;
        }

        $update = [
            'raw_callback_payload' => json_encode($payload, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE),
            'payment_reference'    => $payload['transaction_id'] ?? $order->payment_reference,
        ];

        if ($order->payment_status === 'paid') {
            // Sudah lunas: catat payload terbaru untuk audit, tapi jangan
            // sentuh statusnya.
            $order->update($update);

            return;
        }

        $update['payment_status'] = $newStatus;

        if ($newStatus === 'paid') {
            $update['paid_at'] = isset($payload['settlement_time'])
                ? $payload['settlement_time']
                : now();
        }

        $order->update($update);
    }

    /**
     * Tanya langsung ke Midtrans status sebuah order. Mengembalikan null bila
     * gagal — pemanggil harus tetap menampilkan halaman, bukan error.
     */
    public function fetchStatus(PdfOrder $order): ?array
    {
        if (! $this->isConfigured()) {
            return null;
        }

        try {
            $this->configure();

            $status = \Midtrans\Transaction::status($order->order_number);

            return json_decode(json_encode($status), true);
        } catch (\Throwable $e) {
            // 404 dari Midtrans itu wajar: transaksi belum pernah disentuh
            // pengguna, jadi belum ada catatannya di sana.
            Log::info('Midtrans: gagal mengambil status transaksi', [
                'order_number' => $order->order_number,
                'error'        => $e->getMessage(),
            ]);

            return null;
        }
    }
}
