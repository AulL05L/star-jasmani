@extends('layouts.app')

@section('title', 'Star Jasmani — Training With Mentality')

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
        background-repeat: no-repeat;
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

{{-- HEADER --}}
<header class="sticky top-0 z-50 bg-black/90 backdrop-blur-md p-4 lg:p-3 flex items-center justify-between border-b border-gray-900">
    <div class="flex items-center gap-3">
        <div class="w-10 h-10 overflow-hidden rounded-lg border border-red-800">
            <img src="{{ asset('pict/logo-removebg.png') }}" alt="logo" class="w-full h-full object-cover" />
        </div>
        <span class="text-white font-black tracking-tighter text-xl hidden sm:block">
            STAR <span class="text-red-800">JASMANI</span>
        </span>
    </div>

    <div class="cursor-pointer text-red-800 lg:hidden text-2xl" id="hamburger">
        <i class="fa-solid fa-bars"></i>
    </div>

    <nav class="nav-items hidden lg:block">
        <ul class="flex space-x-2">
            <li><a href="#home" class="relative px-6 py-2 font-bold text-gray-400 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">Home<span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span></a></li>
            <li><a href="#profile" class="relative px-6 py-2 font-bold text-gray-400 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">Profile<span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span></a></li>
            <li><a href="#about" class="relative px-6 py-2 font-bold text-gray-400 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">About<span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span></a></li>
            <li><a href="#training" class="relative px-6 py-2 font-bold text-gray-400 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">Training<span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span></a></li>
            <li><a href="#method" class="relative px-6 py-2 font-bold text-gray-400 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">Our Method<span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span></a></li>
            <li><a href="{{ route('login') }}" class="relative px-6 py-2 font-bold text-red-500 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">Login<span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span></a></li>
        </ul>
    </nav>

    <a href="{{ route('login') }}" class="btn-login-pulse hidden lg:flex items-center gap-2 px-4 py-2 rounded-full border border-red-800 text-red-500 hover:bg-red-800 hover:text-white transition-all duration-300 text-xs font-bold uppercase tracking-wider">
        <i class="fa-solid fa-shield-halved text-sm"></i>
        <span>Member / Coach</span>
    </a>
</header>

{{-- MOBILE MENU --}}
<nav class="bg-black/95 border-b border-gray-900 p-6 hidden fixed w-full z-40 top-0 mt-16" id="mobile-menu">
    <ul class="flex flex-col space-y-6 text-center">
        <li><a href="#home" class="mobile-link font-bold text-gray-400 hover:text-red-800 uppercase tracking-widest text-sm block">Home</a></li>
        <li><a href="#profile" class="mobile-link font-bold text-gray-400 hover:text-red-800 uppercase tracking-widest text-sm block">Profile</a></li>
        <li><a href="#about" class="mobile-link font-bold text-gray-400 hover:text-red-800 uppercase tracking-widest text-sm block">About</a></li>
        <li><a href="#training" class="mobile-link font-bold text-gray-400 hover:text-red-800 uppercase tracking-widest text-sm block">Training</a></li>
        <li><a href="#method" class="mobile-link font-bold text-gray-400 hover:text-red-800 uppercase tracking-widest text-sm block">Our Method</a></li>
        <li class="pt-2 border-t border-gray-800">
            <a href="{{ route('login') }}" class="mobile-link inline-block bg-red-800 hover:bg-red-950 text-white font-bold uppercase tracking-widest text-sm px-8 py-3 rounded-full transition-all duration-300">
                <i class="fa-solid fa-shield-halved mr-2"></i> Login Member / Coach
            </a>
        </li>
    </ul>
</nav>

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
        </p>
        <div class="flex flex-col sm:flex-row gap-4 items-center justify-center pb-6">
            <a href="{{ route('daftar') }}" class="w-full sm:w-auto inline-block bg-red-800 hover:bg-red-950 text-white font-bold py-4 px-10 rounded-full transition duration-300 shadow-lg transform hover:scale-105">
                GABUNG SEKARANG!
            </a>
            <a href="{{ route('login') }}" class="w-full sm:w-auto inline-flex items-center justify-center gap-3 text-red-500 hover:text-white hover:bg-red-800 font-bold py-4 px-10 rounded-full border-2 border-red-800 transition duration-300">
                <i class="fa-solid fa-shield-halved"></i>
                LOGIN MEMBER / COACH
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-24 border-y border-gray-800 py-16">
            <div class="text-center"><h4 class="text-red-800 font-bold uppercase text-xs tracking-widest mb-4">Core Value 01</h4><p class="text-2xl font-bold uppercase italic">Scientific</p></div>
            <div class="text-center"><h4 class="text-red-800 font-bold uppercase text-xs tracking-widest mb-4">Core Value 02</h4><p class="text-2xl font-bold uppercase italic">Measured</p></div>
            <div class="text-center"><h4 class="text-red-800 font-bold uppercase text-xs tracking-widest mb-4">Core Value 03</h4><p class="text-2xl font-bold uppercase italic">Mentality</p></div>
        </div>
        <div class="grid md:grid-cols-2 gap-16">
            <div>
                <h4 class="text-gray-500 font-bold uppercase text-xs tracking-widest mb-6">Vision</h4>
                <p class="text-2xl font-semibold leading-snug">"Memberikan pelatihan tepat guna untuk hasil optimal dalam membentuk fisik dan mental, serta menjadikan olahraga sebagai investasi kesehatan."</p>
            </div>
            <div>
                <h4 class="text-gray-500 font-bold uppercase text-xs tracking-widest mb-6">Mission</h4>
                <ul class="space-y-6 text-gray-400">
                    <li class="flex items-baseline gap-4"><span class="text-red-800 font-black">/</span><p>Menyediakan program pelatihan sistematis sesuai kebutuhan individu.</p></li>
                    <li class="flex items-baseline gap-4"><span class="text-red-800 font-black">/</span><p>Menerapkan metode latihan modern untuk performa fisik puncak.</p></li>
                    <li class="flex items-baseline gap-4"><span class="text-red-800 font-black">/</span><p>Membentuk karakter dan mentalitas yang siap menghadapi seleksi.</p></li>
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
                </div>
            </div>
        </div>
    </div>
</section>

{{-- METHOD --}}
<section id="method" class="py-16 lg:py-24 bg-white text-gray-900">
    <div class="container mx-auto px-6">
        <div class="text-center mb-16">
            <h2 class="text-sm uppercase tracking-[0.4em] text-red-800 font-bold mb-3">Our Scientific Approach</h2>
            <h3 class="text-4xl md:text-5xl font-extrabold tracking-tighter">METODE <span class="text-red-800">STAR JASMANI</span></h3>
            <p class="mt-4 text-gray-500 max-w-2xl mx-auto italic">"Perencanaan latihan dilakukan agar proses latihan memiliki arah yang jelas untuk mencapai tujuan."</p>
        </div>
        <div class="grid md:grid-cols-2 gap-16 items-start">
            <div class="border-l-2 border-gray-200">

                {{-- Item 01 --}}
                <div class="relative pl-8 pb-2 group" id="method-item-0">
                    <div class="method-dot-0 absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-red-800 transition-all duration-300"></div>
                    <button onclick="toggleMethod(0)"
                        class="w-full text-left flex items-center justify-between py-3 pr-2 group">
                        <h4 class="text-xl font-bold text-gray-900 uppercase tracking-wide group-hover:text-red-800 transition-colors">01. Analisis Latihan</h4>
                        <span id="method-icon-0" class="text-red-800 font-black text-2xl leading-none transition-transform duration-300 rotate-0">+</span>
                    </button>
                    <div id="method-content-0" class="overflow-hidden transition-all duration-500 ease-in-out max-h-96 pb-8">
                        <p class="text-gray-500 text-sm mb-3">Sebelum program dimulai, kami lakukan pemetaan kondisi fisik secara menyeluruh — bukan tebakan, tapi data nyata.</p>
                        <ul class="space-y-3 text-gray-600 shadow-sm p-4 bg-gray-50 rounded-lg">
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Pengukuran BMI & Komposisi Tubuh:</strong> Memahami baseline fisik kamu secara akurat sebagai fondasi program latihan yang tepat sasaran.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Analisis Postur (APECS):</strong> Identifikasi ketidakseimbangan otot dan risiko cedera menggunakan aplikasi sport science berbasis teknologi, sebelum beban latihan diberikan.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Asesmen Kemampuan Fisik Awal:</strong> Mengukur kapasitas lari, kekuatan, dan daya tahan sebagai titik tolak untuk merancang program yang realistis dan progresif.</span></li>
                        </ul>
                    </div>
                </div>

                {{-- Item 02 --}}
                <div class="relative pl-8 pb-2 group" id="method-item-1">
                    <div class="method-dot-1 absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-gray-300 transition-all duration-300"></div>
                    <button onclick="toggleMethod(1)"
                        class="w-full text-left flex items-center justify-between py-3 pr-2 group">
                        <h4 class="text-xl font-bold text-gray-900 uppercase tracking-wide group-hover:text-red-800 transition-colors">02. Proses Latihan</h4>
                        <span id="method-icon-1" class="text-gray-400 font-black text-2xl leading-none transition-transform duration-300">+</span>
                    </button>
                    <div id="method-content-1" class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 pb-0">
                        <p class="text-gray-500 text-sm mb-3">Program latihan dirancang secara individual — bukan program generik, tapi periodisasi khusus yang menyesuaikan kondisi, target, dan waktu seleksi kamu.</p>
                        <ul class="space-y-3 text-gray-600 shadow-sm p-4 bg-gray-50 rounded-lg mb-3">
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Strength & Conditioning:</strong> Membangun kekuatan fungsional otot secara progresif agar tubuh siap menanggung beban latihan intensitas tinggi tanpa risiko cedera.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Endurance & Cardio:</strong> Meningkatkan kapasitas aerobik dan daya tahan lari — komponen utama tes samapta yang sering menjadi penentu kelulusan.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Speed & Agility:</strong> Melatih kecepatan gerak dan kelincahan untuk memaksimalkan performa shuttle run dan tes lapangan lainnya.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Renang:</strong> Teknik dan stamina renang untuk memenuhi standar tes kedinasan yang mensyaratkan kemampuan akuatik terukur.</span></li>
                        </ul>
                        <div class="flex flex-wrap gap-2 pb-4">
                            <span class="px-3 py-1 bg-black text-white text-xs font-bold rounded-full uppercase">Strength Training</span>
                            <span class="px-3 py-1 bg-black text-white text-xs font-bold rounded-full uppercase">Endurance</span>
                            <span class="px-3 py-1 bg-black text-white text-xs font-bold rounded-full uppercase">Speed</span>
                            <span class="px-3 py-1 bg-black text-white text-xs font-bold rounded-full uppercase">Renang</span>
                        </div>
                    </div>
                </div>

                {{-- Item 03 --}}
                <div class="relative pl-8 pb-2 group" id="method-item-2">
                    <div class="method-dot-2 absolute -left-[9px] top-0 w-4 h-4 rounded-full bg-gray-300 transition-all duration-300"></div>
                    <button onclick="toggleMethod(2)"
                        class="w-full text-left flex items-center justify-between py-3 pr-2 group">
                        <h4 class="text-xl font-bold text-gray-900 uppercase tracking-wide group-hover:text-red-800 transition-colors">03. Hasil Terukur</h4>
                        <span id="method-icon-2" class="text-gray-400 font-black text-2xl leading-none transition-transform duration-300">+</span>
                    </button>
                    <div id="method-content-2" class="overflow-hidden transition-all duration-500 ease-in-out max-h-0 pb-0">
                        <p class="text-gray-500 text-sm mb-3">Latihan tanpa evaluasi adalah latihan tanpa arah. Di Star Jasmani, setiap progres kamu terdokumentasi dan terukur secara ilmiah.</p>
                        <ul class="space-y-3 text-gray-600 shadow-sm p-4 bg-gray-50 rounded-lg pb-8">
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Laporan Perkembangan Fisik Berkala:</strong> Setiap sesi evaluasi menghasilkan data skor samapta yang bisa kamu pantau langsung — bukan asumsi, tapi angka nyata.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Analisis per Komponen:</strong> Lari, push-up, sit-up, pull-up, shuttle run, dan renang dievaluasi secara terpisah sehingga kamu tahu persis di mana harus fokus.</span></li>
                            <li class="flex items-start gap-2"><span class="text-red-800 font-bold">•</span><span><strong>Rekomendasi Program Lanjutan:</strong> Hasil evaluasi menjadi dasar penyesuaian program — sehingga setiap fase latihan selalu relevan dengan kondisi fisik kamu saat ini.</span></li>
                        </ul>
                    </div>
                </div>

            </div>
            <div class="sticky top-24">
                <div class="relative overflow-hidden rounded-3xl shadow-2xl bg-black aspect-[3/4]">
                    <img src="{{ asset('pict/method-visual.png') }}" alt="Star Jasmani Method" class="w-full h-full object-cover opacity-80" />
                    <div class="absolute bottom-0 left-0 right-0 p-8 bg-gradient-to-t from-black to-transparent">
                        <p class="text-red-500 font-bold text-sm uppercase tracking-widest mb-1">Target Achievement</p>
                        <h5 class="text-2xl font-bold text-white">Membentuk Fisik & Mental Juara</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- FOOTER --}}
<footer class="bg-black text-gray-400 py-12 border-t border-gray-900">
    <div class="container mx-auto px-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-12 mb-12">
            <div class="space-y-4">
                <h4 class="text-white font-black text-2xl tracking-tighter">STAR <span class="text-red-800">JASMANI</span></h4>
                <p class="text-sm leading-relaxed max-w-xs">Penyedia layanan program latihan jasmani profesional.</p>
            </div>
            <div class="space-y-4">
                <h5 class="text-white font-bold uppercase text-xs tracking-widest">Contact Info</h5>
                <ul class="text-sm space-y-2">
                    <li class="flex items-center gap-3"><i class="fa-solid fa-envelope text-red-800"></i><a href="mailto:starjasmani@gmail.com" class="hover:text-white transition">starjasmani@gmail.com</a></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-phone text-red-800"></i><a href="https://wa.me/6285603875675" class="hover:text-white transition">+62 856 0387 5675</a></li>
                    <li class="flex items-center gap-3"><i class="fa-solid fa-location-dot text-red-800"></i><span>Jakarta, Indonesia</span></li>
                </ul>
            </div>
            <div class="space-y-4 md:text-right">
                <h5 class="text-white font-bold uppercase text-xs tracking-widest">Connect With Us</h5>
                <div class="flex md:justify-end space-x-6">
                    <a href="https://www.instagram.com/star_jasmani/" target="_blank" class="text-gray-400 hover:text-red-800 transition text-xl"><i class="fa-brands fa-instagram"></i></a>
                    <a href="https://www.tiktok.com/@star.jasmani" target="_blank" class="text-gray-400 hover:text-red-800 transition text-xl"><i class="fa-brands fa-tiktok"></i></a>
                    <a href="https://wa.me/6285603875675" target="_blank" class="text-gray-400 hover:text-red-800 transition text-xl"><i class="fa-brands fa-whatsapp"></i></a>
                    <a href="mailto:starjasmani@gmail.com" class="text-gray-400 hover:text-red-800 transition text-xl"><i class="fa-solid fa-envelope"></i></a>
                </div>
            </div>
        </div>
        <div class="pt-8 border-t border-gray-900 text-center md:flex md:justify-between md:text-left items-center">
            <p class="text-xs tracking-wide">&copy; 2024 <span class="text-white font-bold">STAR JASMANI</span>. All Rights Reserved.</p>
            <p class="text-[10px] uppercase tracking-[0.2em] mt-4 md:mt-0 opacity-50">Professional S&C Coaching by Fariz Fahrun, S.Or.</p>
        </div>
    </div>
</footer>

@endsection

@push('scripts')
<script>
    const hamburger = document.getElementById("hamburger");
    const mobileMenu = document.getElementById("mobile-menu");
    const mobileLinks = document.querySelectorAll(".mobile-link");
    hamburger.addEventListener("click", () => mobileMenu.classList.toggle("hidden"));
    mobileLinks.forEach(link => link.addEventListener("click", () => mobileMenu.classList.add("hidden")));

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
                icon.classList.remove('text-gray-400');
                icon.classList.add('text-red-800');
                dot.classList.remove('bg-gray-300');
                dot.classList.add('bg-red-800');
            } else {
                content.style.maxHeight = '0';
                content.style.paddingBottom = '0';
                icon.textContent = '+';
                icon.classList.remove('text-red-800');
                icon.classList.add('text-gray-400');
                dot.classList.remove('bg-red-800');
                dot.classList.add('bg-gray-300');
            }
        }
    }
</script>
@endpush