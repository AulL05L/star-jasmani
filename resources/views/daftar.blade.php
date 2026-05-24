@extends('layouts.app')

@section('title', 'Pendaftaran | Star Jasmani')

@push('styles')
<style>
    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .faq-item.active .faq-answer {
        max-height: 400px;
    }
    .faq-item.active .faq-icon-plus { display: none; }
    .faq-item.active .faq-icon-x { display: flex; }
    .faq-icon-x { display: none; }
    .faq-item.active .faq-btn { background: #1a1a1a; border-color: #374151; }
</style>
@endpush

@section('content')

{{-- Header Nav --}}
<div class="sticky top-0 z-50 bg-black/90 backdrop-blur-md border-b border-gray-900 p-4 lg:p-5">
    <a href="{{ route('home') }}" class="flex items-center text-gray-400 hover:text-red-800 transition-colors duration-300">
        <i class="fa-solid fa-arrow-left mr-2"></i>
        <span class="font-bold uppercase tracking-widest text-sm">Kembali</span>
    </a>
</div>

<section class="min-h-screen bg-black py-20 px-6 relative overflow-hidden">


    <div class="relative z-10 max-w-2xl mx-auto">

        {{-- Header --}}
        <div class="text-center mb-16">
            <p class="text-red-800 font-bold uppercase tracking-[0.4em] text-xs mb-4">Informasi Pendaftaran</p>
            <h1 class="text-5xl md:text-6xl font-black text-white tracking-tighter leading-none mb-2">
                Frequently
            </h1>
            <h1 class="text-5xl md:text-6xl font-black text-white tracking-tighter leading-none">
                Asked <span class="text-red-800">Questions</span>
            </h1>
            <div class="w-16 h-1 bg-red-800 mx-auto mt-8"></div>
        </div>

        {{-- FAQ Items --}}
        <div class="space-y-3 mb-16">

            @php
            $faqs = [
                [
                    'q' => 'Apakah pemula yang belum pernah olahraga bisa ikut?',
                    'a' => 'Sangat bisa. Kami menerapkan sistem Sport Science di mana setiap peserta akan dianalisis postur (APECS) dan BMI-nya terlebih dahulu. Program latihan akan disesuaikan dengan kemampuan awal Anda agar progres maksimal dan minim risiko cedera.'
                ],
                [
                    'q' => 'Dimana lokasi pusat pelatihan Star Jasmani?',
                    'a' => 'Saat ini pelatihan tatap muka berpusat di wilayah Jakarta dan sekitarnya. Kami menggunakan fasilitas olahraga publik yang standar untuk simulasi tes seperti stadion atau kolam renang standar nasional.'
                ],
                [
                    'q' => 'Bagaimana cara mendaftar dan pembayarannya?',
                    'a' => 'Anda cukup mengisi data melalui tombol "GABUNG SEKARANG" di bawah. Setelah formulir terkirim, admin kami akan menghubungi Anda melalui WhatsApp untuk konsultasi jadwal, detail biaya, dan teknis pembayaran.'
                ],
                [
                    'q' => 'Berapa lama durasi program pelatihan?',
                    'a' => 'Durasi program disesuaikan dengan target seleksi Anda. Umumnya program berjalan 1-3 bulan dengan jadwal latihan 3-5 kali per minggu. Coach akan merancang periodisasi latihan yang optimal berdasarkan waktu seleksi yang tersedia.'
                ],
                [
                    'q' => 'Apakah program ini cocok untuk persiapan selain Polri?',
                    'a' => 'Tentu saja. Meskipun fokus utama kami adalah persiapan tes fisik Polri, program pelatihan kami sangat komprehensif dan dapat disesuaikan untuk persiapan seleksi militer, kepolisian daerah, atau tes fisik lainnya yang serupa.'
                ],
            ];
            @endphp

            @foreach($faqs as $i => $faq)
            <div class="faq-item rounded-2xl border border-gray-800 bg-gray-950/80 backdrop-blur-sm overflow-hidden transition-all duration-300">
                <button class="faq-btn w-full p-5 text-left flex justify-between items-center gap-4 transition-all duration-300 rounded-2xl"
                    onclick="toggleFAQ(this)">
                    <span class="font-bold text-sm md:text-base text-white tracking-wide">{{ $faq['q'] }}</span>
                    <div class="flex-shrink-0">
                        {{-- Plus icon --}}
                        <span class="faq-icon-plus w-8 h-8 rounded-full border border-gray-700 flex items-center justify-center text-gray-400 transition-all">
                            <i class="fa-solid fa-plus text-xs"></i>
                        </span>
                        {{-- X icon --}}
                        <span class="faq-icon-x w-8 h-8 rounded-full bg-red-800 border border-red-800 items-center justify-center text-white transition-all">
                            <i class="fa-solid fa-xmark text-xs"></i>
                        </span>
                    </div>
                </button>
                <div class="faq-answer">
                    <p class="px-5 pb-5 text-gray-400 text-sm leading-relaxed">{{ $faq['a'] }}</p>
                </div>
            </div>
            @endforeach

        </div>

        {{-- CTA Card --}}
        <div class="bg-gray-950 border border-red-800/40 rounded-3xl p-8 md:p-12 text-center relative overflow-hidden">
            {{-- Glow --}}
            <div class="absolute -top-20 -right-20 w-40 h-40 bg-red-800/20 blur-[60px] pointer-events-none"></div>
            <div class="absolute -bottom-20 -left-20 w-40 h-40 bg-red-800/10 blur-[60px] pointer-events-none"></div>

            <div class="relative z-10">
                <div class="w-14 h-14 bg-red-800/20 rounded-2xl flex items-center justify-center mx-auto mb-6">
                    <i class="fa-solid fa-bolt text-red-500 text-xl"></i>
                </div>
                <h5 class="text-2xl font-black text-white mb-3 uppercase tracking-tight">Siap Memulai Transformasi?</h5>
                <p class="text-gray-400 text-sm mb-8 leading-relaxed max-w-md mx-auto">
                    Daftarkan diri Anda hari ini dan mulai persiapan bersama pelatih profesional bersertifikat nasional.
                </p>
                <a href="https://forms.gle/Aq8jhFq6isjvC3h69" target="_blank"
                    class="inline-block bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black py-4 px-12 rounded-full transition-all duration-300 shadow-lg shadow-red-900/40 tracking-[0.2em] uppercase text-sm">
                    GABUNG SEKARANG!
                </a>
                <p class="mt-6 text-[10px] text-gray-600 uppercase tracking-widest">Official Registration · Star Jasmani</p>
            </div>
        </div>

    </div>
</section>

@endsection

@push('scripts')
<script>
    function toggleFAQ(button) {
        const item = button.closest('.faq-item');
        const isActive = item.classList.contains('active');

        // Tutup semua
        document.querySelectorAll('.faq-item').forEach(el => el.classList.remove('active'));

        // Buka yang diklik kalau belum aktif
        if (!isActive) item.classList.add('active');
    }
</script>
@endpush