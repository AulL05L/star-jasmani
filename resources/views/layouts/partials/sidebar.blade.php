{{-- Sidebar Desktop --}}
<aside class="hidden lg:flex flex-col fixed left-0 top-0 h-full w-64 bg-gray-950 border-r border-gray-800 z-40">

    {{-- Logo --}}
    <div class="p-6 border-b border-gray-800">
        <a href="{{ auth()->user()->isAdmin() ? route('admin.dashboard') : route('member.dashboard') }}" class="flex items-center gap-3">
            <div class="w-9 h-9 overflow-hidden rounded-lg border border-red-800">
                <img src="{{ asset('pict/logo-removebg.png') }}" alt="logo" class="w-full h-full object-cover" />
            </div>
            <span class="text-white font-black tracking-tighter text-lg">
                STAR <span class="text-red-800">JASMANI</span>
            </span>
        </a>
    </div>

    {{-- User Info --}}
    <div class="p-4 border-b border-gray-800">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-sm flex-shrink-0">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>
            <div class="min-w-0">
                <p class="text-white font-bold text-sm truncate">{{ auth()->user()->name }}</p>
                <p class="text-gray-500 text-xs uppercase tracking-widest">{{ auth()->user()->role }}</p>
            </div>
        </div>
    </div>

    {{-- Navigation --}}
    <nav class="flex-1 p-4 space-y-1 overflow-y-auto">

        @if(auth()->user()->isAdmin())
            <p class="text-gray-700 text-[10px] uppercase tracking-widest font-bold px-3 py-2">Main</p>

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.dashboard') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-house w-4 text-center"></i> Dashboard
            </a>

            <p class="text-gray-700 text-[10px] uppercase tracking-widest font-bold px-3 py-2 mt-4">Penilaian</p>

            <a href="{{ route('admin.samapta.create') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.samapta.create') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-plus w-4 text-center"></i> Input Nilai
            </a>

            <a href="{{ route('admin.samapta.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.samapta.index') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-list w-4 text-center"></i> Semua Nilai
            </a>

            <a href="{{ route('admin.benchmark') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.benchmark') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-table-list w-4 text-center"></i> Benchmark Nilai
            </a>

            <p class="text-gray-700 text-[10px] uppercase tracking-widest font-bold px-3 py-2 mt-4">Peserta</p>

            <a href="{{ route('admin.athletes.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.athletes.*') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-users w-4 text-center"></i> Data Peserta
            </a>

            <a href="{{ route('admin.athletes.create') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.athletes.create') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-user-plus w-4 text-center"></i> Tambah Peserta
            </a>

            <a href="{{ route('admin.athletes.import') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.athletes.import*') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-file-arrow-up w-4 text-center"></i> Import Atlet
            </a>

            <p class="text-gray-700 text-[10px] uppercase tracking-widest font-bold px-3 py-2 mt-4">Pengaturan</p>

            <a href="{{ route('admin.institutions.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('admin.institutions.*') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-building w-4 text-center"></i> Instansi & Bobot
            </a>

        @else
            <p class="text-gray-700 text-[10px] uppercase tracking-widest font-bold px-3 py-2">Menu</p>

            <a href="{{ route('member.dashboard') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold transition-all
                {{ request()->routeIs('member.dashboard') ? 'bg-red-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }}">
                <i class="fa-solid fa-house w-4 text-center"></i> Dashboard
            </a>
        @endif
    </nav>

    {{-- Logout --}}
    <div class="p-4 border-t border-gray-800">
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit"
                class="w-full flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-bold text-gray-400 hover:text-white hover:bg-red-900/30 transition-all">
                <i class="fa-solid fa-right-from-bracket w-4 text-center"></i> Logout
            </button>
        </form>
    </div>
</aside>