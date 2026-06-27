@extends('layouts.app')
@section('title', 'Kalkulator Nilai POLRI Samapta — Star Jasmani')

@section('content')

{{-- HEADER --}}
<header class="sticky top-0 z-50 bg-black/90 backdrop-blur-md border-b border-gray-900 px-6 py-3.5 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 overflow-hidden rounded-lg border border-red-800 shrink-0">
            <img src="{{ asset('pict/logo-removebg.png') }}" alt="logo" class="w-full h-full object-cover" />
        </div>
        <div class="hidden sm:flex flex-col leading-none">
            <span class="text-white font-black tracking-tighter text-sm">STAR <span class="text-red-800">JASMANI</span></span>
            <span class="text-gray-600 text-[10px] uppercase tracking-widest">Kalkulator POLRI Samapta</span>
        </div>
    </div>
    <a href="{{ route('home') }}"
        class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
        <i class="fa-solid fa-arrow-left text-xs"></i>
        <span class="hidden sm:inline">Kembali ke Beranda</span>
        <span class="sm:hidden">Beranda</span>
    </a>
</header>

{{-- HERO TITLE --}}
<div class="bg-gradient-to-b from-gray-950 to-black border-b border-gray-900 py-10 md:py-14 px-4">
    <div class="max-w-6xl mx-auto text-center">
        <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[10px] mb-3">
            <i class="fa-solid fa-calculator mr-1.5"></i> Tools Gratis · Standar Resmi POLRI
        </p>
        <h1 class="text-3xl md:text-4xl lg:text-5xl font-extrabold tracking-tighter mb-3 leading-none">
            KALKULATOR NILAI <span class="text-red-800">SAMAPTA</span>
        </h1>
        <p class="text-gray-500 text-sm md:text-base max-w-md mx-auto leading-relaxed">
            Masukkan hasil tes fisik untuk menghitung nilai Samapta POLRI secara otomatis — gratis dan instan.
        </p>
    </div>
</div>

{{-- VALIDATION ERRORS --}}
@if($errors->any())
    <div class="max-w-6xl mx-auto px-4 md:px-6 lg:px-8 pt-5">
        <div class="bg-red-900/20 border border-red-800/60 rounded-2xl p-4">
            <p class="text-red-400 text-xs font-bold uppercase tracking-widest mb-2 flex items-center gap-2">
                <i class="fa-solid fa-circle-exclamation"></i> Periksa inputan berikut:
            </p>
            @foreach($errors->all() as $error)
                <p class="text-red-300 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    </div>
@endif

{{-- MAIN CONTENT --}}
<div class="max-w-6xl mx-auto px-4 py-8 md:px-6 lg:px-8 pb-20">
    <div class="lg:grid lg:grid-cols-5 lg:gap-8 lg:items-start">

        {{-- ══════════════════════════════════════════ --}}
        {{-- LEFT INFO PANEL — desktop only, sticky    --}}
        {{-- ══════════════════════════════════════════ --}}
        <aside class="hidden lg:flex lg:flex-col lg:col-span-2 gap-4 sticky top-[72px]">

            {{-- About --}}
            <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
                <h3 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-circle-info text-red-800 text-sm"></i> Tentang Kalkulator
                </h3>
                <p class="text-gray-400 text-sm leading-relaxed mb-4">
                    Menggunakan tabel konversi resmi POLRI Samapta B — 6 komponen tes, tabel skor terpisah untuk Pria dan Wanita.
                </p>
                <ul class="space-y-2">
                    @foreach(['Lari 12 Menit (Cooper Test)','Pull Up / Chin Up','Sit Up (60 dtk)','Push Up (60 dtk)','Shuttle Run 6×10m','Renang 50m'] as $i => $item)
                        <li class="flex items-center gap-3 text-xs text-gray-500">
                            <span class="w-5 h-5 flex items-center justify-center bg-red-900/30 text-red-700 font-bold rounded-full text-[10px] shrink-0">{{ $i + 1 }}</span>
                            {{ $item }}
                        </li>
                    @endforeach
                </ul>
            </div>

            {{-- Formula --}}
            <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
                <h3 class="text-white font-bold uppercase tracking-widest text-xs mb-5 flex items-center gap-2">
                    <i class="fa-solid fa-diagram-project text-red-800 text-sm"></i> Formula POLRI
                </h3>
                <div class="space-y-0 text-xs">
                    <div class="flex items-center justify-between py-2.5 border-b border-gray-800">
                        <span class="text-gray-500">Jasmani A</span>
                        <span class="text-gray-300 font-mono font-bold">= Nilai Lari</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-gray-800">
                        <span class="text-gray-500">Jasmani B</span>
                        <span class="text-gray-300 font-mono font-bold text-right">= avg(4 komponen)</span>
                    </div>
                    <div class="flex items-center justify-between py-2.5 border-b border-gray-800">
                        <span class="text-gray-500">Nilai UKG</span>
                        <span class="text-gray-300 font-mono font-bold">= (A + B) ÷ 2</span>
                    </div>
                </div>
                <div class="mt-4 bg-red-950/40 border border-red-900/50 rounded-xl p-4">
                    <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1.5">Nilai Akhir</p>
                    <p class="text-white font-black text-sm font-mono">
                        <span class="text-red-400">UKG × 80%</span> + <span class="text-blue-400">Renang × 20%</span>
                    </p>
                    <p class="text-gray-600 text-[10px] mt-2">Lulus jika Nilai Akhir ≥ 70</p>
                </div>
            </div>

            {{-- Grade Legend --}}
            <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
                <h3 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                    <i class="fa-solid fa-medal text-red-800 text-sm"></i> Keterangan Grade
                </h3>
                <div class="space-y-2">
                    @foreach([
                        'A' => ['≥ 80', 'Sangat Baik', 'text-green-400',  'bg-green-900/20'],
                        'B' => ['70–79', 'Baik',        'text-blue-400',   'bg-blue-900/20'],
                        'C' => ['60–69', 'Cukup',       'text-yellow-400', 'bg-yellow-900/20'],
                        'D' => ['50–59', 'Kurang',       'text-orange-400', 'bg-orange-900/20'],
                        'E' => ['< 50',  'Sangat Kurang','text-red-400',    'bg-red-900/20'],
                    ] as $g => [$range, $label, $tc, $bg])
                        <div class="flex items-center gap-3 rounded-lg {{ $g === 'B' ? 'bg-gray-900 px-2 py-1.5' : 'px-1 py-1' }}">
                            <span class="w-7 h-7 flex items-center justify-center rounded-full {{ $bg }} {{ $tc }} font-black text-sm shrink-0">{{ $g }}</span>
                            <span class="{{ $tc }} text-xs font-bold flex-1">{{ $label }}</span>
                            <span class="text-gray-600 text-xs font-mono">{{ $range }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

        </aside>

        {{-- ══════════════════════════════════════════ --}}
        {{-- RIGHT PANEL — form                        --}}
        {{-- ══════════════════════════════════════════ --}}
        <div class="lg:col-span-3">
            <form action="{{ route('kalkulator.polri.hitung') }}" method="POST" class="space-y-4" id="kalkulator-form">
                @csrf

                {{-- GENDER CARD --}}
                <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5 md:p-6">
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                        <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px] shrink-0">1</span>
                        Pilih Gender
                    </h2>
                    <div class="grid grid-cols-2 gap-3">
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="pria" id="gender-pria"
                                onchange="onGenderChange()"
                                {{ old('gender') === 'pria' ? 'checked' : '' }}
                                class="sr-only peer" />
                            <div class="peer-checked:border-red-700 peer-checked:bg-red-900/20 border-2 border-gray-800 rounded-xl p-4 md:p-5 text-center hover:border-gray-600 transition-all duration-200 cursor-pointer h-full">
                                <i class="fa-solid fa-person text-2xl md:text-3xl mb-2 block text-gray-500 peer-checked:text-white"></i>
                                <p class="text-sm font-bold uppercase tracking-wider text-white">Pria</p>
                                <p class="text-[10px] text-gray-600 mt-1">Pull Up (repetisi)</p>
                            </div>
                        </label>
                        <label class="cursor-pointer">
                            <input type="radio" name="gender" value="wanita" id="gender-wanita"
                                onchange="onGenderChange()"
                                {{ old('gender') === 'wanita' ? 'checked' : '' }}
                                class="sr-only peer" />
                            <div class="peer-checked:border-red-700 peer-checked:bg-red-900/20 border-2 border-gray-800 rounded-xl p-4 md:p-5 text-center hover:border-gray-600 transition-all duration-200 cursor-pointer h-full">
                                <i class="fa-solid fa-person-dress text-2xl md:text-3xl mb-2 block text-gray-500 peer-checked:text-white"></i>
                                <p class="text-sm font-bold uppercase tracking-wider text-white">Wanita</p>
                                <p class="text-[10px] text-gray-600 mt-1">Chin Up (detik tahan)</p>
                            </div>
                        </label>
                    </div>
                    @error('gender')<p class="text-red-500 text-xs mt-2">{{ $message }}</p>@enderror
                </div>

                {{-- INPUTS CARD --}}
                <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5 md:p-6">
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-5 flex items-center gap-2">
                        <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px] shrink-0">2</span>
                        Hasil Tes Fisik
                        <span class="text-gray-700 font-normal normal-case tracking-normal text-[10px]">— 6 komponen</span>
                    </h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 md:gap-5">

                        {{-- LARI — full width --}}
                        <div class="md:col-span-2">
                            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-1.5">
                                <i class="fa-solid fa-person-running text-red-800 mr-1"></i>
                                Lari 12 Menit <span class="text-red-500">*</span>
                                <span class="text-gray-600 normal-case tracking-normal font-normal ml-1">(meter)</span>
                            </label>
                            <input type="number" name="raw_lari_meter"
                                value="{{ old('raw_lari_meter') }}"
                                min="0" max="6000" step="1"
                                placeholder="Contoh: 2800"
                                class="w-full bg-black border {{ $errors->has('raw_lari_meter') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-700 transition-all" />
                            <p class="text-gray-600 text-[10px] mt-1.5">
                                Jarak total yang ditempuh dalam 12 menit · Cooper Test · Satuan: meter
                            </p>
                            @error('raw_lari_meter')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- PULL UP --}}
                        <div>
                            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-1.5">
                                <i class="fa-solid fa-dumbbell text-red-800 mr-1"></i>
                                <span id="pullup-label-text">Pull Up / Chin Up</span> <span class="text-red-500">*</span>
                                <span class="text-gray-600 normal-case tracking-normal font-normal ml-1" id="pullup-unit">(pilih gender)</span>
                            </label>
                            <input type="number" name="raw_pullup_reps"
                                value="{{ old('raw_pullup_reps') }}"
                                min="0" max="100" step="1"
                                placeholder="0"
                                id="pullup-input"
                                class="w-full bg-black border {{ $errors->has('raw_pullup_reps') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-700 transition-all" />
                            <p class="text-gray-600 text-[10px] mt-1.5" id="pullup-note">
                                Pria: repetisi pull-up · Wanita: detik chin-up hold
                            </p>
                            @error('raw_pullup_reps')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- SIT UP --}}
                        <div>
                            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-1.5">
                                <i class="fa-solid fa-person text-red-800 mr-1"></i>
                                Sit Up <span class="text-red-500">*</span>
                                <span class="text-gray-600 normal-case tracking-normal font-normal ml-1">(reps / 60 dtk)</span>
                            </label>
                            <input type="number" name="raw_situp_reps"
                                value="{{ old('raw_situp_reps') }}"
                                min="0" max="200" step="1"
                                placeholder="Contoh: 32"
                                class="w-full bg-black border {{ $errors->has('raw_situp_reps') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-700 transition-all" />
                            @error('raw_situp_reps')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- PUSH UP --}}
                        <div>
                            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-1.5">
                                <i class="fa-solid fa-hand-fist text-red-800 mr-1"></i>
                                Push Up <span class="text-red-500">*</span>
                                <span class="text-gray-600 normal-case tracking-normal font-normal ml-1">(reps / 60 dtk)</span>
                            </label>
                            <input type="number" name="raw_pushup_reps"
                                value="{{ old('raw_pushup_reps') }}"
                                min="0" max="200" step="1"
                                placeholder="Contoh: 32"
                                class="w-full bg-black border {{ $errors->has('raw_pushup_reps') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-700 transition-all" />
                            @error('raw_pushup_reps')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- SHUTTLE RUN --}}
                        <div>
                            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-1.5">
                                <i class="fa-solid fa-stopwatch text-red-800 mr-1"></i>
                                Shuttle Run <span class="text-red-500">*</span>
                                <span class="text-gray-600 normal-case tracking-normal font-normal ml-1">(detik)</span>
                            </label>
                            <input type="number" name="raw_shuttle_seconds"
                                value="{{ old('raw_shuttle_seconds') }}"
                                min="5" max="60" step="0.1"
                                placeholder="Contoh: 17.5"
                                class="w-full bg-black border {{ $errors->has('raw_shuttle_seconds') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-700 transition-all" />
                            <p class="text-gray-600 text-[10px] mt-1.5">Lebih cepat = skor lebih tinggi</p>
                            @error('raw_shuttle_seconds')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                        {{-- RENANG — full width with blue accent (20% weight) --}}
                        <div class="md:col-span-2">
                            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-1.5">
                                <i class="fa-solid fa-water text-blue-600 mr-1"></i>
                                Renang 50m <span class="text-red-500">*</span>
                                <span class="text-gray-600 normal-case tracking-normal font-normal ml-1">(detik)</span>
                                <span class="ml-2 bg-blue-900/30 border border-blue-800/40 text-blue-400 text-[9px] px-2 py-0.5 rounded-full font-bold uppercase tracking-widest">Bobot 20%</span>
                            </label>
                            <input type="number" name="raw_renang_seconds"
                                value="{{ old('raw_renang_seconds') }}"
                                min="10" max="999" step="0.1"
                                placeholder="Contoh: 35.0"
                                class="w-full bg-black border {{ $errors->has('raw_renang_seconds') ? 'border-red-600' : 'border-blue-900/40' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-blue-700 transition-all" />
                            <p class="text-gray-600 text-[10px] mt-1.5">
                                Waktu renang gaya bebas 50 meter · Lebih cepat = skor lebih tinggi · Bobot 20% dari nilai akhir POLRI
                            </p>
                            @error('raw_renang_seconds')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>

                    </div>
                </div>

                {{-- MOBILE-ONLY: compact formula info --}}
                <div class="lg:hidden bg-gray-900/40 border border-gray-800 rounded-2xl p-4 text-[11px] text-gray-500 leading-relaxed">
                    <p class="text-gray-400 font-bold text-xs uppercase tracking-widest mb-2">
                        <i class="fa-solid fa-circle-info text-red-800 mr-1"></i> Formula Nilai Akhir POLRI
                    </p>
                    <p>Jasmani B = avg(Pull Up + Sit Up + Push Up + Shuttle Run)</p>
                    <p>Nilai UKG = (Nilai Lari + Jasmani B) ÷ 2</p>
                    <p class="text-white font-semibold mt-1.5">
                        Nilai Akhir = <span class="text-red-400">UKG × 80%</span> + <span class="text-blue-400">Renang × 20%</span>
                    </p>
                    <p class="mt-1">Lulus jika Nilai Akhir ≥ 70</p>
                </div>

                {{-- SUBMIT --}}
                <button type="submit"
                    class="w-full bg-red-800 hover:bg-red-700 active:scale-[0.98] text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg shadow-red-900/30">
                    <i class="fa-solid fa-calculator"></i> HITUNG NILAI SAYA
                </button>

                <p class="text-center text-gray-700 text-xs">
                    Hasil bersifat estimasi · Nilai resmi ditentukan oleh panitia seleksi POLRI
                </p>

            </form>
        </div>

    </div>
</div>

@endsection

@push('scripts')
<script>
function onGenderChange() {
    const gender  = document.querySelector('input[name="gender"]:checked')?.value;
    const unitEl  = document.getElementById('pullup-unit');
    const noteEl  = document.getElementById('pullup-note');
    const inputEl = document.getElementById('pullup-input');

    if (gender === 'pria') {
        unitEl.textContent  = '(repetisi)';
        noteEl.textContent  = 'Jumlah pull-up yang berhasil dilakukan · Tabel POLRI pria: maks. 17 reps = skor 100';
        inputEl.placeholder = 'Contoh: 12';
    } else if (gender === 'wanita') {
        unitEl.textContent  = '(detik tahan)';
        noteEl.textContent  = 'Durasi bertahan dalam posisi chin-up (dagu di atas palang) · Tabel POLRI wanita: maks. 72 dtk = skor 100';
        inputEl.placeholder = 'Contoh: 45';
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const checked = document.querySelector('input[name="gender"]:checked');
    if (checked) onGenderChange();
});
</script>
@endpush
