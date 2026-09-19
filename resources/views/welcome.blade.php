@extends('layouts.app')

@section('title', 'Pelatihan Fisik Persiapan Kedinasan TNI & POLRI | Star Jasmani')
@section('meta_description', 'Star Jasmani: pelatihan fisik bersertifikasi ICCA untuk persiapan seleksi kedinasan TNI/POLRI, kebugaran umum & strength conditioning, serta pemulihan pasca cedera. Coba kalkulator nilai Samapta POLRI gratis.')
@section('meta_image', asset('pict/bg-home.jpg'))

@push('schema')
<script type="application/ld+json">
{!! json_encode([
    '@context' => 'https://schema.org',
    '@graph'   => [
        [
            '@type'       => 'SportsActivityLocation',
            '@id'         => url('/') . '#organization',
            'name'        => 'Star Jasmani',
            'url'         => url('/'),
            'logo'        => asset('pict/logo-removebg.png'),
            'image'       => asset('pict/bg-home.jpg'),
            'description' => 'Penyedia program pelatihan fisik berbasis sport science untuk persiapan kedinasan TNI/POLRI, kebugaran umum, dan pendampingan performa atlet.',
            'telephone'   => '+62 856-0387-5675',
            'areaServed'  => ['@type' => 'AdministrativeArea', 'name' => 'DKI Jakarta, Indonesia'],
            'sameAs'      => ['https://wa.me/6285603875675'],
            'employee'    => [
                '@type'      => 'Person',
                'name'       => 'Fariz Fahrun, S.Or.',
                'jobTitle'   => 'Strength & Conditioning Coach',
                'alumniOf'   => 'Universitas Negeri Jakarta',
                'hasCredential' => 'Pelatih Fisik Level 2 Nasional (ICCA)',
            ],
            'hasOfferCatalog' => [
                '@type' => 'OfferCatalog',
                'name'  => 'Program Pelatihan Star Jasmani',
                'itemListElement' => [
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Persiapan Kedinasan TNI & POLRI', 'description' => 'Standardisasi tes Samapta A & B, periodisasi latihan menjelang seleksi, dan simulasi penilaian poin maksimal.']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Kebugaran Umum & Strength Conditioning', 'description' => 'Weight management, body shaping, dan functional strength training berbasis sport science.']],
                    ['@type' => 'Offer', 'itemOffered' => ['@type' => 'Service', 'name' => 'Pemulihan Pasca Cedera', 'description' => 'Latihan stability & mobility serta protokol return to sport.']],
                ],
            ],
        ],
        [
            '@type'     => 'WebSite',
            '@id'       => url('/') . '#website',
            'url'       => url('/'),
            'name'      => 'Star Jasmani',
            'inLanguage'=> 'id-ID',
            'publisher' => ['@id' => url('/') . '#organization'],
        ],
    ],
], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) !!}
</script>
@endpush

@push('styles')
<style>
    .nav-open { display: none; }
    @media (max-width: 768px) {
        .nav-items { display: none; }
        .nav-open { display: block; }
    }
    @keyframes shimmer {
        0% { background-position: -200% 0; }
        100% { background-position: 200% 0; }
    }
    .animate-shimmer {
        background: linear-gradient(90deg, #d1d5db 0%, #ffffff 50%, #d1d5db 100%);
        background-size: 200% auto;
        /*
            `no-repeat` membuat teks lenyap sepenuhnya di sebagian siklus animasi:
            saat background-position bergeser ke ±200%, gradiennya keluar dari area
            teks dan tidak ada lagi yang mengisi -webkit-text-fill-color: transparent.
            Dengan `repeat`, gradien ubin sehingga teks selalu terwarnai.
        */
        background-repeat: repeat;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 3s infinite linear;
        display: inline-block;
    }
    @keyframes border-pulse {
        0%, 100% { box-shadow: 0 0 0 0 rgba(153,27,27,0.4); }
        50% { box-shadow: 0 0 0 6px rgba(153,27,27,0); }
    }
    .btn-login-pulse { animation: border-pulse 2.5s ease-in-out infinite; }
</style>
@endpush

@section('content')

@include('layouts.partials.public-header')

{{-- HOME --}}
<section id="home" class="text-gray-100 py-48 lg:py-32" style="background-image: url('{{ asset('pict/bg-home.jpg') }}'); background-size: cover; background-position: center;">
    <div class="container mx-auto px-6 text-center bg-transparent bg-opacity-70 rounded-3xl p-10 md:p-12 shadow-2xl backdrop-blur-sm">
        <div class="mb-5 flex items-center justify-center gap-2">
            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            <span class="text-xs md:text-sm font-medium text-gray-200 tracking-widest uppercase">Star Jasmani</span>
        </div>
        <h1 class="text-4xl md:text-5xl font-extrabold text-gray-100 mb-6 tracking-tighter leading-tight uppercase text-center">
            <span class="block">TRAINING WITH,</span>
            <span class="block text-red-800">MENTALITY.</span>
        </h1>
        <p class="text-lg md:text-xl font-light text-gray-100 mb-10 max-w-3xl mx-auto leading-relaxed">
            <strong class="font-semibold text-gray-300 animate-shimmer">Program Didukung Pelatih Bersertifikasi Nasional</strong>
            <span class="block mt-3 text-base md:text-lg text-gray-300 font-light">
                Pelatihan fisik persiapan kedinasan <strong class="font-semibold text-white">TNI &amp; POLRI</strong>,
                kebugaran umum &amp; <strong class="font-semibold text-white">strength conditioning</strong>,
                serta pendampingan performa atlet untuk instansi olahraga dan cabang olahraga prestasi.
            </span>
        </p>
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-center pb-6">
            <a href="{{ route('daftar') }}" class="w-full sm:w-auto inline-block bg-red-800 hover:bg-red-950 text-white font-bold py-4 px-10 rounded-full transition duration-300 shadow-lg transform hover:scale-105">
                GABUNG SEKARANG!
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 text-red-500 hover:text-white hover:bg-red-800 font-bold py-4 px-10 rounded-full border-2 border-red-800 transition duration-300">
                <i class="fa-solid fa-shield-halved"></i>
                LOGIN MEMBER / COACH
            </a>
            <a href="{{ route('kalkulator.polri') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 text-white hover:text-red-400 font-bold py-4 px-10 rounded-full border-2 border-white/30 hover:border-red-800 transition duration-300">
                <i class="fa-solid fa-calculator"></i>
                CEK NILAI JASMANI POLRI
            </a>
        </div>
    </div>
</section>

{{-- PROFILE --}}
<section id="profile" class="py-20 lg:py-32 bg-black text-white">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mb-24">
            <h2 class="text-red-800 font-black uppercase tracking-[0.3em] text-xs mb-6">The Origin</h2>
            <h3 class="text-4xl md:text-6xl font-extrabold tracking-tighter mb-8 leading-none">
                EST. 2024 <br> <span class="text-gray-500">JAKARTA, INDONESIA</span>
            </h3>
            <p class="text-gray-400 text-lg md:text-xl leading-relaxed max-w-2xl">
                Star Jasmani didirikan oleh <span class="text-white font-bold">Fariz Fahrun, S.Or.</span> pada 28 Oktober 2024. Berawal dari visi untuk membantu masyarakat, atlet, dan calon anggota institusi memaksimalkan kapasitas fisiknya melalui metode latihan berbasis <span class="text-white font-semibold">sport science</span> yang objektif, terukur, dan efektif.
            </p>
            <p class="text-gray-500 text-base md:text-lg leading-relaxed max-w-2xl mt-4">
                Serta membantu mencapai potensi fisik terbaiknya dengan menggabungkan evaluasi, pelatihan, dan pengembangan performa secara komprehensif.
            </p>
        </div>
        {{-- CORE VALUES --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-24">
            <div class="relative overflow-hidden bg-zinc-950 border border-zinc-800 hover:border-red-800 transition-colors duration-300 group p-8">
                <span class="absolute -top-4 -right-2 text-[7rem] font-black text-zinc-900 leading-none select-none group-hover:text-zinc-800 transition-colors duration-300">01</span>
                <div class="relative z-10">
                    <div class="w-10 h-10 rounded-full bg-red-800/20 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-flask text-red-500 text-sm"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-red-500 mb-3">Core Value 01</p>
                    <h4 class="text-3xl font-black uppercase tracking-tight text-white mb-3">Scientific</h4>
                    <p class="text-zinc-400 text-sm leading-relaxed">Setiap program dirancang berdasarkan prinsip sport science — bukan intuisi, tapi evidence-based training.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-0 bg-red-700 group-hover:w-full transition-all duration-500"></div>
            </div>

            <div class="relative overflow-hidden bg-zinc-950 border border-red-800 group p-8">
                <span class="absolute -top-4 -right-2 text-[7rem] font-black text-red-950 leading-none select-none">02</span>
                <div class="relative z-10">
                    <div class="w-10 h-10 rounded-full bg-red-800/30 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-chart-line text-red-500 text-sm"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-red-500 mb-3">Core Value 02</p>
                    <h4 class="text-3xl font-black uppercase tracking-tight text-white mb-3">Measured</h4>
                    <p class="text-zinc-400 text-sm leading-relaxed">Progres yang tidak terukur tidak bisa dimanage. Setiap sesi menghasilkan data yang bisa dipantau dan dievaluasi.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-full bg-red-700"></div>
            </div>

            <div class="relative overflow-hidden bg-zinc-950 border border-zinc-800 hover:border-red-800 transition-colors duration-300 group p-8">
                <span class="absolute -top-4 -right-2 text-[7rem] font-black text-zinc-900 leading-none select-none group-hover:text-zinc-800 transition-colors duration-300">03</span>
                <div class="relative z-10">
                    <div class="w-10 h-10 rounded-full bg-red-800/20 flex items-center justify-center mb-6">
                        <i class="fa-solid fa-brain text-red-500 text-sm"></i>
                    </div>
                    <p class="text-xs font-bold uppercase tracking-[0.3em] text-red-500 mb-3">Core Value 03</p>
                    <h4 class="text-3xl font-black uppercase tracking-tight text-white mb-3">Mentality</h4>
                    <p class="text-zinc-400 text-sm leading-relaxed">Fisik yang kuat dibangun di atas mental yang tangguh. Kami melatih keduanya secara bersamaan dan terstruktur.</p>
                </div>
                <div class="absolute bottom-0 left-0 h-1 w-0 bg-red-700 group-hover:w-full transition-all duration-500"></div>
            </div>
        </div>

        {{-- VISI MISI --}}
        <div class="grid md:grid-cols-2 gap-0 border border-zinc-800">

            {{-- VISION --}}
            <div class="p-10 lg:p-14 border-b md:border-b-0 md:border-r border-zinc-800">
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-red-500 mb-8">— Vision</p>
                <div class="relative">
                    <span class="absolute -top-6 -left-2 text-[8rem] leading-none font-black text-zinc-900 select-none">"</span>
                    <p class="relative z-10 text-xl lg:text-2xl font-semibold text-white leading-snug">
                        Memberikan pelatihan tepat guna untuk hasil optimal dalam membentuk
                        <span class="text-red-500"> fisik dan mental</span>,
                        serta menjadikan olahraga sebagai investasi kesehatan.
                    </p>
                    <span class="block text-right text-[4rem] leading-none font-black text-zinc-900 select-none -mt-4">"</span>
                </div>
            </div>

            {{-- MISSION --}}
            <div class="p-10 lg:p-14">
                <p class="text-xs font-bold uppercase tracking-[0.35em] text-red-500 mb-8">— Mission</p>
                <ul class="space-y-0">
                    <li class="flex gap-6 py-6 border-b border-zinc-800 group">
                        <div class="flex flex-col items-center">
                            <span class="text-red-600 font-black text-lg leading-none">01</span>
                            <div class="w-px flex-1 bg-zinc-800 mt-2 group-last:hidden"></div>
                        </div>
                        <p class="text-zinc-300 text-sm leading-relaxed pt-0.5 group-hover:text-white transition-colors">Menyediakan program pelatihan <strong class="text-white">sistematis dan personal</strong> sesuai kebutuhan masing-masing individu.</p>
                    </li>
                    <li class="flex gap-6 py-6 border-b border-zinc-800 group">
                        <div class="flex flex-col items-center">
                            <span class="text-red-600 font-black text-lg leading-none">02</span>
                            <div class="w-px flex-1 bg-zinc-800 mt-2"></div>
                        </div>
                        <p class="text-zinc-300 text-sm leading-relaxed pt-0.5 group-hover:text-white transition-colors">Menerapkan metode latihan <strong class="text-white">modern berbasis sport science</strong> untuk mencapai performa fisik puncak.</p>
                    </li>
                    <li class="flex gap-6 py-6 group">
                        <div class="flex flex-col items-center">
                            <span class="text-red-600 font-black text-lg leading-none">03</span>
                        </div>
                        <p class="text-zinc-300 text-sm leading-relaxed pt-0.5 group-hover:text-white transition-colors">Membentuk <strong class="text-white">karakter dan mentalitas</strong> yang kuat, siap menghadapi tantangan seleksi dengan percaya diri.</p>
                    </li>
                </ul>
            </div>

        </div>
    </div>
</section>

{{-- ABOUT --}}
<section id="about" class="py-16 lg:py-24 bg-gray-50 text-gray-800">
    <div class="container mx-auto px-6">
        <div class="max-w-4xl mb-12">
            <h2 class="text-sm uppercase tracking-[0.3em] text-red-800 font-bold mb-2">Meet Your Coach</h2>
            <h3 class="text-4xl md:text-5xl font-extrabold text-gray-900 mb-6 leading-tight">
                FARIZ FAHRUN, S.Or. <br>
                <span class="text-red-800 text-2xl md:text-3xl">S&C Coach</span>
            </h3>
            <p class="text-lg text-gray-600 leading-relaxed">Lulusan <strong class="text-gray-900">Ilmu Keolahragaan</strong> dengan fokus pada Kepelatihan Olahraga. Kami menerapkan prinsip dasar <strong class="text-gray-900">Sport Science</strong> untuk membangun fisik yang kuat dan mental yang tangguh.</p>
        </div>
        <div class="grid md:grid-cols-2 gap-12 items-start">
            <div class="relative group">
                <div class="absolute -inset-1 bg-red-800 rounded-2xl blur opacity-20 group-hover:opacity-40 transition duration-1000"></div>
                <img src="{{ asset('pict/about.png') }}" alt="Coach Fariz" class="relative rounded-2xl shadow-2xl w-full h-[500px] object-cover border-b-8 border-red-800" />
                <div class="absolute top-4 right-4 bg-black bg-opacity-70 text-white px-4 py-2 rounded-lg backdrop-blur-md border border-red-800">
                    <p class="text-xs uppercase tracking-tighter font-bold text-red-500">S&C Coach</p>
                </div>
                <div class="mt-8">
                    <a href="https://wa.me/6285603875675" class="inline-flex items-center gap-2 bg-red-800 hover:bg-red-950 text-white font-bold py-4 px-8 rounded-lg transition-all transform hover:-translate-y-1 w-full justify-center md:w-auto">
                        KONSULTASI DENGAN COACH
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"></path></svg>
                    </a>
                </div>
            </div>
            <div class="space-y-6">
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50"><tr><th class="px-4 py-2 border-b text-red-800 text-[10px] uppercase font-bold"><i class="fa-solid fa-certificate mr-2"></i>Lisensi & Sertifikasi</th><th class="px-4 py-2 border-b text-right text-[10px] uppercase font-bold text-gray-400">Lembaga</th></tr></thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <tr><td class="px-4 py-3 font-semibold text-gray-900 text-xs">Pelatih Fisik Level 2 Nasional</td><td class="px-4 py-3 text-right text-gray-500 italic text-xs">ICCA</td></tr>
                            <tr><td class="px-4 py-3 font-semibold text-gray-900 text-xs">S.Or (Sarjana Olahraga)</td><td class="px-4 py-3 text-right text-gray-500 italic text-xs">UNJ</td></tr>
                        </tbody>
                    </table>
                </div>
                <div class="overflow-hidden rounded-xl border border-gray-200 bg-white shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50"><tr><th class="px-4 py-2 border-b text-red-800 text-[10px] uppercase font-bold"><i class="fa-solid fa-dumbbell mr-2"></i>Pengalaman Melatih</th><th class="px-4 py-2 border-b text-right text-[10px] uppercase font-bold text-gray-400">Status</th></tr></thead>
                        <tbody class="text-sm divide-y divide-gray-100">
                            <tr><td class="px-4 py-3 font-semibold text-gray-900 text-xs">S&C PPLM Pencak Silat DKI Jakarta</td><td class="px-4 py-3 text-right text-gray-500 italic text-xs">2022 - Sekarang</td></tr>
                            <tr><td class="px-4 py-3 font-semibold text-gray-900 text-xs">Pelatih Binaan BAPOMI DKI Jakarta</td><td class="px-4 py-3 text-right text-gray-500 italic text-xs">2022</td></tr>
                            <tr><td class="px-4 py-3 font-semibold text-gray-900 text-xs">Binpres Klub Olahraga Prestasi UNJ</td><td class="px-4 py-3 text-right text-gray-500 italic text-xs">Active</td></tr>
                        </tbody>
                    </table>
                </div>
                {{-- PRESTASI UTAMA — nonaktif sementara, hapus tag komentar ini untuk munculkan lagi
                <div class="overflow-hidden rounded-xl border border-red-100 bg-red-50/30 shadow-sm">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-red-800 text-white"><tr><th class="px-4 py-2 border-b text-[10px] uppercase font-bold"><i class="fa-solid fa-trophy mr-2"></i>Prestasi Utama S&C</th><th class="px-4 py-2 border-b text-right text-[10px] uppercase font-bold">Tahun</th></tr></thead>
                        <tbody class="text-sm divide-y divide-red-100">
                            <tr><td class="px-4 py-3 font-bold text-gray-900 text-xs">Juara Umum 1 POMNAS XVIII Kalsel</td><td class="px-4 py-3 text-right text-gray-600 italic text-xs">2023</td></tr>
                            <tr><td class="px-4 py-3 font-bold text-gray-900 text-xs">Juara Umum 1 Invitasi Beladiri Mahasiswa</td><td class="px-4 py-3 text-right text-gray-600 italic text-xs">2024 - 2025</td></tr>
                            <tr><td class="px-4 py-3 font-bold text-gray-900 text-xs">Juara Umum 1 POMPROV</td><td class="px-4 py-3 text-right text-gray-600 italic text-xs">2023</td></tr>
                        </tbody>
                    </table>
                </div>
                --}}
            </div>
        </div>
    </div>
</section>

{{-- TRAINING --}}
<section id="training" class="py-20 lg:py-32 bg-black text-gray-100">
    <div class="container mx-auto px-6">
        <div class="mb-20">
            <h2 class="text-4xl md:text-5xl font-extrabold mb-4 tracking-tighter">PROGRAM <span class="text-red-800">PELATIHAN</span></h2>
            <div class="w-24 h-1 bg-red-800 mb-6"></div>
            <p class="text-gray-400 max-w-xl italic">Metode latihan sistematis berbasis Sport Science untuk mencapai performa fisik yang diinginkan.</p>
        </div>
        <div class="space-y-24">
            <div class="flex flex-col md:flex-row items-center gap-12 group">
                <div class="w-full md:w-1/2 overflow-hidden rounded-2xl aspect-video bg-gray-900">
                    <img src="{{ asset('pict/trainningkedinasan.png') }}" alt="Persiapan Kedinasan" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-105 transition duration-700" />
                </div>
                <div class="w-full md:w-1/2 space-y-4">
                    <span class="text-red-800 font-bold tracking-[0.2em] text-sm uppercase">Persiapan Kedinasan</span>
                    <h3 class="text-3xl font-bold">PERSIAPAN KEDINASAN</h3>
                    <p class="text-gray-400 leading-relaxed">Program komprehensif untuk calon prajurit TNI, POLRI, dan Instansi Kedinasan. Fokus pada standar tes samapta.</p>
                    <ul class="text-sm text-gray-500 space-y-2 pt-2">
                        <li>• Analisis Postur & Biomekanika Tubuh (APECS)</li>
                        <li>• Standardisasi Tes Samapta A & B</li>
                        <li>• Periodisasi Latihan Menjelang Seleksi</li>
                        <li>• Simulasi Penilaian Poin Maksimal</li>
                    </ul>
                    <div class="pt-5">
                        <a href="{{ route('program.kedinasan') }}"
                            class="inline-flex items-center gap-2 text-red-500 hover:text-white font-bold text-sm uppercase tracking-wider transition-all duration-300 group">
                            Lihat Program Kedinasan
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                    <div class="pt-5">
                        <a href="{{ route('kalkulator.polri') }}"
                            class="inline-flex items-center gap-2 text-red-500 hover:text-white font-bold text-sm uppercase tracking-wider transition-all duration-300 group">
                            <i class="fa-solid fa-calculator text-xs group-hover:scale-110 transition-transform"></i>
                            Hitung Nilai POLRI Kamu
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row-reverse items-center gap-12 group">
                <div class="w-full md:w-1/2 overflow-hidden rounded-2xl aspect-video bg-gray-900">
                    <img src="{{ asset('pict/trainningkebugaran.png') }}" alt="Kebugaran Umum" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-105 transition duration-700" />
                </div>
                <div class="w-full md:w-1/2 space-y-4 md:text-right">
                    <span class="text-red-800 font-bold tracking-[0.2em] text-sm uppercase">Modern Lifestyle</span>
                    <h3 class="text-3xl font-bold">KEBUGARAN UMUM & S&C</h3>
                    <p class="text-gray-400 leading-relaxed">Ditujukan bagi individu yang ingin meningkatkan kualitas hidup melalui transformasi fisik.</p>
                    <ul class="text-sm text-gray-500 space-y-2 pt-2">
                        <li>• Analisis Postur & Biomekanika Tubuh (APECS)</li>
                        <li>• Weight Management & Body Shaping</li>
                        <li>• Functional Strength Training</li>
                        <li>• Program Latihan Berbasis Sport Science</li>
                    </ul>
                    <div class="pt-5">
                        <a href="{{ route('program.kebugaran') }}"
                            class="inline-flex items-center gap-2 text-red-500 hover:text-white font-bold text-sm uppercase tracking-wider transition-all duration-300 group">
                            Lihat Program Kebugaran
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
            <div class="flex flex-col md:flex-row items-center gap-12 group">
                <div class="w-full md:w-1/2 overflow-hidden rounded-2xl aspect-video bg-gray-900">
                    <img src="{{ asset('pict/trainningmentalitas.png') }}" alt="Mentalitas Juara" class="w-full h-full object-cover opacity-60 group-hover:opacity-100 group-hover:scale-105 transition duration-700" />
                </div>
                <div class="w-full md:w-1/2 space-y-4">
                    <span class="text-red-800 font-bold tracking-[0.2em] text-sm uppercase">Recovery & Rehabilitation</span>
                    <h3 class="text-3xl font-bold uppercase">PEMULIHAN OPTIMAL</h3>
                    <p class="text-gray-400 leading-relaxed">Program khusus yang dirancang untuk mengembalikan performa terbaik Anda pasca cedera.</p>
                    <ul class="text-sm text-gray-500 space-y-2 pt-2">
                        <li>• Latihan Penguatan Otot Pendukung (Stability & Mobility)</li>
                        <li>• Program Kembali ke Olahraga (Return to Sport Protocol)</li>
                    </ul>
                    <div class="pt-5">
                        <a href="{{ route('program.kebugaran') }}"
                            class="inline-flex items-center gap-2 text-red-500 hover:text-white font-bold text-sm uppercase tracking-wider transition-all duration-300 group">
                            Lihat Program Pemulihan
                            <i class="fa-solid fa-arrow-right text-xs group-hover:translate-x-1 transition-transform"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- METHOD --}}
<section id="method" class="bg-zinc-950 text-white flex flex-col lg:flex-row min-h-screen">

    {{-- KIRI: teks + accordion + CTA --}}
    <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 md:px-14 lg:px-16 py-20 lg:py-24">

        <p class="text-xs font-bold uppercase tracking-[0.35em] text-red-500 mb-4">Our Scientific Approach</p>
        <h3 class="text-4xl md:text-5xl font-extrabold tracking-tighter mb-3 leading-tight">
            METODE <span class="text-red-500">STAR JASMANI</span>
        </h3>
        <p class="text-zinc-400 text-base mb-10 leading-relaxed max-w-md">
            "Perencanaan latihan dilakukan agar proses latihan memiliki arah yang jelas untuk mencapai tujuan — berbasis data, bukan asumsi."
        </p>

        {{-- Accordion --}}
        <div class="border-l-2 border-zinc-700 mb-10">

            {{-- Item 01 --}}
            <div class="relative pl-8 pb-2" id="method-item-0">
                <div class="method-dot-0 absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-red-600 transition-all duration-300"></div>
                <button onclick="toggleMethod(0)" class="w-full text-left flex items-center justify-between py-3 pr-2 group">
                    <h4 class="text-lg font-bold text-white uppercase tracking-wide group-hover:text-red-500 transition-colors">01. Analisis Latihan</h4>
                    <span id="method-icon-0" class="text-red-500 font-black text-2xl leading-none">+</span>
                </button>
                <div id="method-content-0" class="overflow-hidden transition-all duration-500 ease-in-out max-h-96 pb-6">
                    <p class="text-zinc-400 text-sm mb-3">Sebelum program dimulai, kami lakukan pemetaan kondisi fisik secara menyeluruh — bukan tebakan, tapi data nyata.</p>
                    <ul class="space-y-2 text-zinc-300 text-sm p-4 bg-zinc-900 rounded-lg">
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Pengukuran BMI & Komposisi Tubuh:</strong> Baseline fisik yang akurat sebagai fondasi program tepat sasaran.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Analisis Postur (APECS):</strong> Identifikasi ketidakseimbangan otot & risiko cedera sebelum beban latihan diberikan.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Asesmen Kemampuan Fisik Awal:</strong> Titik tolak untuk merancang program yang realistis dan progresif.</span></li>
                    </ul>
                </div>
            </div>

            {{-- Item 02 --}}
            <div class="relative pl-8 pb-2" id="method-item-1">
                <div class="method-dot-1 absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-zinc-600 transition-all duration-300"></div>
                <button onclick="toggleMethod(1)" class="w-full text-left flex items-center justify-between py-3 pr-2 group">
                    <h4 class="text-lg font-bold text-white uppercase tracking-wide group-hover:text-red-500 transition-colors">02. Proses Latihan</h4>
                    <span id="method-icon-1" class="text-zinc-500 font-black text-2xl leading-none">+</span>
                </button>
                <div id="method-content-1" class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 pb-0">
                    <p class="text-zinc-400 text-sm mb-3">Periodisasi individual — bukan program generik, tapi rancangan khusus yang menyesuaikan kondisi, target, dan waktu seleksi.</p>
                    <ul class="space-y-2 text-zinc-300 text-sm p-4 bg-zinc-900 rounded-lg mb-3">
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Strength & Conditioning:</strong> Kekuatan fungsional progresif, siap menanggung beban intensitas tinggi tanpa cedera.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Endurance & Cardio:</strong> Kapasitas aerobik & daya tahan lari — penentu utama kelulusan tes samapta.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Speed & Agility:</strong> Kecepatan dan kelincahan untuk shuttle run dan tes lapangan lainnya.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Renang:</strong> Teknik dan stamina akuatik sesuai standar tes kedinasan.</span></li>
                    </ul>
                    <div class="flex flex-wrap gap-2 pb-4">
                        <span class="px-3 py-1 bg-zinc-800 border border-zinc-700 text-zinc-300 text-xs font-bold rounded-full uppercase">Strength</span>
                        <span class="px-3 py-1 bg-zinc-800 border border-zinc-700 text-zinc-300 text-xs font-bold rounded-full uppercase">Endurance</span>
                        <span class="px-3 py-1 bg-zinc-800 border border-zinc-700 text-zinc-300 text-xs font-bold rounded-full uppercase">Speed</span>
                        <span class="px-3 py-1 bg-zinc-800 border border-zinc-700 text-zinc-300 text-xs font-bold rounded-full uppercase">Renang</span>
                    </div>
                </div>
            </div>

            {{-- Item 03 --}}
            <div class="relative pl-8 pb-2" id="method-item-2">
                <div class="method-dot-2 absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-zinc-600 transition-all duration-300"></div>
                <button onclick="toggleMethod(2)" class="w-full text-left flex items-center justify-between py-3 pr-2 group">
                    <h4 class="text-lg font-bold text-white uppercase tracking-wide group-hover:text-red-500 transition-colors">03. Hasil Terukur</h4>
                    <span id="method-icon-2" class="text-zinc-500 font-black text-2xl leading-none">+</span>
                </button>
                <div id="method-content-2" class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 pb-0">
                    <p class="text-zinc-400 text-sm mb-3">Latihan tanpa evaluasi adalah latihan tanpa arah. Di Star Jasmani, setiap progres terdokumentasi secara ilmiah.</p>
                    <ul class="space-y-2 text-zinc-300 text-sm p-4 bg-zinc-900 rounded-lg pb-6">
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Laporan Perkembangan Berkala:</strong> Data skor samapta yang bisa dipantau langsung — angka nyata, bukan asumsi.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Analisis per Komponen:</strong> Tahu persis di mana harus fokus dari setiap elemen tes fisik.</span></li>
                        <li class="flex items-start gap-2"><span class="text-red-500 font-bold shrink-0">•</span><span><strong class="text-white">Rekomendasi Lanjutan:</strong> Program selalu disesuaikan dengan kondisi fisik terkini.</span></li>
                    </ul>
                </div>
            </div>

        </div>

        {{-- 4 Pillars --}}
        <div class="grid grid-cols-4 gap-4 mb-10 border-t border-b border-zinc-800 py-6">
            <div class="text-center">
                <i class="fa-solid fa-crosshairs text-red-500 text-xl mb-2"></i>
                <p class="text-white font-bold text-xs uppercase tracking-wider">Focus</p>
                <p class="text-zinc-500 text-xs mt-0.5">Train Your Mind</p>
            </div>
            <div class="text-center">
                <i class="fa-solid fa-shield-halved text-red-500 text-xl mb-2"></i>
                <p class="text-white font-bold text-xs uppercase tracking-wider">Discipline</p>
                <p class="text-zinc-500 text-xs mt-0.5">Build Your Habit</p>
            </div>
            <div class="text-center">
                <i class="fa-solid fa-person-running text-red-500 text-xl mb-2"></i>
                <p class="text-white font-bold text-xs uppercase tracking-wider">Resilience</p>
                <p class="text-zinc-500 text-xs mt-0.5">Overcome Limits</p>
            </div>
            <div class="text-center">
                <i class="fa-solid fa-star text-red-500 text-xl mb-2"></i>
                <p class="text-white font-bold text-xs uppercase tracking-wider">Leadership</p>
                <p class="text-zinc-500 text-xs mt-0.5">Inspire Others</p>
            </div>
        </div>

        {{-- CTA Buttons --}}
        <div class="flex flex-col sm:flex-row gap-3">
            <a href="{{ route('daftar') }}"
                class="inline-flex items-center justify-center gap-2 bg-red-700 hover:bg-red-600 text-white font-bold uppercase tracking-widest text-xs py-4 px-8 transition-all duration-300">
                DAFTAR SEKARANG
            </a>
            <a href="https://wa.me/6285603875675" target="_blank"
                class="inline-flex items-center justify-center gap-2 border-2 border-white hover:bg-white hover:text-black text-white font-bold uppercase tracking-widest text-xs py-4 px-8 transition-all duration-300">
                KONSULTASI GRATIS
            </a>
        </div>

    </div>

    {{-- KANAN: gambar edge-to-edge --}}
    <div class="w-full lg:w-1/2 h-72 sm:h-96 lg:h-auto relative">
        <img src="{{ asset('pict/method-visual.png') }}" alt="Star Jasmani Method"
            class="absolute inset-0 w-full h-full object-cover" />
        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-8">
            <p class="text-red-400 font-bold text-xs uppercase tracking-widest mb-2">Target Achievement</p>
            <h5 class="text-2xl font-bold text-white leading-tight">Membentuk Fisik &<br>Mental Juara</h5>
        </div>
    </div>

</section>

{{-- PORTAL STAR PERFORMANCE --}}
@php($portalPerformance = config('portal.performance_url'))
<section id="portal" class="py-20 lg:py-28 bg-black text-gray-100 border-t border-zinc-900">
    <div class="container mx-auto px-6">
        <div class="relative overflow-hidden rounded-2xl border border-zinc-800 bg-zinc-950">
            <div class="absolute -top-24 -right-24 w-72 h-72 rounded-full bg-red-900/20 blur-3xl"></div>
            <div class="relative flex flex-col lg:flex-row items-center gap-10 p-8 lg:p-14">

                <div class="w-full lg:w-2/3 space-y-5">
                    <span class="inline-flex items-center gap-2 text-red-500 font-bold tracking-[0.2em] text-xs uppercase">
                        <i class="fa-solid fa-chart-line"></i> Portal Pelatih
                    </span>
                    <h2 class="text-4xl md:text-5xl font-extrabold tracking-tighter">STAR <span class="text-red-800">PERFORMANCE</span></h2>
                    <div class="w-24 h-1 bg-red-800"></div>
                    <p class="text-gray-400 leading-relaxed max-w-2xl">
                        Sistem monitoring dan evaluasi kondisi fisik atlet lintas cabang olahraga. Menghitung
                        Performance % terhadap benchmark, meringkasnya menjadi skor berbobot, lalu memeringkat atlet.
                    </p>
                    <ul class="grid sm:grid-cols-2 gap-x-8 gap-y-2 text-sm text-gray-500 pt-2">
                        <li>&bull; Tes Biomotor &amp; Antropometri</li>
                        <li>&bull; Bleep Test, RAST, dan 1RM</li>
                        <li>&bull; Readiness Harian Atlet</li>
                        <li>&bull; Peringkat &amp; Laporan Sesi</li>
                    </ul>
                    <p class="text-xs text-zinc-600 pt-2">
                        <i class="fa-solid fa-circle-info mr-1"></i>
                        Akun Star Performance terpisah dari akun Star Jasmani.
                    </p>
                </div>

                <div class="w-full lg:w-1/3 flex flex-col gap-3 lg:items-end">
                    <a href="{{ route('program.atlet') }}"
                        class="w-full lg:w-auto inline-flex items-center justify-center gap-3 bg-red-800 hover:bg-red-950 text-white font-bold uppercase tracking-widest text-xs py-4 px-10 rounded-full transition-all duration-300 transform hover:scale-105">
                        Pelajari Selengkapnya
                        <i class="fa-solid fa-arrow-right text-xs"></i>
                    </a>
                    @if ($portalPerformance)
                        <a href="{{ $portalPerformance }}" target="_blank" rel="noopener"
                            class="w-full lg:w-auto inline-flex items-center justify-center gap-3 border-2 border-zinc-700 hover:border-red-800 text-white font-bold uppercase tracking-widest text-xs py-4 px-10 rounded-full transition-all duration-300">
                            Masuk Portal
                            <i class="fa-solid fa-arrow-up-right-from-square text-xs"></i>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>
</section>

@include('layouts.partials.public-footer')

@endsection

@push('scripts')
<script>

    // ── Method Accordion ──
    const methodState = [true, false, false]; // item 0 terbuka by default

    function toggleMethod(index) {
        methodState[index] = !methodState[index];

        for (let i = 0; i < 3; i++) {
            const content = document.getElementById('method-content-' + i);
            const icon    = document.getElementById('method-icon-' + i);
            const dot     = document.querySelector('.method-dot-' + i);

            if (methodState[i]) {
                content.style.maxHeight = content.scrollHeight + 'px';
                content.style.paddingBottom = '2rem';
                icon.textContent = '−';
                icon.classList.remove('text-zinc-500');
                icon.classList.add('text-red-500');
                dot.classList.remove('bg-zinc-600');
                dot.classList.add('bg-red-600');
            } else {
                content.style.maxHeight = '0';
                content.style.paddingBottom = '0';
                icon.textContent = '+';
                icon.classList.remove('text-red-500');
                icon.classList.add('text-zinc-500');
                dot.classList.remove('bg-red-600');
                dot.classList.add('bg-zinc-600');
            }
        }
    }
</script>
@endpush
