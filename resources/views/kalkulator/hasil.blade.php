@extends('layouts.app')
@section('title', 'Hasil Nilai POLRI Samapta — Star Jasmani')

@section('content')

{{-- HEADER --}}
<header class="sticky top-0 z-50 bg-black/90 backdrop-blur-md border-b border-gray-900 px-6 py-3.5 flex items-center justify-between">
    <div class="flex items-center gap-3">
        <div class="w-8 h-8 overflow-hidden rounded-lg border border-red-800 shrink-0">
            <img src="{{ asset('pict/logo-removebg.png') }}" alt="logo" class="w-full h-full object-cover" />
        </div>
        <div class="hidden sm:flex flex-col leading-none">
            <span class="text-white font-black tracking-tighter text-sm">STAR <span class="text-red-800">JASMANI</span></span>
            <span class="text-gray-600 text-[10px] uppercase tracking-widest">Hasil Kalkulator POLRI</span>
        </div>
    </div>
    <div class="flex items-center gap-3">
        <a href="{{ route('kalkulator.polri') }}"
            class="flex items-center gap-2 bg-red-800/20 hover:bg-red-800 border border-red-800/50 hover:border-red-700 text-red-400 hover:text-white text-xs font-bold uppercase tracking-wider px-3 py-2 rounded-lg transition-all">
            <i class="fa-solid fa-rotate-left text-xs"></i> Hitung Ulang
        </a>
        <a href="{{ route('home') }}"
            class="hidden sm:flex items-center gap-2 text-gray-600 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-house text-xs"></i>
        </a>
    </div>
</header>

@php
    $grade = $hasil['grade'] ?? 'E';
    $gradeColors = match($grade) {
        'A'     => ['text' => 'text-green-400',  'bg' => 'bg-green-950/60',   'border' => 'border-green-800',  'badge_bg' => 'bg-green-900/30',  'badge_border' => 'border-green-700'],
        'B'     => ['text' => 'text-blue-400',   'bg' => 'bg-blue-950/60',    'border' => 'border-blue-800',   'badge_bg' => 'bg-blue-900/30',   'badge_border' => 'border-blue-700'],
        'C'     => ['text' => 'text-yellow-400', 'bg' => 'bg-yellow-950/40',  'border' => 'border-yellow-800', 'badge_bg' => 'bg-yellow-900/30', 'badge_border' => 'border-yellow-700'],
        'D'     => ['text' => 'text-orange-400', 'bg' => 'bg-orange-950/40',  'border' => 'border-orange-800', 'badge_bg' => 'bg-orange-900/30', 'badge_border' => 'border-orange-700'],
        default => ['text' => 'text-red-400',    'bg' => 'bg-red-950/40',     'border' => 'border-red-800',    'badge_bg' => 'bg-red-900/30',    'badge_border' => 'border-red-700'],
    };
    $isLulus     = $hasil['is_lulus'] ?? false;
    $gender      = $hasil['gender'] ?? 'pria';
    $pullupLabel = $gender === 'wanita' ? 'Chin Up' : 'Pull Up';
    $pullupUnit  = $gender === 'wanita' ? 'dtk'     : 'reps';

    $scoreColor = fn($s) => $s >= 80 ? 'text-green-400' : ($s >= 70 ? 'text-blue-400' : ($s >= 60 ? 'text-yellow-400' : 'text-orange-400'));
@endphp

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- HERO BANNER — grade-colored, 3 stat cards side-by-side     --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div class="{{ $gradeColors['bg'] }} border-b {{ $gradeColors['border'] }} py-10 md:py-14 px-4">
    <div class="max-w-5xl mx-auto">

        {{-- 3 Stat Cards --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-0">

            {{-- Stat 1: Nilai Akhir --}}
            <div class="text-center md:border-r {{ $gradeColors['border'] }} md:pr-8 lg:pr-12">
                <p class="text-gray-500 text-[10px] uppercase tracking-[0.3em] font-bold mb-3">Nilai Akhir</p>
                <p class="{{ $gradeColors['text'] }} text-7xl md:text-8xl font-black tracking-tighter leading-none">
                    {{ number_format($hasil['score_final'] ?? 0, 1) }}
                </p>
                <p class="text-gray-600 text-xs mt-2 font-mono">/ 100</p>
            </div>

            {{-- Stat 2: Grade --}}
            <div class="text-center md:border-r {{ $gradeColors['border'] }} md:px-8 lg:px-12">
                <p class="text-gray-500 text-[10px] uppercase tracking-[0.3em] font-bold mb-3">Grade</p>
                <p class="{{ $gradeColors['text'] }} text-7xl md:text-8xl font-black tracking-tighter leading-none">
                    {{ $grade }}
                </p>
                <p class="{{ $gradeColors['text'] }} text-sm font-bold uppercase tracking-wider mt-2">
                    {{ $hasil['grade_label'] ?? '—' }}
                </p>
            </div>

            {{-- Stat 3: Status --}}
            <div class="text-center md:pl-8 lg:pl-12">
                <p class="text-gray-500 text-[10px] uppercase tracking-[0.3em] font-bold mb-3">Status</p>
                @if($isLulus)
                    <i class="fa-solid fa-circle-check text-6xl md:text-7xl text-green-400 leading-none"></i>
                    <p class="text-green-400 font-black text-xl uppercase tracking-widest mt-2">LULUS</p>
                    <p class="text-gray-600 text-xs mt-1">Nilai Akhir ≥ 70</p>
                @else
                    <i class="fa-solid fa-circle-xmark text-6xl md:text-7xl text-red-400 leading-none"></i>
                    <p class="text-red-400 font-black text-xl uppercase tracking-widest mt-2">BELUM LULUS</p>
                    <p class="text-gray-600 text-xs mt-1">Nilai Akhir &lt; 70</p>
                @endif
            </div>

        </div>

        {{-- Info badges --}}
        <div class="flex flex-wrap items-center justify-center gap-2 mt-8">
            <span class="bg-black/40 border border-gray-800 text-gray-400 text-xs px-3 py-1.5 rounded-full">
                <i class="fa-solid fa-{{ $gender === 'pria' ? 'person' : 'person-dress' }} mr-1"></i>
                {{ $gender === 'pria' ? 'Pria' : 'Wanita' }}
            </span>
            <span class="bg-black/40 border border-gray-800 text-gray-400 text-xs px-3 py-1.5 rounded-full">
                <i class="fa-solid fa-shield-halved text-red-800 mr-1"></i> POLRI Samapta B
            </span>
            <span class="bg-black/40 border border-gray-800 text-gray-400 text-xs px-3 py-1.5 rounded-full">
                Formula: <span class="text-red-400">80%</span> UKG + <span class="text-blue-400">20%</span> Renang
            </span>
        </div>

    </div>
</div>

{{-- ════════════════════════════════════════════════════════════ --}}
{{-- MAIN CONTENT                                               --}}
{{-- ════════════════════════════════════════════════════════════ --}}
<div class="max-w-5xl mx-auto px-4 py-8 md:px-6 lg:px-8 pb-16 space-y-6">

    {{-- TWO-COL: Component Scores + Score Breakdown --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        {{-- ── Component Scores ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                    <i class="fa-solid fa-list-check text-red-800"></i> Skor Per Komponen
                </h2>
                <span class="text-gray-700 text-[10px] uppercase tracking-widest">6 Komponen</span>
            </div>

            @php
                $components = [
                    ['Lari 12 Menit',  'fa-person-running',   number_format($hasil['raw_lari_meter']) . ' m',            $hasil['score_lari']],
                    [$pullupLabel,     'fa-dumbbell',          $hasil['raw_pullup_reps'] . ' ' . $pullupUnit,             $hasil['score_pullup']],
                    ['Sit Up',         'fa-person',            $hasil['raw_situp_reps'] . ' reps',                        $hasil['score_situp']],
                    ['Push Up',        'fa-hand-fist',         $hasil['raw_pushup_reps'] . ' reps',                       $hasil['score_pushup']],
                    ['Shuttle Run',    'fa-stopwatch',         $hasil['raw_shuttle_seconds'] . ' dtk',                    $hasil['score_shuttle']],
                    ['Renang 50m',     'fa-water',             $hasil['raw_renang_seconds'] . ' dtk',                     $hasil['score_renang']],
                ];
            @endphp

            <div class="divide-y divide-gray-900">
                @foreach($components as [$name, $icon, $raw, $score])
                    <div class="flex items-center gap-4 px-5 py-3.5 hover:bg-gray-900/30 transition-colors">
                        <i class="fa-solid {{ $icon }} text-gray-700 w-4 text-sm shrink-0"></i>
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center justify-between mb-1">
                                <p class="text-white text-sm font-semibold">{{ $name }}</p>
                                <p class="font-black text-base {{ $scoreColor($score) }} ml-2 shrink-0">
                                    {{ number_format($score, 1) }}
                                </p>
                            </div>
                            <div class="flex items-center gap-2">
                                <div class="flex-1 bg-gray-800 rounded-full h-1 overflow-hidden">
                                    <div class="h-full rounded-full {{ $score >= 70 ? 'bg-gradient-to-r from-red-900 to-red-700' : 'bg-gray-700' }}"
                                         style="width: {{ min(100, max(0, $score)) }}%"></div>
                                </div>
                                <p class="text-gray-600 text-[10px] font-mono shrink-0">{{ $raw }}</p>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── Score Breakdown ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden flex flex-col">
            <div class="px-6 py-4 border-b border-gray-800">
                <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                    <i class="fa-solid fa-diagram-project text-red-800"></i> Breakdown Perhitungan
                </h2>
            </div>

            <div class="p-5 md:p-6 space-y-3 flex-1 flex flex-col">

                <div class="flex items-start justify-between py-2.5 border-b border-gray-900">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold">Jasmani A</p>
                        <p class="text-gray-600 text-xs mt-0.5">Nilai Lari</p>
                    </div>
                    <p class="text-white font-black text-xl">{{ number_format($hasil['score_lari'], 1) }}</p>
                </div>

                <div class="flex items-start justify-between py-2.5 border-b border-gray-900">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold">Jasmani B</p>
                        <p class="text-gray-600 text-xs mt-0.5">avg({{ $pullupLabel }}, Sit Up, Push Up, Shuttle)</p>
                    </div>
                    <p class="text-white font-black text-xl">{{ number_format($hasil['score_jasmani_b'], 1) }}</p>
                </div>

                <div class="flex items-start justify-between py-2.5 border-b border-gray-900">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold">Nilai UKG</p>
                        <p class="text-gray-600 text-xs mt-0.5">(Jasmani A + Jasmani B) ÷ 2</p>
                    </div>
                    <p class="text-white font-black text-xl">{{ number_format($hasil['score_ukg_avg'], 1) }}</p>
                </div>

                <div class="flex items-start justify-between py-2.5 border-b border-gray-900">
                    <div>
                        <p class="text-gray-300 text-sm font-semibold">Renang</p>
                        <p class="text-gray-600 text-xs mt-0.5">Bobot 20% dari nilai akhir</p>
                    </div>
                    <p class="text-blue-400 font-black text-xl">{{ number_format($hasil['score_renang'], 1) }}</p>
                </div>

                {{-- Final formula box --}}
                <div class="{{ $gradeColors['badge_bg'] }} border {{ $gradeColors['badge_border'] }} rounded-xl p-4 mt-auto">
                    <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Formula</p>
                    <p class="text-gray-400 text-xs mb-3 font-mono">
                        (<span class="text-red-400">{{ number_format($hasil['score_ukg_avg'], 1) }}</span> × 0.80)
                        + (<span class="text-blue-400">{{ number_format($hasil['score_renang'], 1) }}</span> × 0.20)
                    </p>
                    <div class="flex items-baseline gap-3">
                        <p class="{{ $gradeColors['text'] }} font-black text-4xl leading-none">
                            {{ number_format($hasil['score_final'], 1) }}
                        </p>
                        <div>
                            <p class="text-gray-500 text-xs">/ 100</p>
                            <p class="{{ $gradeColors['text'] }} text-xs font-bold">Grade {{ $grade }} · {{ $hasil['grade_label'] }}</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>

    {{-- GRADE LEGEND --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
        <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
            <i class="fa-solid fa-star text-red-800"></i> Keterangan Grade
        </h2>
        <div class="grid grid-cols-5 gap-2 text-center">
            @foreach([
                'A' => ['≥ 80', 'Sangat Baik', 'text-green-400'],
                'B' => ['70–79', 'Baik',        'text-blue-400'],
                'C' => ['60–69', 'Cukup',       'text-yellow-400'],
                'D' => ['50–59', 'Kurang',       'text-orange-400'],
                'E' => ['< 50',  'Sgt Kurang',   'text-red-400'],
            ] as $g => [$range, $label, $color])
                <div class="rounded-xl py-3 px-1 {{ $grade === $g ? 'bg-gray-800 ring-1 ring-white/10' : 'bg-gray-900' }}">
                    <p class="font-black text-2xl {{ $color }}">{{ $g }}</p>
                    <p class="text-gray-500 text-[10px] mt-0.5">{{ $range }}</p>
                    <p class="text-gray-600 text-[10px] leading-tight mt-0.5 hidden sm:block">{{ $label }}</p>
                </div>
            @endforeach
        </div>
        <p class="text-gray-700 text-[10px] mt-3 text-center">Grade A atau B = Lulus (Nilai Akhir ≥ 70)</p>
    </div>

    {{-- CTAs --}}
    <div class="flex flex-col sm:flex-row gap-3">
        <a href="{{ route('kalkulator.polri') }}"
            class="flex-1 flex items-center justify-center gap-2 bg-red-800 hover:bg-red-700 active:scale-[0.98] text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all shadow-lg shadow-red-900/20">
            <i class="fa-solid fa-rotate-left"></i> Hitung Ulang
        </a>
        <button disabled
            title="Segera hadir — fitur sedang dalam pengembangan"
            class="flex-1 flex items-center justify-center gap-2 bg-gray-900 border border-gray-700 text-gray-600 font-black uppercase tracking-widest text-sm py-5 rounded-2xl cursor-not-allowed">
            <i class="fa-solid fa-file-pdf"></i> Download PDF — Segera Hadir
        </button>
    </div>

    <p class="text-center text-gray-700 text-xs pb-2">
        Hasil estimasi berdasarkan standar POLRI Samapta B · Nilai resmi ditentukan panitia seleksi ·
        <a href="{{ route('home') }}" class="text-red-800 hover:text-red-500 transition-colors">Latihan bersama Star Jasmani</a>
    </p>

</div>

@endsection
