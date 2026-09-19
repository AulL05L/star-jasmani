@extends('layouts.app')

@section('title', 'Star Performance — Sistem Monitoring Performa Atlet untuk Pelatih, Klub & Instansi Olahraga')
@section('meta_description', 'Star Performance: sistem monitoring dan evaluasi kondisi fisik atlet lintas cabang olahraga. Tes biomotor dan antropometri, Bleep Test, RAST, 1RM, readiness harian, Performance % terhadap benchmark, peringkat atlet, dan laporan sesi — untuk pelatih fisik, klub, dan instansi olahraga.')

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
                'Benchmark dan parameter per angkatan',
            ],
            'audience'  => ['@type' => 'Audience', 'audienceType' => 'Pelatih fisik, pelatih cabang olahraga, klub, dan instansi olahraga'],
            'publisher' => ['@type' => 'Organization', 'name' => 'Star Jasmani', 'url' => url('/')],
        ],
        [
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                ['@type' => 'ListItem', 'position' => 1, 'name' => 'Beranda', 'item' => url('/')],
                ['@type' => 'ListItem', 'position' => 2, 'name' => 'Star Performance', 'item' => route('program.atlet')],
            ],
        ],
        [
            '@type'      => 'FAQPage',
            'mainEntity' => collect([
                ['Star Performance itu untuk siapa?', 'Untuk pelatih fisik, pelatih cabang olahraga, pengelola instansi olahraga, klub, serta sekolah olahraga yang perlu mencatat dan membandingkan kondisi fisik atletnya secara berkala.'],
                ['Tes apa saja yang bisa dicatat?', 'Tes biomotor dan antropometri, Bleep Test untuk kapasitas aerobik, RAST untuk tenaga anaerobik, serta 1RM untuk kekuatan maksimal. Readiness harian atlet juga dapat dicatat.'],
                ['Apa maksud Performance % terhadap benchmark?', 'Hasil tes tiap atlet dibandingkan dengan nilai benchmark yang ditetapkan, lalu dinyatakan sebagai persentase. Persentase tiap parameter kemudian diringkas menjadi satu skor berbobot sehingga atlet dapat diperingkat secara adil meski satuan tiap tesnya berbeda.'],
                ['Apakah bisa dipakai untuk lebih dari satu cabang olahraga?', 'Bisa. Benchmark dan parameter tes dapat diatur per angkatan, sehingga cabang olahraga dengan tuntutan fisik berbeda tetap dinilai memakai standarnya masing-masing.'],
                ['Bagaimana atlet mendapat akun?', 'Akun dibuat oleh admin, tidak ada pendaftaran mandiri. Ini menjaga agar hanya atlet binaan Anda yang ada di dalam sistem.'],
                ['Apakah akunnya sama dengan akun Star Jasmani?', 'Tidak. Akun Star Performance terpisah dari akun member Star Jasmani, termasuk datanya.'],
                ['Bagaimana cara mulai memakainya?', 'Mulai dari sesi konsultasi dan demo. Ceritakan cabang olahraga dan jumlah atlet binaan Anda, lalu kami tunjukkan bagaimana benchmark serta peringkatnya akan terbentuk untuk kasus Anda.'],
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
@php($wa = 'https://wa.me/6285603875675?text=' . rawurlencode('Halo Coach, saya ingin tahu lebih lanjut tentang Star Performance untuk atlet binaan saya.'))

{{-- ══════════ HERO ══════════ --}}
<section class="relative bg-zinc-950 border-b border-zinc-900 overflow-hidden">
    <div class="absolute -top-40 -right-40 w-[28rem] h-[28rem] rounded-full bg-red-900/10 blur-3xl"></div>

    <div class="relative container mx-auto px-6 max-w-5xl py-16 lg:py-24">
        <nav aria-label="Breadcrumb" class="mb-7">
            <ol class="flex items-center gap-2 text-xs text-gray-600 uppercase tracking-widest">
                <li><a href="{{ route('home') }}" class="hover:text-red-500 transition-colors">Beranda</a></li>
                <li><i class="fa-solid fa-chevron-right text-[8px]"></i></li>
                <li class="text-gray-400">Star Performance</li>
            </ol>
        </nav>

        <span class="inline-flex items-center gap-2 text-red-500 text-[11px] font-bold uppercase tracking-[0.25em] mb-5">
            <i class="fa-solid fa-chart-line"></i> Portal Pelatih
        </span>

        <h1 class="text-3xl md:text-5xl lg:text-6xl font-extrabold text-white tracking-tighter leading-[1.05] mb-6">
            Sistem Monitoring Performa Atlet <span class="text-red-800">Lintas Cabang Olahraga</span>
        </h1>

        <p class="text-gray-400 text-base md:text-lg leading-relaxed mb-8 max-w-3xl">
            Star Performance mengumpulkan hasil tes fisik atlet Anda, menghitung capaiannya terhadap benchmark,
            lalu meringkasnya menjadi satu skor dan peringkat yang bisa langsung dibaca — supaya keputusan
            pembinaan berdiri di atas data, bukan ingatan.
        </p>

        <div class="flex flex-wrap gap-2 mb-10">
            @foreach([
                'Tes Biomotor & Antropometri', 'Bleep Test · RAST · 1RM', 'Readiness Harian Atlet',
                'Performance % vs Benchmark', 'Skor Berbobot & Peringkat', 'Laporan Sesi',
            ] as $chip)
                <span class="text-[11px] text-gray-400 bg-black/60 border border-zinc-800 rounded-full px-3.5 py-1.5">{{ $chip }}</span>
            @endforeach
        </div>

        <div class="flex flex-col sm:flex-row gap-4">
            <a href="{{ $wa }}" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Jadwalkan Demo
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

{{-- ══════════ EMPAT NILAI UTAMA ══════════ --}}
<section class="bg-black border-b border-zinc-900 py-12">
    <div class="container mx-auto px-6 max-w-5xl">
        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['fa-database', 'Satu Sumber Data', 'Seluruh hasil tes atlet tersimpan di satu tempat, bukan tersebar di buku dan spreadsheet.'],
                ['fa-scale-balanced', 'Perbandingan yang Adil', 'Detik, meter, repetisi, dan kilogram disetarakan jadi persentase terhadap benchmark.'],
                ['fa-arrow-trend-up', 'Perkembangan Terlihat', 'Setiap periode tes tercatat, sehingga naik-turunnya kondisi atlet bisa ditunjukkan.'],
                ['fa-file-lines', 'Siap Dibawa Rapat', 'Peringkat dan laporan sesi bisa langsung dipakai saat evaluasi program.'],
            ] as $v)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-5">
                    <i class="fa-solid {{ $v[0] }} text-red-700 text-lg mb-3"></i>
                    <h3 class="text-white font-bold text-sm mb-1.5">{{ $v[1] }}</h3>
                    <p class="text-gray-500 text-xs leading-relaxed">{{ $v[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ TAMPILAN SISTEM ══════════ --}}
{{--
    Gambar berikut adalah mockup, BUKAN tangkapan layar produk. Warna, nama menu, dan
    nama biomotornya memang diambil dari sistem aslinya, tapi nama atlet dan angkanya
    contoh. Labelnya dipasang terbuka supaya tak seorang pun mengira ini data nyata.
--}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Seperti Apa Tampilannya</h2>
        <div class="w-16 h-1 bg-red-800 mb-5"></div>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-6 max-w-3xl">
            Beranda pelatih: kesiapan tim hari ini, atlet yang perlu perhatian, bentuk tim per biomotor,
            dan peringkat atlet dalam satu layar.
        </p>
        <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-amber-500/80 border border-amber-900/50 bg-amber-950/20 rounded-full px-3 py-1.5"><i class="fa-solid fa-circle-info"></i> Ilustrasi tampilan &middot; data contoh</span>

        <figure class="mt-8">
            <div class="rounded-2xl border border-zinc-800 overflow-hidden bg-black">
                <div class="flex items-center gap-2 px-4 py-2.5 border-b border-zinc-800 bg-zinc-950"><span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span><span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span><span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span><span class="ml-3 text-[10px] text-gray-600 tracking-widest">performance.starjasmani.id</span></div>
                <a href="{{ asset('pict/star-performance/dasbor-pelatih.webp') }}" target="_blank" rel="noopener" class="block">
                    <img src="{{ asset('pict/star-performance/dasbor-pelatih.webp') }}"
                         alt="Dasbor pelatih Star Performance: kartu kesiapan, daftar atlet perlu perhatian, grafik bentuk tim, dan tabel peringkat atlet"
                         class="block w-full" loading="lazy" width="1400" height="1408" />
                </a>
            </div>
        </figure>

        <div class="grid md:grid-cols-2 gap-8 items-center mt-10">
            <figure>
                <a href="{{ asset('pict/star-performance/tampilan-ponsel.webp') }}" target="_blank" rel="noopener" class="block">
                    <img src="{{ asset('pict/star-performance/tampilan-ponsel.webp') }}"
                         alt="Tampilan Star Performance di ponsel: beranda dan laci navigasi yang terbuka"
                         class="block w-full rounded-2xl border border-zinc-800" loading="lazy" width="980" height="935" />
                </a>
            </figure>
            <div>
                <h3 class="text-white font-bold text-lg mb-3">Dipakai di pinggir lapangan</h3>
                <p class="text-gray-500 text-sm leading-relaxed mb-4">
                    Hasil tes paling sering dicatat saat sesi masih berjalan, bukan setelah sampai rumah.
                    Karena itu tampilan ponselnya bukan versi yang dipangkas: menu yang ada di desktop
                    ada semua di sini.
                </p>
                <ul class="space-y-2.5 text-sm text-gray-500">
                    <li class="flex gap-3"><i class="fa-solid fa-check text-red-700 mt-1 text-xs"></i> Baris sentuh besar, nyaman ditekan dengan tangan berkeringat</li>
                    <li class="flex gap-3"><i class="fa-solid fa-check text-red-700 mt-1 text-xs"></i> Tabel lebar digeser di dalam kartunya sendiri, halaman tidak ikut melebar</li>
                    <li class="flex gap-3"><i class="fa-solid fa-check text-red-700 mt-1 text-xs"></i> Tidak ada menu yang disembunyikan di versi ponsel</li>
                </ul>
            </div>
        </div>
    <p class="mt-4 text-xs text-gray-600"><i class="fa-solid fa-up-right-and-down-left-from-center text-[10px] mr-1.5"></i>Ketuk gambar untuk membukanya dalam ukuran penuh.</p>
    </div>
</section>

{{-- ══════════ APA ITU ══════════ --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <span class="inline-block text-red-500 text-[11px] font-bold uppercase tracking-[0.25em] mb-3">Apa Itu Star Performance</span>
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-5">
            Bukan Buku Catatan Digital — Tapi Alat Ukur
        </h2>
        <div class="w-16 h-1 bg-red-800 mb-8"></div>

        <div class="grid lg:grid-cols-2 gap-8 mb-10">
            <p class="text-gray-400 text-sm md:text-base leading-relaxed">
                Mencatat hasil tes itu mudah. Yang sulit adalah menjawab pertanyaan berikutnya: atlet mana yang
                paling siap, komponen mana yang tertinggal, dan apakah program tiga bulan terakhir benar-benar
                menaikkan kondisi mereka.
            </p>
            <p class="text-gray-400 text-sm md:text-base leading-relaxed">
                Star Performance dibangun untuk menjawab itu. Hasil tes tidak berhenti sebagai angka, tapi
                dibandingkan dengan benchmark, diberi bobot, lalu diperingkat — sehingga pelatih punya dasar
                yang sama setiap kali harus mengambil keputusan.
            </p>
        </div>

        <div class="grid md:grid-cols-3 gap-5">
            @foreach([
                ['Terpusat', 'Data seluruh atlet binaan berada dalam satu sistem, bisa ditelusuri per angkatan dan per periode.'],
                ['Terukur', 'Setiap hasil dibandingkan dengan nilai acuan yang Anda tetapkan sendiri, bukan standar orang lain.'],
                ['Tertelusur', 'Riwayat tes tersimpan, sehingga perkembangan atlet bisa dibuktikan, bukan sekadar dirasakan.'],
            ] as $p)
                <div class="bg-black border border-zinc-900 rounded-2xl p-6">
                    <h3 class="text-white font-bold text-base mb-2">{{ $p[0] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $p[1] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ MASALAH ══════════ --}}
<section class="bg-black border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Masalah yang Sering Ditemui</h2>
        <div class="w-16 h-1 bg-red-800 mb-4"></div>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-10 max-w-3xl">
            Kalau sebagian dari ini terasa familier, masalahnya bukan pada ketelitian Anda — tapi pada
            alat pencatatannya.
        </p>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['fa-table-list', 'Data tercecer di banyak tempat', 'Hasil tes berpindah antara catatan tangan, foto, grup WhatsApp, dan spreadsheet yang formatnya berubah tiap periode.'],
                ['fa-scale-unbalanced', 'Satuan tes tidak sebanding', 'Bleep test dalam level, RAST dalam watt, 1RM dalam kilogram — tidak bisa langsung diadu untuk menentukan siapa yang paling siap.'],
                ['fa-chart-line', 'Perkembangan sulit dibuktikan', 'Tanpa baseline yang tersimpan rapi, sulit menunjukkan ke pengurus atau orang tua bahwa program latihan berhasil.'],
                ['fa-user-group', 'Penilaian antar atlet terasa subjektif', 'Tanpa skor terpadu, pemilihan atlet inti mudah dipertanyakan karena tidak ada dasar angka yang seragam.'],
                ['fa-heart-pulse', 'Kesiapan harian tidak tercatat', 'Kondisi atlet naik-turun tiap hari, tapi keputusan menaikkan beban sering diambil tanpa catatan apa pun.'],
                ['fa-clock-rotate-left', 'Menyusun laporan makan waktu', 'Menjelang evaluasi, data harus dikumpulkan ulang dan dihitung manual — berjam-jam untuk sesuatu yang seharusnya otomatis.'],
            ] as $m)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6 hover:border-red-900/60 transition-colors">
                    <i class="fa-solid {{ $m[0] }} text-red-700 text-lg mb-4"></i>
                    <h3 class="text-white font-bold text-base mb-2">{{ $m[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $m[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ ALUR KERJA ══════════ --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Cara Kerjanya</h2>
        <div class="w-16 h-1 bg-red-800 mb-4"></div>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-10 max-w-3xl">
            Lima langkah, dan hanya dua yang menuntut pekerjaan manual dari Anda.
        </p>

        <div class="space-y-4">
            @foreach([
                ['01', 'Tetapkan benchmark per angkatan', 'Parameter tes dan nilai acuannya diatur sesuai tuntutan cabang olahraga. Sekali disusun, dipakai terus untuk angkatan itu.', true],
                ['02', 'Catat hasil tes atlet', 'Hasil tiap sesi dimasukkan lengkap dengan tanggal dan periodenya — biomotor, antropometri, Bleep Test, RAST, 1RM.', true],
                ['03', 'Sistem menghitung Performance %', 'Tiap hasil dibandingkan dengan benchmark dan dinyatakan sebagai persentase, sehingga parameter dengan satuan berbeda menjadi setara.', false],
                ['04', 'Skor berbobot dan peringkat terbentuk', 'Persentase tiap parameter diringkas jadi satu skor, lalu atlet diperingkat berdasarkan skor itu.', false],
                ['05', 'Laporan sesi siap dibaca', 'Hasilnya bisa langsung dibawa ke rapat evaluasi atau ditunjukkan ke atlet yang bersangkutan.', false],
            ] as $s)
                <div class="flex gap-5 bg-black border border-zinc-900 rounded-2xl p-6">
                    <span class="shrink-0 text-red-800 font-black text-2xl leading-none w-8">{{ $s[0] }}</span>
                    <div class="flex-1">
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <h3 class="text-white font-bold text-lg">{{ $s[1] }}</h3>
                            <span class="text-[10px] uppercase tracking-widest font-bold px-2 py-0.5 rounded-full {{ $s[3] ? 'text-gray-500 border border-zinc-800' : 'text-red-400 border border-red-900/60' }}">
                                {{ $s[3] ? 'Anda' : 'Otomatis' }}
                            </span>
                        </div>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $s[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ FITUR ══════════ --}}
<section class="bg-black border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Fitur</h2>
        <div class="w-16 h-1 bg-red-800 mb-10"></div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-3 gap-5">
            @foreach([
                ['fa-ruler-combined', 'Tes Biomotor & Antropometri', 'Pencatatan parameter fisik dan ukuran tubuh atlet secara terstruktur per angkatan dan per periode.'],
                ['fa-gauge-high', 'Bleep Test', 'Pencatatan kapasitas aerobik dengan riwayat antar periode, sehingga tren daya tahan terlihat.'],
                ['fa-bolt', 'RAST', 'Running-based Anaerobic Sprint Test untuk mengukur tenaga anaerobik atlet.'],
                ['fa-dumbbell', '1RM', 'Pencatatan kekuatan maksimal, tersimpan dalam satu riwayat bersama parameter lainnya.'],
                ['fa-heart-pulse', 'Readiness Harian', 'Pemantauan kesiapan harian sebagai bahan pertimbangan sebelum menaikkan atau menurunkan beban latihan.'],
                ['fa-percent', 'Performance % terhadap Benchmark', 'Setiap hasil dinyatakan sebagai persentase capaian, membuat parameter berbeda satuan bisa dibandingkan.'],
                ['fa-ranking-star', 'Skor Berbobot & Peringkat', 'Persentase tiap parameter diringkas jadi satu skor, lalu atlet diperingkat secara konsisten.'],
                ['fa-file-lines', 'Laporan Sesi', 'Hasil tiap sesi tes dirangkum menjadi laporan yang siap dicetak atau dibagikan.'],
                ['fa-layer-group', 'Benchmark per Angkatan', 'Tiap angkatan punya parameter dan nilai acuannya sendiri, sehingga lintas cabang olahraga tetap adil.'],
            ] as $f)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6 hover:border-red-900/60 transition-colors">
                    <i class="fa-solid {{ $f[0] }} text-red-700 text-lg mb-4"></i>
                    <h3 class="text-white font-bold text-base mb-2">{{ $f[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $f[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ MEMBACA ANGKA ══════════ --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Layar untuk Membaca Angka</h2>
        <div class="w-16 h-1 bg-red-800 mb-5"></div>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-6 max-w-3xl">
            Dasbor cabang olahraga menjawab pertanyaan yang biasanya paling lama dicari jawabannya:
            biomotor mana yang tertinggal, gerakan angkat mana yang paling lemah, dan siapa yang
            power anaerobiknya paling tinggi.
        </p>
        <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-amber-500/80 border border-amber-900/50 bg-amber-950/20 rounded-full px-3 py-1.5"><i class="fa-solid fa-circle-info"></i> Ilustrasi tampilan &middot; data contoh</span>

        <figure class="mt-8">
            <div class="rounded-2xl border border-zinc-800 overflow-hidden bg-black">
                <div class="flex items-center gap-2 px-4 py-2.5 border-b border-zinc-800 bg-zinc-950"><span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span><span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span><span class="w-2.5 h-2.5 rounded-full bg-zinc-700"></span><span class="ml-3 text-[10px] text-gray-600 tracking-widest">performance.starjasmani.id</span></div>
                <a href="{{ asset('pict/star-performance/dasbor-cabor.webp') }}" target="_blank" rel="noopener" class="block">
                    <img src="{{ asset('pict/star-performance/dasbor-cabor.webp') }}"
                         alt="Dasbor cabang olahraga: sparkline arah gerak tiap biomotor, rerata 1RM per gerakan, dan grafik power anaerobik RAST"
                         class="block w-full" loading="lazy" width="1400" height="1564" />
                </a>
            </div>
            <figcaption class="mt-3 text-xs text-gray-600 leading-relaxed">
                Tiap biomotor punya grafik kecilnya sendiri, bukan delapan garis bertumpuk dalam satu grafik.
            </figcaption>
        </figure>
    <p class="mt-4 text-xs text-gray-600"><i class="fa-solid fa-up-right-and-down-left-from-center text-[10px] mr-1.5"></i>Ketuk gambar untuk membukanya dalam ukuran penuh.</p>
    </div>
</section>

{{-- ══════════ LAPORAN CETAK ══════════ --}}
<section class="bg-black border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Laporan yang Bisa Dicetak</h2>
        <div class="w-16 h-1 bg-red-800 mb-5"></div>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-6 max-w-3xl">
            Rapat evaluasi dan pengurus cabang masih berjalan di atas kertas. Dua lembar ini keluar
            langsung dari data yang sama, tanpa perlu disalin ulang ke Excel.
        </p>
        <span class="inline-flex items-center gap-2 text-[10px] font-bold uppercase tracking-widest text-amber-500/80 border border-amber-900/50 bg-amber-950/20 rounded-full px-3 py-1.5"><i class="fa-solid fa-circle-info"></i> Ilustrasi tampilan &middot; data contoh</span>

        <div class="grid md:grid-cols-2 gap-8 mt-8">
            <figure>
                <figcaption class="mb-3">
                    <span class="block text-white font-bold text-sm">Profil Kondisi Fisik Atlet</span>
                    <span class="block text-gray-600 text-xs mt-1 leading-relaxed">Satu lembar per atlet. Dipakai untuk pengajuan ke pengurus cabang atau dinas.</span>
                </figcaption>
                <a href="{{ asset('pict/star-performance/profil-kondisi-fisik.webp') }}" target="_blank" rel="noopener" class="block">
                    <img src="{{ asset('pict/star-performance/profil-kondisi-fisik.webp') }}"
                         alt="Lembar A4 Profil Kondisi Fisik Atlet: data fisik, item tes per biomotor, radar performance parameter, 1RM, RAST, dan Bleep Test"
                         class="block w-full rounded-lg border border-zinc-800" loading="lazy" width="1000" height="1520" />
                </a>
            </figure>
            <figure>
                <figcaption class="mb-3">
                    <span class="block text-white font-bold text-sm">Rekap Performa Per Sesi</span>
                    <span class="block text-gray-600 text-xs mt-1 leading-relaxed">Satu lembar per sesi evaluasi, berisi peringkat seluruh atlet dan juara tiap biomotor.</span>
                </figcaption>
                <a href="{{ asset('pict/star-performance/rekap-performa.webp') }}" target="_blank" rel="noopener" class="block">
                    <img src="{{ asset('pict/star-performance/rekap-performa.webp') }}"
                         alt="Lembar A4 Rekap Performa Per Sesi: tabel peringkat atlet per biomotor, juara tiap kategori, dan rerata tim"
                         class="block w-full rounded-lg border border-zinc-800" loading="lazy" width="1000" height="1397" />
                </a>
            </figure>
        </div>
    <p class="mt-4 text-xs text-gray-600"><i class="fa-solid fa-up-right-and-down-left-from-center text-[10px] mr-1.5"></i>Ketuk gambar untuk membukanya dalam ukuran penuh.</p>
    </div>
</section>

{{-- ══════════ UNTUK SIAPA ══════════ --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Untuk Siapa</h2>
        <div class="w-16 h-1 bg-red-800 mb-10"></div>

        <div class="grid sm:grid-cols-2 gap-5">
            @foreach([
                ['fa-medal', 'Instansi Olahraga', 'Program pembinaan yang harus melaporkan perkembangan atlet secara berkala dan terukur.'],
                ['fa-shield-halved', 'Klub & Sekolah Olahraga', 'Pembinaan berjenjang dengan banyak atlet dan banyak angkatan sekaligus.'],
                ['fa-user-tie', 'Pelatih Fisik Mandiri', 'Pelatih S&C yang menangani beberapa atlet atau tim dan butuh dasar angka untuk programnya.'],
                ['fa-people-group', 'Pengurus Cabang Olahraga', 'Pihak yang perlu membandingkan kesiapan atlet lintas klub memakai standar yang sama.'],
            ] as $u)
                <div class="flex gap-5 bg-black border border-zinc-900 rounded-2xl p-6">
                    <i class="fa-solid {{ $u[0] }} text-red-700 text-xl shrink-0 mt-1"></i>
                    <div>
                        <h3 class="text-white font-bold text-base mb-2">{{ $u[1] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $u[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ KEAMANAN & AKSES ══════════ --}}
<section class="bg-black border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Kendali Akses &amp; Data</h2>
        <div class="w-16 h-1 bg-red-800 mb-4"></div>
        <p class="text-gray-500 text-sm md:text-base leading-relaxed mb-10 max-w-3xl">
            Data kondisi fisik atlet itu sensitif — apalagi untuk atlet di bawah umur. Karena itu aksesnya dibatasi sejak awal.
        </p>

        <div class="grid sm:grid-cols-3 gap-5">
            @foreach([
                ['fa-user-lock', 'Akun dibuat admin', 'Tidak ada pendaftaran mandiri. Hanya atlet dan pelatih yang Anda daftarkan yang bisa masuk.'],
                ['fa-key', 'Hak akses bertingkat', 'Pelatih dan atlet melihat apa yang menjadi haknya masing-masing, bukan seluruh isi sistem.'],
                ['fa-lock', 'Terpisah dari Star Jasmani', 'Akun dan data Star Performance berdiri sendiri, tidak bercampur dengan member Star Jasmani.'],
            ] as $k)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6">
                    <i class="fa-solid {{ $k[0] }} text-red-700 text-lg mb-4"></i>
                    <h3 class="text-white font-bold text-base mb-2">{{ $k[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $k[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ KREDIBILITAS ══════════ --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Dibangun dari Lapangan</h2>
        <div class="w-16 h-1 bg-red-800 mb-4"></div>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-10 max-w-3xl">
            Sistem ini disusun oleh pelatih yang memakainya sendiri untuk membina atlet, sehingga alurnya
            mengikuti cara kerja pelatih di lapangan — bukan sebaliknya.
        </p>

        <div class="grid sm:grid-cols-2 gap-4">
            @foreach([
                ['fa-medal', 'S&C Coach PPLM Pencak Silat DKI Jakarta', '2022 – sekarang'],
                ['fa-users', 'Pelatih Binaan BAPOMI DKI Jakarta', '2022'],
                ['fa-certificate', 'Pelatih Fisik Level 2 Nasional', 'ICCA'],
                ['fa-graduation-cap', 'S.Or. Ilmu Keolahragaan', 'Universitas Negeri Jakarta'],
            ] as $c)
                <div class="flex items-center gap-4 bg-black border border-zinc-900 rounded-xl p-5">
                    <i class="fa-solid {{ $c[0] }} text-red-800 text-lg shrink-0"></i>
                    <div>
                        <p class="text-white font-bold text-sm leading-snug">{{ $c[1] }}</p>
                        <p class="text-gray-600 text-xs mt-0.5">{{ $c[2] }}</p>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ CARA MEMULAI ══════════ --}}
<section class="bg-black border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-5xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Cara Memulai</h2>
        <div class="w-16 h-1 bg-red-800 mb-10"></div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-5">
            @foreach([
                ['01', 'Konsultasi', 'Ceritakan cabang olahraga, jumlah atlet binaan, dan tes apa saja yang selama ini Anda pakai.'],
                ['02', 'Demo', 'Kami tunjukkan bagaimana benchmark dan peringkat akan terbentuk untuk kasus Anda sendiri.'],
                ['03', 'Penyiapan', 'Akun, angkatan, parameter tes, dan nilai benchmark disiapkan sesuai kebutuhan Anda.'],
                ['04', 'Pendampingan', 'Anda dibimbing sampai sesi tes pertama tercatat dan peringkat pertama terbentuk.'],
            ] as $l)
                <div class="bg-zinc-950 border border-zinc-900 rounded-2xl p-6">
                    <span class="text-red-800 font-black text-xl leading-none">{{ $l[0] }}</span>
                    <h3 class="text-white font-bold text-base mt-3 mb-2">{{ $l[1] }}</h3>
                    <p class="text-gray-500 text-sm leading-relaxed">{{ $l[2] }}</p>
                </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ══════════ FAQ ══════════ --}}
<section class="bg-zinc-950 border-b border-zinc-900 py-16 lg:py-20">
    <div class="container mx-auto px-6 max-w-3xl">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-3">Pertanyaan yang Sering Diajukan</h2>
        <div class="w-16 h-1 bg-red-800 mb-10"></div>

        <div class="space-y-4">
            @foreach([
                ['Star Performance itu untuk siapa?', 'Untuk pelatih fisik, pelatih cabang olahraga, pengelola instansi olahraga, klub, serta sekolah olahraga yang perlu mencatat dan membandingkan kondisi fisik atletnya secara berkala.'],
                ['Tes apa saja yang bisa dicatat?', 'Tes biomotor dan antropometri, Bleep Test untuk kapasitas aerobik, RAST untuk tenaga anaerobik, serta 1RM untuk kekuatan maksimal. Readiness harian atlet juga dapat dicatat.'],
                ['Apa maksud Performance % terhadap benchmark?', 'Hasil tes tiap atlet dibandingkan dengan nilai benchmark yang ditetapkan, lalu dinyatakan sebagai persentase. Persentase tiap parameter kemudian diringkas menjadi satu skor berbobot sehingga atlet dapat diperingkat secara adil meski satuan tiap tesnya berbeda.'],
                ['Apakah bisa dipakai untuk lebih dari satu cabang olahraga?', 'Bisa. Benchmark dan parameter tes dapat diatur per angkatan, sehingga cabang olahraga dengan tuntutan fisik berbeda tetap dinilai memakai standarnya masing-masing.'],
                ['Bagaimana atlet mendapat akun?', 'Akun dibuat oleh admin, tidak ada pendaftaran mandiri. Ini menjaga agar hanya atlet binaan Anda yang ada di dalam sistem.'],
                ['Apakah akunnya sama dengan akun Star Jasmani?', 'Tidak. Akun Star Performance terpisah dari akun member Star Jasmani, termasuk datanya.'],
                ['Bagaimana cara mulai memakainya?', 'Mulai dari sesi konsultasi dan demo. Ceritakan cabang olahraga dan jumlah atlet binaan Anda, lalu kami tunjukkan bagaimana benchmark serta peringkatnya akan terbentuk untuk kasus Anda.'],
            ] as $faq)
                <details class="bg-black border border-zinc-900 rounded-2xl overflow-hidden">
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

{{-- ══════════ CTA ══════════ --}}
<section class="relative bg-black py-20 overflow-hidden">
    <div class="absolute -bottom-32 left-1/2 -translate-x-1/2 w-[32rem] h-[32rem] rounded-full bg-red-900/10 blur-3xl"></div>
    <div class="relative container mx-auto px-6 max-w-3xl text-center">
        <h2 class="text-2xl md:text-4xl font-extrabold text-white tracking-tighter mb-4">Lihat Dulu Cara Kerjanya</h2>
        <p class="text-gray-400 text-sm md:text-base leading-relaxed mb-9 max-w-xl mx-auto">
            Ceritakan cabang olahraga dan jumlah atlet binaan Anda. Kami tunjukkan bagaimana benchmark
            serta peringkatnya akan terbentuk — tanpa biaya, tanpa harus mendaftar dulu.
        </p>
        <div class="flex flex-col sm:flex-row gap-4 justify-center">
            <a href="{{ $wa }}" target="_blank" rel="noopener"
                class="inline-flex items-center justify-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-brands fa-whatsapp text-lg"></i> Jadwalkan Demo
            </a>
            <a href="mailto:starjasmani@gmail.com?subject=Pertanyaan%20Star%20Performance"
                class="inline-flex items-center justify-center gap-2 border-2 border-zinc-700 hover:border-red-800 text-white font-bold py-3.5 px-8 rounded-full transition-all duration-300">
                <i class="fa-solid fa-envelope"></i> Kirim Email
            </a>
        </div>
    </div>
</section>

@include('layouts.partials.public-footer')
@endsection
