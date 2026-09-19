@extends('layouts.app')

@section('title', 'Latihan Fisik Masuk Polisi & TNI — Persiapan Tes Samapta di Jakarta')
@section('meta_description', 'Latihan fisik persiapan masuk polisi dan TNI di Jakarta. Program disusun mundur dari tanggal seleksi untuk menaikkan nilai tes fisik Samapta A dan B, lengkap dengan asesmen awal dan analisis postur, bersama pelatih bersertifikasi ICCA.')

@php
$faqs = [
    ['Apa saja tes fisik untuk masuk polisi dan TNI?',
     'Rangkaian tes fisiknya disebut kesamaptaan jasmani atau Samapta: lari 12 menit, pull up untuk pria dan chin up untuk wanita, sit up, push up, shuttle run, serta renang. Keenamnya punya tabel nilainya masing-masing.'],
    ['Berapa lama latihan fisik untuk persiapan masuk polisi?',
     'Tergantung kondisi fisik awal dan jarak waktu menuju seleksi. Karena itu program disusun sebagai periodisasi individual: asesmen awal dilakukan lebih dulu, lalu beban latihan dirancang mundur dari tanggal seleksi peserta.'],
    ['Apa saja yang diukur pada asesmen awal?',
     'Pengukuran BMI dan komposisi tubuh, analisis postur serta keseimbangan otot menggunakan APECS, dan asesmen kemampuan fisik awal pada tiap komponen tes.'],
    ['Komponen apa saja yang dilatih?',
     'Strength and conditioning, endurance dan kapasitas aerobik untuk lari, speed and agility untuk shuttle run, serta teknik dan stamina renang sesuai standar tes kedinasan.'],
    ['Apakah saya bisa mengukur nilai tes fisik saya sendiri?',
     'Bisa. Star Jasmani menyediakan kalkulator nilai tes fisik polisi gratis yang menghitung nilai Jasmani A, Jasmani B, dan nilai akhir dari hasil tes mandiri Anda.'],
    ['Siapa yang melatih?',
     'Fariz Fahrun, S.Or., lulusan Ilmu Keolahragaan Universitas Negeri Jakarta dengan lisensi Pelatih Fisik Level 2 Nasional dari ICCA.'],
];
@endphp

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'Service',
            'name'        => 'Pelatihan Fisik Persiapan Kedinasan TNI & POLRI',
            'serviceType' => 'Pelatihan fisik persiapan seleksi kedinasan',
            'url'         => route('program.kedinasan'),
            'description' => 'Program latihan fisik terstruktur untuk calon peserta seleksi TNI, POLRI, dan instansi kedinasan, difokuskan pada standar tes Samapta A dan B.',
            'areaServed'  => ['@type' => 'AdministrativeArea', 'name' => 'DKI Jakarta, Indonesia'],
            'provider'    => ['@type' => 'Organization', 'name' => 'Star Jasmani', 'url' => url('/')],
            'audience'    => ['@type' => 'Audience', 'audienceType' => 'Calon peserta seleksi TNI, POLRI, dan instansi kedinasan'],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Persiapan Kedinasan', 'item' => route('program.kedinasan')],
            ],
        ],
        [
            '@type'      => 'FAQPage',
            'mainEntity' => collect($faqs)->map(fn ($qa) => [
                '@type'          => 'Question',
                'name'           => $qa[0],
                'acceptedAnswer' => ['@type' => 'Answer', 'text' => $qa[1]],
            ])->all(),
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@section('content')
@include('layouts.partials.public-header')

{{-- HERO --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-24">
    <div class="container mx-auto px-6 max-w-4xl">
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center gap-2 text-xs text-gray-600 uppercase tracking-widest">
                <li><a href="{{ route('home') }}" class="hover:text-red-500 transition-colors">Beranda</a></li>
                <li><i class="fa-solid fa-chevron-right text-[8px]"></i></li>
                <li class="text-gray-400">Persiapan Kedinasan</li>
            </ol>
        </nav>

        <span class="inline-block text-red-500 text-[11px] font-bold uppercase tracking-[0.25em] mb-4">Program Unggulan</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tighter leading-tight mb-6">
            Pelatihan Fisik Persiapan Kedinasan <span class="text-red-800">TNI &amp; POLRI</span>
        </h1>
        <p class="text-gray-400 text-base md:text-lg leading-relaxed mb-8 max-w-3xl">
            Seleksi kedinasan tidak dimenangkan oleh latihan yang paling berat, melainkan oleh latihan yang paling
            terarah. Untuk Anda yang sedang bersiap masuk polisi atau TNI, Star Jasmani menyusun program fisik
            mundur dari tanggal seleksi — berbasis data asesmen, bukan asumsi — agar setiap komponen tes fisik
            Samapta A dan B naik pada waktu yang tepat.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ route('daftar') }}"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-solid fa-user-plus"></i> Daftar Program
            </a>
            <a href="{{ route('kalkulator.polri') }}"
                class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-solid fa-calculator"></i> Cek Nilai Samapta Gratis
            </a>
        </div>
    </div>
</section>

{{-- UNTUK SIAPA --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Untuk Siapa Program Ini</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>
        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['fa-shield-halved', 'Calon Peserta POLRI', 'Mempersiapkan tes kesamaptaan jasmani A dan B beserta komponen renang sesuai standar penilaian POLRI.'],
                ['fa-star', 'Calon Prajurit TNI', 'Membangun kapasitas aerobik, kekuatan, dan kelincahan yang dituntut rangkaian tes jasmani TNI.'],
                ['fa-building-columns', 'Instansi Kedinasan Lain', 'Peserta seleksi instansi kedinasan yang mensyaratkan tes kebugaran fisik terukur.'],
            ] as $kartu)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6 hover:border-red-900 transition-colors">
                    <i class="fa-solid {{ $kartu[0] }} text-red-700 text-xl mb-4"></i>
                    <h3 class="text-white font-bold text-lg mb-2">{{ $kartu[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $kartu[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- KOMPONEN TES --}}
<section class="bg-zinc-950 border-y border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Komponen Tes Fisik yang Dilatih</h2>
        <div class="w-16 h-1 bg-red-800 mb-6"></div>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-8">
            Enam komponen berikut adalah isi tes kesamaptaan jasmani POLRI. Setiap komponen punya tabel konversi
            nilainya sendiri, dan tabel untuk peserta pria berbeda dengan peserta wanita.
        </p>

        <div class="grid sm:grid-cols-2 gap-4 mb-8">
            @foreach([
                ['Lari 12 Menit (Cooper Test)', 'Samapta A. Jarak tempuh menentukan seluruh nilai Jasmani A.'],
                ['Pull Up / Chin Up', 'Samapta B. Kekuatan tarik tubuh bagian atas.'],
                ['Sit Up 60 Detik', 'Samapta B. Daya tahan otot perut.'],
                ['Push Up 60 Detik', 'Samapta B. Daya tahan otot dorong.'],
                ['Shuttle Run 6 × 10 Meter', 'Samapta B. Kelincahan dan perubahan arah.'],
                ['Renang 50 Meter', 'Komponen terpisah dengan bobot 20% pada nilai akhir.'],
            ] as $i => $komponen)
                <div class="flex gap-4 bg-black border border-zinc-900 rounded-xl p-5">
                    <span class="shrink-0 w-7 h-7 rounded-full bg-red-900/40 border border-red-800 text-red-400 text-xs font-bold flex items-center justify-center">{{ $i + 1 }}</span>
                    <div>
                        <h3 class="text-white font-bold text-sm mb-1">{{ $komponen[0] }}</h3>
                        <p class="text-gray-500 text-xs leading-relaxed">{{ $komponen[1] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <div class="bg-black border border-red-900/50 rounded-2xl p-6 md:p-7">
            <h3 class="text-white font-bold text-lg mb-2">Sudah tahu nilai Anda sekarang?</h3>
            <p class="text-gray-400 text-sm leading-relaxed mb-5">
                Sebelum mulai berlatih, ukur dulu titik tolaknya. Masukkan hasil tes mandiri Anda ke kalkulator
                dan lihat nilai Jasmani A, Jasmani B, serta nilai akhir Anda — gratis, tanpa perlu membuat akun.
            </p>
            <a href="{{ route('kalkulator.polri') }}"
                class="inline-flex items-center gap-2 text-red-400 hover:text-white font-bold text-sm uppercase tracking-widest transition-colors">
                Buka Kalkulator Nilai Samapta <i class="fa-solid fa-arrow-right text-xs"></i>
            </a>
        </div>
    </div>
</section>

{{-- METODE --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Cara Kami Menyusun Programnya</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="space-y-5">
            @foreach([
                ['01', 'Analisis Latihan', 'Pemetaan kondisi fisik menyeluruh sebelum satu pun beban diberikan: pengukuran BMI dan komposisi tubuh, analisis postur serta ketidakseimbangan otot dengan APECS, dan asesmen kemampuan fisik awal pada tiap komponen tes.'],
                ['02', 'Proses Latihan', 'Periodisasi individual, bukan program generik. Strength and conditioning, endurance untuk lari, speed and agility untuk shuttle run, serta teknik renang — disusun progresif sesuai kondisi, target, dan tanggal seleksi Anda.'],
                ['03', 'Hasil Terukur', 'Setiap sesi tercatat. Perkembangan tiap komponen dipantau terhadap nilai standar, sehingga Anda tahu persis komponen mana yang sudah aman dan mana yang masih perlu dikejar.'],
            ] as $tahap)
                <div class="flex gap-5 bg-zinc-950 border border-zinc-900 rounded-2xl p-6">
                    <span class="shrink-0 text-red-800 font-black text-2xl leading-none">{{ $tahap[0] }}</span>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-2">{{ $tahap[1] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $tahap[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- COACH --}}
@include('layouts.partials.public-coach')

{{-- FAQ --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Pertanyaan yang Sering Diajukan</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="space-y-4">
            @foreach($faqs as $faq)
                <details class="bg-zinc-950 border border-zinc-900 rounded-2xl overflow-hidden">
                    <summary class="cursor-pointer list-none px-6 py-5 flex items-center justify-between gap-4">
                        <h3 class="flex-1 text-white font-bold text-sm md:text-base">{{ $faq[0] }}</h3>
                        <i class="fa-solid fa-plus faq-ico-buka text-red-700 text-sm shrink-0"></i>
                        <i class="fa-solid fa-minus faq-ico-tutup text-red-500 text-sm shrink-0"></i>
                    </summary>
                    <p class="px-6 pb-6 text-gray-500 text-sm leading-relaxed">{{ $faq[1] }}</p>
                </details>
            @endforeach
        </div>
    </div>
</section>

{{-- CTA --}}
<section class="bg-zinc-950 border-t border-zinc-900 py-16">
    <div class="container mx-auto px-6 max-w-3xl text-center">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-4">Mulai dari Titik Tolak yang Jelas</h2>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-8 max-w-xl mx-auto">
            Daftar untuk asesmen awal, atau tanyakan dulu apa pun yang ingin Anda ketahui tentang programnya.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ route('daftar') }}"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-solid fa-user-plus"></i> Daftar Sekarang
            </a>
            <a href="https://wa.me/6285603875675" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Tanya via WhatsApp
            </a>
        </div>
    </div>
</section>

@include('layouts.partials.public-footer')
@endsection
