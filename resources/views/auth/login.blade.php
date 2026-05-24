@extends('layouts.app')

@section('title', 'Login — Star Jasmani')

@push('styles')
<style>
    body::before {
        content: '';
        position: fixed;
        inset: 0;
        background-image: repeating-linear-gradient(
            0deg, transparent, transparent 2px,
            rgba(255,255,255,0.015) 2px, rgba(255,255,255,0.015) 4px
        );
        pointer-events: none;
        z-index: 0;
    }
    .glow-red {
        position: fixed;
        width: 500px;
        height: 500px;
        background: radial-gradient(circle, rgba(153,27,27,0.3) 0%, transparent 70%);
        border-radius: 50%;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        pointer-events: none;
        z-index: 0;
    }
    @keyframes slideUp {
        from { opacity: 0; transform: translateY(32px); }
        to   { opacity: 1; transform: translateY(0); }
    }
    .card-animate { animation: slideUp 0.5s cubic-bezier(0.22,1,0.36,1) both; }
    .role-tab.active { background-color: #991b1b; color: #fff; border-color: #991b1b; }
    .role-tab { transition: all 0.25s ease; }
    .input-field:focus {
        outline: none;
        border-color: #991b1b;
        box-shadow: 0 0 0 3px rgba(153,27,27,0.25);
    }
    @keyframes shimmer {
        0%   { background-position: -200% 0; }
        100% { background-position:  200% 0; }
    }
    .shimmer-text {
        background: linear-gradient(90deg, #9ca3af 0%, #ffffff 50%, #9ca3af 100%);
        background-size: 200% auto;
        -webkit-background-clip: text;
        background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: shimmer 3s infinite linear;
        display: inline-block;
    }
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        20%, 60% { transform: translateX(-6px); }
        40%, 80% { transform: translateX(6px); }
    }
    .shake { animation: shake 0.4s ease; }
</style>
@endpush

@section('content')
<div class="glow-red"></div>

{{-- HEADER MINI --}}
<header class="relative z-10 p-5 flex items-center justify-between border-b border-gray-900">
    <a href="{{ route('home') }}" class="flex items-center gap-3">
        <div class="w-9 h-9 overflow-hidden rounded-lg border border-red-800">
            <img src="{{ asset('pict/logo-removebg.png') }}" alt="logo" class="w-full h-full object-cover" />
        </div>
        <span class="text-white font-black tracking-tighter text-lg">
            STAR <span class="text-red-800">JASMANI</span>
        </span>
    </a>
    <a href="{{ route('home') }}" class="text-gray-500 hover:text-white text-xs uppercase tracking-widest font-bold transition-colors flex items-center gap-2">
        <i class="fa-solid fa-arrow-left text-[10px]"></i> Kembali
    </a>
</header>

{{-- MAIN --}}
<main class="relative z-10 flex items-center justify-center px-4 py-12 min-h-[calc(100vh-80px)]">
    <div class="card-animate w-full max-w-md">

        {{-- Brand --}}
        <div class="text-center mb-8">
            <p class="text-[10px] uppercase tracking-[0.4em] text-gray-600 mb-2">Portal Akses</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                <span class="shimmer-text">STAR JASMANI</span>
            </h1>
            <p class="text-gray-500 text-sm mt-2">Digital Assessment System</p>
        </div>

        {{-- Card --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-8 shadow-2xl">

            {{-- Role Tabs --}}
            <div class="flex gap-2 mb-8 p-1 bg-black rounded-xl border border-gray-900">
                <button id="tab-coach" onclick="switchRole('coach')"
                    class="role-tab active flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg border border-transparent text-sm font-bold uppercase tracking-wider">
                    <i class="fa-solid fa-shield-halved text-xs"></i> Coach
                </button>
                <button id="tab-member" onclick="switchRole('member')"
                    class="role-tab flex-1 flex items-center justify-center gap-2 py-2.5 px-4 rounded-lg border border-transparent text-sm font-bold uppercase tracking-wider text-gray-500 hover:text-gray-300">
                    <i class="fa-solid fa-user text-xs"></i> Member
                </button>
            </div>

            {{-- Role Label --}}
            <div class="mb-6 flex items-center gap-3">
                <div class="w-1 h-10 bg-red-800 rounded-full flex-shrink-0"></div>
                <div>
                    <p id="role-title" class="text-white font-bold text-sm">Masuk sebagai Coach / Admin</p>
                    <p id="role-desc" class="text-gray-500 text-xs mt-0.5">Akses penuh: input nilai, kelola peserta, ekspor laporan.</p>
                </div>
            </div>

            {{-- Error dari Laravel --}}
            @if($errors->any())
                <div class="shake mb-4 flex items-center gap-3 bg-red-950/60 border border-red-800/50 text-red-400 text-xs rounded-lg px-4 py-3">
                    <i class="fa-solid fa-circle-exclamation"></i>
                    <span>{{ $errors->first() }}</span>
                </div>
            @endif

            {{-- Form --}}
            <form action="{{ route('login.post') }}" method="POST" class="space-y-5">
                @csrf

                {{-- Email --}}
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600">
                            <i class="fa-solid fa-envelope text-xs"></i>
                        </span>
                        <input type="email" name="email" value="{{ old('email') }}" required
                            placeholder="Masukkan email"
                            class="input-field w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 pl-10 pr-4 text-sm transition-all duration-200" />
                    </div>
                </div>

                {{-- Password --}}
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Password</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-600">
                            <i class="fa-solid fa-lock text-xs"></i>
                        </span>
                        <input id="pw-input" type="password" name="password" required
                            placeholder="Masukkan password"
                            class="input-field w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 pl-10 pr-12 text-sm transition-all duration-200" />
                        <button type="button" onclick="togglePw()"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-600 hover:text-gray-400 transition-colors">
                            <i id="pw-icon" class="fa-solid fa-eye text-xs"></i>
                        </button>
                    </div>
                </div>

                {{-- Remember --}}
                <div class="flex items-center justify-between pt-1">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input type="checkbox" name="remember" class="w-4 h-4 accent-red-800" />
                        <span class="text-gray-500 text-xs">Ingat saya</span>
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                    class="w-full bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-4 rounded-xl transition-all duration-200 flex items-center justify-center gap-3 mt-2 shadow-lg shadow-red-900/30">
                    <i class="fa-solid fa-right-to-bracket"></i>
                    <span id="btn-text">Masuk sebagai Coach</span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-900"></div>
                <span class="text-gray-700 text-[10px] uppercase tracking-widest">atau</span>
                <div class="flex-1 h-px bg-gray-900"></div>
            </div>

            {{-- Back --}}
            <a href="{{ route('home') }}"
                class="w-full flex items-center justify-center gap-2 py-3 rounded-xl border border-gray-800 text-gray-500 hover:text-white hover:border-gray-600 text-xs font-bold uppercase tracking-wider transition-all duration-200">
                <i class="fa-solid fa-house text-[10px]"></i>
                Kembali ke Beranda
            </a>
        </div>

        <p class="text-center text-gray-700 text-[10px] mt-6 uppercase tracking-widest">
            &copy; 2024 Star Jasmani · Sistem Penilaian Jasmani Digital
        </p>
    </div>
</main>
@endsection

@push('scripts')
<script>
    const roleConfig = {
        coach:  { title: 'Masuk sebagai Coach / Admin', desc: 'Akses penuh: input nilai, kelola peserta, ekspor laporan.', btn: 'Masuk sebagai Coach' },
        member: { title: 'Masuk sebagai Member',        desc: 'Lihat progress latihan & unduh laporan fisik Anda.',      btn: 'Masuk sebagai Member' }
    };

    function switchRole(role) {
        const cfg = roleConfig[role];
        document.getElementById('role-title').textContent = cfg.title;
        document.getElementById('role-desc').textContent  = cfg.desc;
        document.getElementById('btn-text').textContent   = cfg.btn;

        ['coach','member'].forEach(r => {
            const tab = document.getElementById('tab-' + r);
            if (r === role) {
                tab.classList.add('active');
                tab.classList.remove('text-gray-500','hover:text-gray-300');
            } else {
                tab.classList.remove('active');
                tab.classList.add('text-gray-500','hover:text-gray-300');
            }
        });
    }

    function togglePw() {
        const input = document.getElementById('pw-input');
        const icon  = document.getElementById('pw-icon');
        if (input.type === 'password') {
            input.type = 'text';
            icon.className = 'fa-solid fa-eye-slash text-xs';
        } else {
            input.type = 'password';
            icon.className = 'fa-solid fa-eye text-xs';
        }
    }
</script>
@endpush