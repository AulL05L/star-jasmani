@extends('layouts.app')

@section('title', 'Sistem Monitoring Performa Atlet untuk Pelatih & PPLM | Star Performance')
@section('meta_description', 'Star Performance: sistem monitoring dan evaluasi kondisi fisik atlet lintas cabang olahraga. Tes biomotor dan antropometri, Bleep Test, RAST, 1RM, readiness harian, Performance % terhadap benchmark, serta peringkat dan laporan sesi untuk pelatih PPLM dan klub.')

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'               => 'SoftwareApplication',
            'name'                => 'Star Performance',
            'applicationCategory' => 'BusinessApplication',
            'operatingSystem'     => 'Web',
            'inLanguage'          => 'id-ID',
            'url'                 => route('program.atlet'),
            'description'         => 'Sistem monitoring dan evaluasi kondisi fisik atlet lintas cabang olahraga: pencatatan tes biomotor dan antropometri, perhitungan Performance % terhadap benchmark, skor berbobot, serta peringkat dan laporan sesi.',
            'featureList'         => [
                'Tes biomotor dan antropometri',
                'Bleep Test, RAST, dan 1RM',
                'Readiness harian atlet',
                'Performance % terhadap benchmark',
                'Peringkat atlet dan laporan sesi',
            ],
            'publisher' => ['@type' => 'Organization', 'name' => 'Star Jasmani', 'url' => url('/')],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Monitoring Performa Atlet', 'item' => route('program.atlet')],
            ],
        ],
        [
            '@type'      => 'FAQPage',
            'mainEntity' => collect([
                ['Star Performance itu untuk siapa?', 'Untuk pelatih fisik, pelatih cabang olahraga, pengelola PPLM dan PPLP, serta klub yang perlu mencatat dan membandingkan kondisi fisik atletnya secara berkala.'],
                ['Tes apa saja yang bisa dicatat?', 'Tes biomotor dan antropometri, Bleep Test untuk kapasitas aerobik, RAST untuk tenaga anaerobik, serta 1RM untuk kekuatan maksimal. Readiness harian atlet juga dapat dicatat.'],
                ['Apa maksud Performance % terhadap benchmark?', 'Hasil tes tiap atlet dibandingkan dengan nilai benchmark yang ditetapkan, lalu dinyatakan sebagai persentase. Persentase tiap parameter kemudian diringkas menjadi satu skor berbobot sehingga atlet dapat diperingkat secara adil.'],
                ['Apakah bisa dipakai untuk lebih dari satu cabang olahraga?', 'Bisa. Benchmark dan parameter tes dapat diatur per angkatan, sehingga cabang olahraga dengan tuntutan fisik berbeda tetap dapat dinilai memakai standarnya masing-masing.'],
                ['Apakah akunnya sama dengan akun Star Jasmani?', 'Tidak. Akun Star Performance terpisah dari akun member Star Jasmani.'],
            ])->map(fn ($qa) => [
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

@php($portalPerformance = config('portal.performance_url'))

{{-- HERO --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-24">
    <div class="container mx-auto px-6 max-w-4xl">
        <nav aria-label="Breadcrumb" class="mb-6">
            <ol class="flex items-center gap-2 text-xs text-gray-600 uppercase tracking-widest">
                <li><a href="{{ route('home') }}" class="hover:text-red-500 transition-colors">Beranda</a></li>
                <li><i class="fa-solid fa-chevron-right text-[8px]"></i></li>
                <li class="text-gray-400">Monitoring Performa Atlet</li>
            </ol>
        </nav>

        <span class="inline-block text-red-500 text-[11px] font-bold uppercase tracking-[0.25em] mb-4">Untuk Pelatih &amp; Program Pembinaan</span>
        <h1 class="text-3xl md:text-5xl font-extrabold text-white tracking-tighter leading-tight mb-6">
            Sistem Monitoring Performa Atlet <span class="text-red-800">Lintas Cabang Olahraga</span>
        </h1>
        <p class="text-gray-400 text-base md:text-lg leading-relaxed mb-8 max-w-3xl">
            Catatan tes fisik yang tersebar di buku dan spreadsheet sulit dipakai untuk mengambil keputusan.
            Star Performance mengumpulkan hasil tes atlet Anda, menghitung capaiannya terhadap benchmark,
            lalu meringkasnya jadi satu skor dan peringkat yang bisa langsung dibaca.
        </p>

        <div class="flex flex-col sm:flex-row gap-4">
            @if ($portalPerformance)
                <a href="{{ $portalPerformance }}" target="_blank" rel="noopener"
                    class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                    Masuk Portal <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            @endif
            <a href="https://wa.me/6285603875675" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Minta Demo
            </a>
        </div>
    </div>
</section>

{{-- MASALAH --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Masalah yang Diselesaikan</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="grid sm:grid-cols-3 gap-5">
            @foreach([
                ['fa-table-list', 'Data tes tercecer', 'Hasil tes berpindah-pindah antara catatan tangan, foto, dan spreadsheet yang tidak seragam antar periode.'],
                ['fa-scale-unbalanced', 'Sulit membandingkan', 'Satuan tiap tes berbeda — detik, meter, repetisi, kilogram — sehingga capaian antar parameter tidak bisa langsung diadu.'],
                ['fa-chart-line', 'Perkembangan tak terlihat', 'Tanpa pembanding baseline, sulit membuktikan apakah program latihan benar-benar menaikkan kondisi atlet.'],
            ] as $masalah)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6">
                    <i class="fa-solid {{ $masalah[0] }} text-red-700 text-xl mb-4"></i>
                    <h3 class="text-white font-bold text-base mb-2">{{ $masalah[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $masalah[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FITUR --}}
<section class="bg-zinc-950 border-y border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Apa yang Bisa Dilakukan</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="grid sm:grid-cols-2 gap-5">
            @foreach([
                ['fa-ruler-combined', 'Tes Biomotor & Antropometri', 'Pencatatan parameter fisik dan ukuran tubuh atlet secara terstruktur per angkatan dan per periode.'],
                ['fa-stopwatch', 'Bleep Test, RAST, dan 1RM', 'Tes baku untuk kapasitas aerobik, tenaga anaerobik, dan kekuatan maksimal — tersimpan dalam satu riwayat.'],
                ['fa-heart-pulse', 'Readiness Harian Atlet', 'Pemantauan kesiapan harian sebagai bahan pertimbangan sebelum menaikkan atau menurunkan beban latihan.'],
                ['fa-ranking-star', 'Peringkat & Laporan Sesi', 'Performance % terhadap benchmark diringkas jadi skor berbobot, lalu atlet diperingkat dan hasilnya dapat dicetak sebagai laporan.'],
            ] as $fitur)
                <div class="bg-black border border-zinc-900 rounded-2xl p-6 hover:border-red-900 transition-colors">
                    <i class="fa-solid {{ $fitur[0] }} text-red-700 text-xl mb-4"></i>
                    <h3 class="text-white font-bold text-base mb-2">{{ $fitur[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $fitur[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- CARA KERJA --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Cara Kerjanya</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="space-y-5">
            @foreach([
                ['01', 'Tetapkan benchmark per angkatan', 'Parameter tes dan nilai acuannya diatur sesuai tuntutan cabang olahraga, sehingga tiap cabang dinilai memakai standarnya sendiri.'],
                ['02', 'Catat hasil tes atlet', 'Hasil tiap sesi tes dimasukkan ke sistem, lengkap dengan tanggal dan periodenya.'],
                ['03', 'Sistem menghitung Performance %', 'Tiap hasil dibandingkan dengan benchmark dan dinyatakan sebagai persentase, sehingga parameter dengan satuan berbeda menjadi setara dan bisa dibandingkan.'],
                ['04', 'Baca skor, peringkat, dan laporan', 'Persentase tiap parameter diringkas menjadi satu skor berbobot, atlet diperingkat, dan hasilnya siap dibawa ke rapat evaluasi.'],
            ] as $langkah)
                <div class="flex gap-5 bg-zinc-950 border border-zinc-900 rounded-2xl p-6">
                    <span class="shrink-0 text-red-800 font-black text-2xl leading-none">{{ $langkah[0] }}</span>
                    <div>
                        <h3 class="text-white font-bold text-lg mb-2">{{ $langkah[1] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $langkah[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <p class="mt-8 text-xs text-gray-600 flex items-start gap-2">
            <i class="fa-solid fa-circle-info mt-0.5 text-red-800"></i>
            Akun Star Performance terpisah dari akun member Star Jasmani.
        </p>
    </div>
</section>

{{-- KREDIBILITAS --}}
<section class="bg-zinc-950 border-y border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-4xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Dibangun dari Lapangan, Bukan dari Teori</h2>
        <div class="w-16 h-1 bg-red-800 mb-6"></div>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-8 max-w-3xl">
            Sistem ini disusun oleh pelatih yang memakainya sendiri untuk membina atlet, sehingga alurnya mengikuti
            cara kerja pelatih di lapangan — bukan sebaliknya.
        </p>
        <div class="grid sm:grid-cols-2 gap-4">
            @foreach([
                ['fa-medal', 'S&C Coach PPLM Pencak Silat DKI Jakarta', '2022 – sekarang'],
                ['fa-users', 'Pelatih Binaan BAPOMI DKI Jakarta', '2022'],
                ['fa-certificate', 'Pelatih Fisik Level 2 Nasional', 'ICCA'],
                ['fa-graduation-cap', 'S.Or. Ilmu Keolahragaan', 'Universitas Negeri Jakarta'],
            ] as $kredit)
                <div class="flex items-center gap-4 bg-black border border-zinc-900 rounded-xl p-5">
                    <i class="fa-solid {{ $kredit[0] }} text-red-800 text-lg shrink-0"></i>
                    <div>
                        <p class="text-white font-bold text-sm leading-snug">{{ $kredit[1] }}</p>
                        <p class="text-gray-600 text-xs mt-0.5">{{ $kredit[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- FAQ --}}
<section class="bg-black py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-2xl md:text-3xl font-extrabold text-white tracking-tighter mb-3">Pertanyaan yang Sering Diajukan</h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="space-y-4">
            @foreach([
                ['Star Performance itu untuk siapa?', 'Untuk pelatih fisik, pelatih cabang olahraga, pengelola PPLM dan PPLP, serta klub yang perlu mencatat dan membandingkan kondisi fisik atletnya secara berkala.'],
                ['Tes apa saja yang bisa dicatat?', 'Tes biomotor dan antropometri, Bleep Test untuk kapasitas aerobik, RAST untuk tenaga anaerobik, serta 1RM untuk kekuatan maksimal. Readiness harian atlet juga dapat dicatat.'],
                ['Apa maksud Performance % terhadap benchmark?', 'Hasil tes tiap atlet dibandingkan dengan nilai benchmark yang ditetapkan, lalu dinyatakan sebagai persentase. Persentase tiap parameter kemudian diringkas menjadi satu skor berbobot sehingga atlet dapat diperingkat secara adil.'],
                ['Apakah bisa dipakai untuk lebih dari satu cabang olahraga?', 'Bisa. Benchmark dan parameter tes dapat diatur per angkatan, sehingga cabang olahraga dengan tuntutan fisik berbeda tetap dapat dinilai memakai standarnya masing-masing.'],
                ['Apakah akunnya sama dengan akun Star Jasmani?', 'Tidak. Akun Star Performance terpisah dari akun member Star Jasmani.'],
            ] as $faq)
                <details class="group bg-zinc-950 border border-zinc-900 rounded-2xl overflow-hidden">
                    <summary class="cursor-pointer list-none px-6 py-5 flex items-center justify-between gap-4">
                        <h3 class="text-white font-bold text-sm md:text-base">{{ $faq[0] }}</h3>
                        <i class="fa-solid fa-plus text-red-700 text-sm shrink-0 group-open:hidden"></i>
                        <i class="fa-solid fa-minus text-red-500 text-sm shrink-0 hidden group-open:block"></i>
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
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-4">Ingin Melihatnya Bekerja?</h2>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-8 max-w-xl mx-auto">
            Ceritakan cabang olahraga dan jumlah atlet binaan Anda, dan kami tunjukkan bagaimana benchmark
            serta peringkatnya akan terbentuk.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="https://wa.me/6285603875675" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Minta Demo
            </a>
            @if ($portalPerformance)
                <a href="{{ $portalPerformance }}" target="_blank" rel="noopener"
                    class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                    Masuk Portal <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                </a>
            @endif
        </div>
    </div>
</section>

@include('layouts.partials.public-footer')
@endsection
