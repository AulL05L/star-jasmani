@extends('layouts.app')
@section('title', 'Pembayaran PDF Laporan — Star Jasmani')

@section('content')

{{-- HEADER --}}
<header class="sticky top-0 z-50 bg-black/90 backdrop-blur-md border-b border-gray-900 px-6 py-3.5 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 overflow-hidden rounded-lg border border-red-800 shrink-0">
            <img src="{{ asset('pict/logo-removebg.png') }}" alt="logo" class="w-full h-full object-cover" />
        </div>
        <div class="hidden sm:flex flex-col leading-none">
            <span class="text-white font-black tracking-tighter text-sm">STAR <span class="text-red-800">JASMANI</span></span>
            <span class="text-gray-600 text-[10px] uppercase tracking-widest">Pembayaran PDF Laporan</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('kalkulator.polri.hasil', $result->token) }}"
            class="flex items-center gap-2 bg-gray-800/60 hover:bg-gray-700 border border-gray-700 hover:border-gray-600 text-gray-300 hover:text-white text-xs font-bold uppercase tracking-wider px-3 py-2 rounded-lg transition-all">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali ke Hasil
        </a>
        <a href="{{ route('home') }}"
            class="hidden sm:flex items-center gap-2 text-gray-600 hover:text-white text-xs transition-colors">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
    </div>
</header>

@php
    $grade       = $result->grade ?? 'E';
    $gradeColors = match($grade) {
        'A'     => ['text' => 'text-green-400',  'border' => 'border-green-800'],
        'B'     => ['text' => 'text-blue-400',   'border' => 'border-blue-800'],
        'C'     => ['text' => 'text-yellow-400', 'border' => 'border-yellow-800'],
        'D'     => ['text' => 'text-orange-400', 'border' => 'border-orange-800'],
        default => ['text' => 'text-red-400',    'border' => 'border-red-800'],
    };
    $status    = $order->payment_status;   // pending | paid | expired | failed
    $isPending = $status === 'pending';
    $isPaid    = $status === 'paid';
    $isExpired = $status === 'expired';
    $isFailed  = $status === 'failed';
    $hasPaymentUrl = ! empty($order->payment_url);
    $hasQrisUrl    = ! empty($order->qris_url);
@endphp

{{-- SCORE MINI-SUMMARY --}}
<div class="bg-gray-950 border-b border-gray-800 px-6 py-4">
    <div class="max-w-2xl mx-auto flex items-center justify-between flex-wrap gap-3">
        <div class="flex items-center gap-4">
            <div class="text-center">
                <p class="text-gray-600 text-[10px] uppercase tracking-widest">Nilai Akhir</p>
                <p class="{{ $gradeColors['text'] }} text-3xl font-black leading-none mt-0.5">
                    {{ number_format($result->score_final ?? 0, 1) }}
                </p>
            </div>
            <div class="w-px h-10 bg-gray-800"></div>
            <div class="text-center">
                <p class="text-gray-600 text-[10px] uppercase tracking-widest">Grade</p>
                <p class="{{ $gradeColors['text'] }} text-3xl font-black leading-none mt-0.5">{{ $grade }}</p>
            </div>
            <div class="w-px h-10 bg-gray-800"></div>
            <div>
                <p class="text-gray-600 text-[10px] uppercase tracking-widest">Status</p>
                @if($result->is_lulus)
                    <span class="text-green-400 text-xs font-bold uppercase">Lulus</span>
                @else
                    <span class="text-red-400 text-xs font-bold uppercase">Belum Lulus</span>
                @endif
            </div>
        </div>
        <span class="bg-black/40 border {{ $gradeColors['border'] }} text-gray-500 text-[10px] uppercase tracking-widest px-3 py-1.5 rounded-full">
            <i class="fa-solid fa-shield-halved text-red-800 mr-1"></i>
            POLRI · {{ $result->gender === 'pria' ? 'Pria' : 'Wanita' }}
        </span>
    </div>
</div>

{{-- MAIN --}}
<div class="max-w-2xl mx-auto px-4 py-8 md:px-6 pb-16 space-y-5">

    {{-- Flash --}}
    @if(session('success'))
        <div class="bg-green-950/60 border border-green-800 text-green-300 text-sm rounded-xl px-5 py-4 flex items-start gap-3">
            <i class="fa-solid fa-circle-check text-green-400 mt-0.5 shrink-0"></i>
            <span>{{ session('success') }}</span>
        </div>
    @endif
    @if(session('error'))
        <div class="bg-red-950/60 border border-red-800 text-red-300 text-sm rounded-xl px-5 py-4 flex items-start gap-3">
            <i class="fa-solid fa-circle-exclamation text-red-400 mt-0.5 shrink-0"></i>
            <span>{{ session('error') }}</span>
        </div>
    @endif

    {{-- ORDER CARD --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between flex-wrap gap-2">
            <h1 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fa-solid fa-file-pdf text-red-400"></i> PDF Laporan POLRI Samapta
            </h1>

            {{-- Payment status badge --}}
            @if($isPending)
                <span class="bg-yellow-900/30 border border-yellow-700 text-yellow-400 text-[10px] uppercase tracking-widest font-bold px-3 py-1 rounded-full">
                    Menunggu Pembayaran
                </span>
            @elseif($isPaid)
                <span class="bg-green-900/30 border border-green-700 text-green-400 text-[10px] uppercase tracking-widest font-bold px-3 py-1 rounded-full">
                    Lunas
                </span>
            @elseif($isExpired)
                <span class="bg-gray-800 border border-gray-700 text-gray-400 text-[10px] uppercase tracking-widest font-bold px-3 py-1 rounded-full">
                    Kedaluwarsa
                </span>
            @elseif($isFailed)
                <span class="bg-red-900/30 border border-red-700 text-red-400 text-[10px] uppercase tracking-widest font-bold px-3 py-1 rounded-full">
                    Gagal
                </span>
            @endif
        </div>

        <div class="px-6 py-5 divide-y divide-gray-900">
            <div class="flex items-center justify-between py-3 text-sm">
                <span class="text-gray-500">Nomor Order</span>
                <span class="text-white font-mono font-bold tracking-wider">{{ $order->order_number }}</span>
            </div>
            <div class="flex items-center justify-between py-3 text-sm">
                <span class="text-gray-500">Item</span>
                <span class="text-gray-300">PDF Laporan Nilai POLRI Samapta</span>
            </div>
            <div class="flex items-center justify-between py-3">
                <span class="text-gray-500 text-sm">Total</span>
                <span class="text-white font-black text-xl">
                    Rp {{ number_format($order->amount, 0, ',', '.') }}
                </span>
            </div>
            @if($order->expired_at)
                <div class="flex items-center justify-between py-3 text-sm">
                    <span class="text-gray-500">Batas Waktu</span>
                    <span class="text-orange-400 font-mono text-xs" id="countdown-display">
                        {{ $order->expired_at->format('d M Y, H:i') }} WIB
                    </span>
                </div>
            @endif
        </div>
    </div>

    {{-- ── PAID STATE ── --}}
    @if($isPaid)
        <div class="bg-green-950/50 border border-green-800 rounded-2xl px-6 py-8 text-center">
            <i class="fa-solid fa-circle-check text-green-400 text-5xl mb-4"></i>
            <p class="text-green-300 font-bold text-base">Pembayaran Dikonfirmasi</p>
            <p class="text-gray-500 text-sm mt-2">PDF laporan kamu sudah siap diunduh.</p>
            @if($order->paid_at)
                <p class="text-gray-700 text-xs mt-3 font-mono">{{ $order->paid_at->format('d M Y, H:i') }} WIB</p>
            @endif
            <a href="{{ route('kalkulator.polri.pdf', $result->token) }}"
               class="inline-flex items-center gap-2 mt-6 bg-red-800 hover:bg-red-700 active:scale-[0.98] text-white font-black uppercase tracking-widest text-sm px-6 py-3.5 rounded-xl transition-all shadow-lg shadow-red-900/20">
                <i class="fa-solid fa-file-pdf"></i> Download PDF Laporan
            </a>
        </div>
    @endif

    {{-- ── EXPIRED STATE ── --}}
    @if($isExpired)
        <div class="bg-gray-900 border border-gray-700 rounded-2xl px-6 py-8 text-center">
            <i class="fa-solid fa-clock text-gray-500 text-5xl mb-4"></i>
            <p class="text-gray-300 font-bold text-base">Sesi Pembayaran Kedaluwarsa</p>
            <p class="text-gray-500 text-sm mt-2 max-w-xs mx-auto">
                Waktu pembayaran telah habis. Kamu bisa membuat order baru.
            </p>
            <a href="{{ route('kalkulator.polri.bayar', $result->token) }}"
               class="inline-flex items-center gap-2 mt-6 bg-gray-800 hover:bg-gray-700 border border-gray-600 text-white font-bold uppercase tracking-wider text-sm px-5 py-3 rounded-xl transition-all">
                <i class="fa-solid fa-rotate-right text-xs"></i> Coba Lagi
            </a>
        </div>
    @endif

    {{-- ── FAILED STATE ── --}}
    @if($isFailed)
        <div class="bg-red-950/40 border border-red-800 rounded-2xl px-6 py-8 text-center">
            <i class="fa-solid fa-circle-xmark text-red-400 text-5xl mb-4"></i>
            <p class="text-red-300 font-bold text-base">Pembayaran Gagal</p>
            <p class="text-gray-500 text-sm mt-2 max-w-xs mx-auto">
                Terjadi kesalahan saat memproses pembayaran. Silakan coba lagi.
            </p>
            <a href="{{ route('kalkulator.polri.bayar', $result->token) }}"
               class="inline-flex items-center gap-2 mt-6 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-wider text-sm px-5 py-3 rounded-xl transition-all">
                <i class="fa-solid fa-rotate-right text-xs"></i> Coba Lagi
            </a>
        </div>
    @endif

    {{-- ── PENDING STATE ── --}}
    @if($isPending)

        {{-- Gateway payment button (Midtrans Snap / redirect URL) --}}
        @if($hasPaymentUrl)
            <div class="bg-gray-950 border border-gray-800 rounded-2xl px-6 py-6 text-center space-y-4">
                <p class="text-gray-400 text-sm">Klik tombol di bawah untuk menyelesaikan pembayaran melalui gateway.</p>
                <a href="{{ $order->payment_url }}"
                   target="_blank"
                   class="inline-flex items-center gap-2 bg-red-800 hover:bg-red-700 active:scale-[0.98] text-white font-black uppercase tracking-widest text-sm px-8 py-4 rounded-xl transition-all shadow-lg shadow-red-900/20">
                    <i class="fa-solid fa-credit-card"></i> Bayar Sekarang — Rp {{ number_format($order->amount, 0, ',', '.') }}
                </a>
                <p class="text-gray-600 text-xs">Mendukung QRIS · GoPay · OVO · Dana · ShopeePay · Transfer Bank</p>
            </div>
        @endif

        {{-- Dynamic QRIS image from gateway --}}
        @if($hasQrisUrl)
            <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800">
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                        <i class="fa-solid fa-qrcode text-red-800"></i> Scan QRIS untuk Membayar
                    </h2>
                </div>
                <div class="px-6 py-8 flex flex-col items-center gap-4">
                    <img src="{{ $order->qris_url }}"
                         alt="QRIS Pembayaran"
                         class="w-64 h-64 object-contain rounded-xl bg-white p-2" />
                    <div class="text-center space-y-1">
                        <p class="text-white font-bold text-lg">Rp {{ number_format($order->amount, 0, ',', '.') }}</p>
                        <p class="text-gray-500 text-xs">Scan dengan aplikasi e-wallet atau mobile banking</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Placeholder: gateway not integrated yet --}}
        @if(! $hasPaymentUrl && ! $hasQrisUrl)
            <div class="bg-gray-950 border border-dashed border-gray-700 rounded-2xl px-6 py-10 text-center space-y-3">
                <i class="fa-solid fa-plug-circle-xmark text-gray-700 text-4xl"></i>
                <p class="text-gray-500 font-semibold text-sm">Payment Gateway Belum Terhubung</p>
                <p class="text-gray-600 text-xs max-w-xs mx-auto leading-relaxed">
                    Integrasi pembayaran otomatis sedang dalam pengembangan.
                    Halaman ini akan menampilkan QRIS atau tombol bayar setelah gateway aktif.
                </p>
                <div class="pt-2">
                    <span class="bg-gray-900 border border-gray-700 text-gray-600 text-[10px] uppercase tracking-widest px-3 py-1.5 rounded-full font-bold">
                        Order #{{ $order->order_number }} · Rp {{ number_format($order->amount, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        @endif

        {{-- Polling notice (ready for JS hook once gateway is wired) --}}
        <div class="bg-gray-900 border border-gray-800 rounded-xl px-5 py-4 flex items-start gap-3">
            <i class="fa-solid fa-circle-info text-gray-600 mt-0.5 shrink-0 text-sm"></i>
            <p class="text-gray-500 text-xs leading-relaxed">
                PDF akan tersedia secara otomatis setelah pembayaran dikonfirmasi oleh sistem.
                Kamu tidak perlu mengirim bukti transfer secara manual.
            </p>
        </div>

    @endif

    <p class="text-center text-gray-700 text-xs pb-2">
        PDF laporan berlaku selama masa aktif hasil kalkulator ·
        Pertanyaan? Hubungi admin Star Jasmani
    </p>

</div>

@endsection
