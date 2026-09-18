<?php

namespace App\Http\Controllers;

use App\Models\PdfOrder;
use App\Services\MidtransPayment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Penerima notifikasi pembayaran dari Midtrans.
 *
 * Endpoint ini dipanggil server Midtrans, bukan peramban pengguna, sehingga:
 *   - dikecualikan dari CSRF (lihat bootstrap/app.php);
 *   - tidak boleh mengandalkan sesi apa pun;
 *   - keasliannya dibuktikan lewat signature_key, bukan lewat login.
 *
 * Kode balasan penting: Midtrans mengulang pengiriman bila menerima selain 200.
 * Jadi 200 dipakai untuk "sudah kami proses" MAUPUN "order ini tidak kami
 * kenali" — mengulang notifikasi untuk order asing tidak akan pernah berhasil.
 */
class MidtransWebhookController extends Controller
{
    public function __invoke(Request $request, MidtransPayment $midtrans): JsonResponse
    {
        $payload = $request->all();

        if (! $midtrans->isConfigured()) {
            Log::error('Midtrans webhook: MIDTRANS_SERVER_KEY belum diisi, notifikasi diabaikan');

            return response()->json(['message' => 'gateway not configured'], 503);
        }

        if (! $midtrans->signatureIsValid($payload)) {
            Log::warning('Midtrans webhook: signature tidak valid, notifikasi ditolak', [
                'order_id' => $payload['order_id'] ?? null,
                'ip'       => $request->ip(),
            ]);

            return response()->json(['message' => 'invalid signature'], 403);
        }

        $order = PdfOrder::where('order_number', $payload['order_id'] ?? '')->first();

        if (! $order) {
            Log::warning('Midtrans webhook: order tidak ditemukan', [
                'order_id' => $payload['order_id'] ?? null,
            ]);

            return response()->json(['message' => 'order not found'], 200);
        }

        $midtrans->applyPayload($order, $payload);

        Log::info('Midtrans webhook diterima', [
            'order_number' => $order->order_number,
            'status_baru'  => $order->fresh()->payment_status,
        ]);

        return response()->json(['message' => 'ok'], 200);
    }
}
