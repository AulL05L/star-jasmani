@extends('layouts.app')

@section('title', 'Detail Nilai Samapta')

@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Hasil Penilaian</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                HASIL <span class="text-red-800">SAMAPTA</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ $samaptaScore->athlete->user->name }} · {{ $samaptaScore->assessment_date->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.samapta.index') }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Semua Nilai
        </a>
    </div>

    {{-- Nilai Akhir Card --}}
    <div class="bg-gray-950 border border-red-900 rounded-2xl p-8 mb-6 text-center">
        <p class="text-gray-500 text-xs uppercase tracking-widest mb-2">Nilai Akhir POLRI</p>
        <p class="text-8xl font-black text-white mb-2">{{ number_format($samaptaScore->score_final, 1) }}</p>
        <span class="inline-block px-6 py-2 rounded-full text-lg font-black uppercase tracking-widest
            {{ $samaptaScore->grade === 'A' ? 'bg-green-900/50 text-green-400' :
              ($samaptaScore->grade === 'B' ? 'bg-blue-900/50 text-blue-400' :
              ($samaptaScore->grade === 'C' ? 'bg-yellow-900/50 text-yellow-400' :
              'bg-red-900/50 text-red-400')) }}">
            Grade {{ $samaptaScore->grade }}
        </span>
        <div class="flex justify-center gap-8 mt-6 text-sm">
            <div>
                <p class="text-gray-600 text-xs uppercase tracking-widest">Rata-rata UKG</p>
                <p class="text-white font-black text-xl">{{ number_format($samaptaScore->score_ukg_avg, 1) }}</p>
            </div>
            <div class="w-px bg-gray-800"></div>
            <div>
                <p class="text-gray-600 text-xs uppercase tracking-widest">Nilai Renang</p>
                <p class="text-white font-black text-xl">{{ $samaptaScore->score_renang ?? '—' }}</p>
            </div>
            <div class="w-px bg-gray-800"></div>
            <div>
                <p class="text-gray-600 text-xs uppercase tracking-widest">Institusi</p>
                <p class="text-white font-black text-xl">{{ $samaptaScore->institution }}</p>
            </div>
        </div>
    </div>

    {{-- Breakdown Komponen --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 mb-6">
        <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-6 flex items-center gap-2">
            <i class="fa-solid fa-chart-bar text-red-800"></i> Breakdown Komponen UKG
        </h2>
        <div class="space-y-4">
            @php
                $components = [
                    ['label' => 'Lari 12 Menit', 'raw' => $samaptaScore->raw_lari_meter . ' m', 'score' => $samaptaScore->score_lari, 'icon' => 'fa-person-running'],
                    ['label' => 'Push-Up', 'raw' => $samaptaScore->raw_pushup_reps . ' reps', 'score' => $samaptaScore->score_pushup, 'icon' => 'fa-dumbbell'],
                    ['label' => 'Sit-Up', 'raw' => $samaptaScore->raw_situp_reps . ' reps', 'score' => $samaptaScore->score_situp, 'icon' => 'fa-child-reaching'],
                    ['label' => $samaptaScore->athlete->upper_body_test, 'raw' => $samaptaScore->raw_pullup_reps . ' reps', 'score' => $samaptaScore->score_pullup, 'icon' => 'fa-arrow-up-from-bracket'],
                    ['label' => 'Shuttle Run', 'raw' => $samaptaScore->raw_shuttle_seconds . ' dtk', 'score' => $samaptaScore->score_shuttle, 'icon' => 'fa-shuffle'],
                ];
            @endphp

            @foreach($components as $comp)
            <div class="flex items-center gap-4">
                <div class="w-8 flex-shrink-0 text-center">
                    <i class="fa-solid {{ $comp['icon'] }} text-red-800 text-sm"></i>
                </div>
                <div class="w-32 flex-shrink-0">
                    <p class="text-gray-400 text-xs font-bold uppercase tracking-wider">{{ $comp['label'] }}</p>
                    <p class="text-gray-600 text-xs">{{ $comp['raw'] }}</p>
                </div>
                <div class="flex-1 bg-gray-900 rounded-full h-2">
                    <div class="bg-red-800 h-2 rounded-full transition-all"
                        style="width: {{ $comp['score'] ?? 0 }}%"></div>
                </div>
                <div class="w-12 text-right">
                    <span class="text-white font-black text-sm">{{ $comp['score'] ?? '—' }}</span>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    {{-- Info Peserta + Sesi --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                <i class="fa-solid fa-user text-red-800"></i> Info Peserta
            </h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Nama</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->athlete->user->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Gender</dt>
                    <dd class="text-white font-bold">{{ Str::ucfirst($samaptaScore->athlete->gender) }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Target Institusi</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->athlete->target_institution ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Batch</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->athlete->batch ?? '—' }}</dd>
                </div>
            </dl>
        </div>

        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-list text-red-800"></i> Info Sesi
            </h2>
            <dl class="space-y-3 text-sm">
                <div class="flex justify-between">
                    <dt class="text-gray-500">Tanggal</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->assessment_date->format('d M Y') }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Label Sesi</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->session_label ?? '—' }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Dinilai oleh</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->coach->name }}</dd>
                </div>
                <div class="flex justify-between">
                    <dt class="text-gray-500">Institusi</dt>
                    <dd class="text-white font-bold">{{ $samaptaScore->institution }}</dd>
                </div>
            </dl>
        </div>
    </div>

    {{-- Catatan --}}
    @if($samaptaScore->notes)
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 mb-6">
        <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-3 flex items-center gap-2">
            <i class="fa-solid fa-note-sticky text-red-800"></i> Catatan Coach
        </h2>
        <p class="text-gray-400 text-sm leading-relaxed">{{ $samaptaScore->notes }}</p>
    </div>
    @endif

    {{-- Actions --}}
    <div class="flex flex-col sm:flex-row gap-3 pb-10">
        <a href="{{ route('admin.samapta.create') }}"
            class="flex-1 bg-red-800 hover:bg-red-700 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl transition-all flex items-center justify-center gap-3">
            <i class="fa-solid fa-plus"></i> Input Nilai Baru
        </a>
        <a href="{{ route('admin.samapta.edit', $samaptaScore) }}"
            class="flex items-center justify-center gap-2 py-4 px-8 rounded-2xl border border-gray-800 text-gray-400 hover:text-white hover:border-gray-600 text-sm font-bold uppercase tracking-wider transition-all">
            <i class="fa-solid fa-pen"></i> Edit
        </a>
        <a href="{{ route('admin.reports.samapta.pdf', $samaptaScore) }}"
            class="flex items-center justify-center gap-2 py-4 px-8 rounded-2xl border border-gray-700 text-gray-300 hover:text-white hover:border-white text-sm font-bold uppercase tracking-wider transition-all">
            <i class="fa-solid fa-file-pdf text-red-500"></i> Download PDF
        </a>
        <form action="{{ route('admin.samapta.destroy', $samaptaScore) }}" method="POST"
            onsubmit="return confirm('Hapus data ini?')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="w-full flex items-center justify-center gap-2 py-4 px-8 rounded-2xl border border-red-900 text-red-500 hover:bg-red-900/30 text-sm font-bold uppercase tracking-wider transition-all">
                <i class="fa-solid fa-trash"></i> Hapus
            </button>
        </form>
    </div>
</div>
@endsection