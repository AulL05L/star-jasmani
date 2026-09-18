{{--
    Header untuk semua halaman publik.

    Menu menunjuk ke route absolut (bukan anchor "#") supaya tetap benar ketika
    dipakai dari halaman selain beranda. Setiap halaman segmen wajib muncul di
    sini: tautan internal dari seluruh situs adalah cara Google memahami bahwa
    keempat halaman itu sama pentingnya.
--}}
@php
    $navItems = [
        ['label' => 'Beranda',        'url' => route('home')],
        ['label' => 'Kedinasan',      'url' => route('program.kedinasan')],
        ['label' => 'Kebugaran',      'url' => route('program.kebugaran')],
        ['label' => 'Atlet & Pelatih','url' => route('program.atlet')],
    ];
    $navActive = fn (string $url) => url()->current() === $url;
@endphp

<header class="sticky top-0 z-50 bg-black/90 backdrop-blur-md p-4 lg:p-3 flex items-center justify-between border-b border-gray-900">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="w-10 h-10 overflow-hidden rounded-lg border border-red-800">
            <img src="{{ asset('pict/logo-removebg.png') }}" alt="Logo Star Jasmani" class="w-full h-full object-cover" />
        </div>
        <span class="text-white font-black tracking-tighter text-xl hidden sm:block">
            STAR <span class="text-red-800">JASMANI</span>
        </span>
    </a>

    <div class="cursor-pointer text-red-800 lg:hidden text-2xl" id="hamburger">
        <i class="fa-solid fa-bars"></i>
    </div>

    <nav class="hidden lg:block">
        <ul class="flex space-x-1 items-center">
            @foreach($navItems as $item)
                <li>
                    <a href="{{ $item['url'] }}"
                        class="relative px-4 py-2 font-bold {{ $navActive($item['url']) ? 'text-white' : 'text-gray-400' }} hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">
                        {{ $item['label'] }}
                        <span class="absolute left-1/2 bottom-0 h-0.5 bg-red-800 transition-all duration-300 {{ $navActive($item['url']) ? 'w-1/2 left-1/4' : 'w-0 group-hover:w-1/2 group-hover:left-1/4' }}"></span>
                    </a>
                </li>
            @endforeach
            <li>
                <a href="{{ route('kalkulator.polri') }}"
                    class="relative px-4 py-2 font-bold text-red-400 hover:text-white uppercase tracking-widest transition-all duration-300 text-xs group">
                    Kalkulator
                    <span class="absolute left-1/2 bottom-0 w-0 h-0.5 bg-red-800 transition-all duration-300 group-hover:w-1/2 group-hover:left-1/4"></span>
                </a>
            </li>
            <li>
                <a href="{{ route('login') }}"
                    class="ml-2 inline-block bg-red-800 hover:bg-red-950 text-white font-bold uppercase tracking-widest text-xs px-5 py-2.5 rounded-full transition-all duration-300">
                    Login
                </a>
            </li>
        </ul>
    </nav>
</header>

{{-- MOBILE MENU --}}
<nav class="bg-black/95 border-b border-gray-900 p-6 hidden fixed w-full z-40 top-0 mt-16" id="mobile-menu">
    <ul class="flex flex-col space-y-6 text-center">
        @foreach($navItems as $item)
            <li><a href="{{ $item['url'] }}" class="mobile-link font-bold text-gray-400 hover:text-red-800 uppercase tracking-widest text-sm block">{{ $item['label'] }}</a></li>
        @endforeach
        <li><a href="{{ route('kalkulator.polri') }}" class="mobile-link font-bold text-red-400 hover:text-red-600 uppercase tracking-widest text-sm block"><i class="fa-solid fa-calculator mr-1 text-xs"></i> Kalkulator</a></li>
        <li class="pt-2 border-t border-gray-800">
            <a href="{{ route('login') }}" class="mobile-link inline-block bg-red-800 hover:bg-red-950 text-white font-bold uppercase tracking-widest text-sm px-8 py-3 rounded-full transition-all duration-300">
                <i class="fa-solid fa-shield-halved mr-2"></i> Login Member / Coach
            </a>
        </li>
    </ul>
</nav>

@once
@push('scripts')
<script>
    (function () {
        const hamburger  = document.getElementById("hamburger");
        const mobileMenu = document.getElementById("mobile-menu");
        if (!hamburger || !mobileMenu) return;
        hamburger.addEventListener("click", () => mobileMenu.classList.toggle("hidden"));
        document.querySelectorAll(".mobile-link").forEach(link =>
            link.addEventListener("click", () => mobileMenu.classList.add("hidden")));
    })();
</script>
@endpush
@endonce
