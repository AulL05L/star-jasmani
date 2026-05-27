@extends('layouts.app')
@section('title', 'Semua Nilai Samapta')
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-5">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Penilaian</p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">Semua <span class="text-red-800">Nilai</span></h1>
            <p class="text-gray-500 text-sm mt-1">Rekap penilaian samapta seluruh atlet</p>
        </div>
        <a href="{{ route('admin.samapta.create') }}"
            class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all self-start sm:self-auto">
            <i class="fa-solid fa-plus"></i>
            <span class="hidden sm:inline">Input Nilai</span>
            <span class="sm:hidden">Input</span>
        </a>
    </div>

    {{-- Stats Cards --}}
    @if($stats)
    <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-4">
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 text-center">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Total Penilaian</p>
            <p class="text-3xl font-black text-white">{{ $stats->total }}</p>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 text-center">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Rata-rata</p>
            <p class="text-3xl font-black text-red-500">{{ $stats->avg_nilai ?? '—' }}</p>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 text-center">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Grade A</p>
            <p class="text-3xl font-black text-green-400">{{ $stats->grade_a }}</p>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 text-center">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Grade B</p>
            <p class="text-3xl font-black text-blue-400">{{ $stats->grade_b }}</p>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4 text-center">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Grade C/D/E</p>
            <p class="text-3xl font-black text-yellow-400">{{ ($stats->grade_c ?? 0) + ($stats->grade_d ?? 0) + ($stats->grade_e ?? 0) }}</p>
            @if(($stats->total ?? 0) > 0)
                <p class="text-gray-600 text-[10px]">dari {{ $stats->total }} peserta</p>
            @endif
        </div>
    </div>
    @endif

    {{-- Tab Parameter (prominent) --}}
    <div class="mb-4">
        <p class="text-gray-600 text-[10px] uppercase tracking-widest font-bold mb-2">Filter Parameter</p>
        <div class="flex flex-wrap gap-2">
            <a href="{{ route('admin.samapta.index', array_merge(request()->except('parameter_ke', 'page'), ['tahun' => $tahun, 'search' => $search, 'grade' => $grade, 'gender' => $gender])) }}"
                class="px-5 py-2.5 rounded-xl font-black uppercase tracking-widest text-xs transition-all
                    {{ !$parameterKe ? 'bg-red-800 text-white shadow-lg shadow-red-900/30' : 'bg-gray-950 border border-gray-800 text-gray-400 hover:border-red-800 hover:text-white' }}">
                Semua Parameter
            </a>
            @foreach([1,2,3,4] as $p)
            @php
                $pLabels = [1=>'Tes Awal', 2=>'Parameter 2', 3=>'Parameter 3', 4=>'Tes Akhir'];
                $hasData = $parameterList->contains($p);
            @endphp
            <a href="{{ route('admin.samapta.index', array_merge(request()->except('parameter_ke', 'page'), ['tahun' => $tahun, 'search' => $search, 'grade' => $grade, 'gender' => $gender, 'parameter_ke' => $p])) }}"
                class="relative px-5 py-2.5 rounded-xl font-black uppercase tracking-widest text-xs transition-all
                    {{ $parameterKe == $p ? 'bg-red-800 text-white shadow-lg shadow-red-900/30' : 'bg-gray-950 border border-gray-800 text-gray-400 hover:border-red-800 hover:text-white' }}
                    {{ !$hasData ? 'opacity-40' : '' }}">
                P{{ $p }} <span class="font-normal opacity-70 hidden sm:inline">· {{ $pLabels[$p] }}</span>
                @if($hasData)
                    <span class="absolute -top-1 -right-1 w-2 h-2 rounded-full bg-green-500"></span>
                @endif
            </a>
            @endforeach
        </div>
    </div>

    {{-- Filter Sekunder --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5 mb-6">
        <form method="GET" action="{{ route('admin.samapta.index') }}" class="grid grid-cols-2 lg:grid-cols-5 gap-3" id="filter-form">
            <input type="hidden" name="parameter_ke" value="{{ $parameterKe }}" />

            {{-- Search --}}
            <div class="col-span-2">
                <label class="block text-gray-500 text-[10px] uppercase tracking-widest mb-1">Cari Nama</label>
                <input type="text" name="search" value="{{ $search }}"
                    placeholder="Nama atlet..."
                    class="w-full bg-black border border-gray-800 text-white text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-800 transition-all placeholder-gray-700" />
            </div>

            {{-- Tahun --}}
            <div>
                <label class="block text-gray-500 text-[10px] uppercase tracking-widest mb-1">Tahun</label>
                <select name="tahun" onchange="this.form.submit()"
                    class="w-full bg-black border border-gray-800 text-white text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-800 appearance-none">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Grade --}}
            <div>
                <label class="block text-gray-500 text-[10px] uppercase tracking-widest mb-1">Grade</label>
                <select name="grade"
                    class="w-full bg-black border border-gray-800 text-white text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-800 appearance-none">
                    <option value="">Semua Grade</option>
                    @foreach(['A','B','C','D','E'] as $g)
                        <option value="{{ $g }}" {{ $grade === $g ? 'selected' : '' }}>Grade {{ $g }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Gender --}}
            <div>
                <label class="block text-gray-500 text-[10px] uppercase tracking-widest mb-1">Gender</label>
                <select name="gender"
                    class="w-full bg-black border border-gray-800 text-white text-xs rounded-xl px-3 py-2.5 focus:outline-none focus:border-red-800 appearance-none">
                    <option value="">Semua</option>
                    <option value="putra" {{ $gender === 'putra' ? 'selected' : '' }}>Putra</option>
                    <option value="putri" {{ $gender === 'putri' ? 'selected' : '' }}>Putri</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="col-span-2 lg:col-span-5 flex gap-2 justify-end">
                <a href="{{ route('admin.samapta.index') }}"
                    class="px-4 py-2 rounded-xl border border-gray-800 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-all">
                    Reset
                </a>
                <button type="submit"
                    class="px-6 py-2 bg-red-800 hover:bg-red-700 text-white rounded-xl text-xs font-bold uppercase tracking-wider transition-all">
                    <i class="fa-solid fa-filter mr-1"></i> Filter
                </button>
            </div>
        </form>
    </div>

    {{-- Tabel --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Hasil Penilaian</h2>
                <p class="text-gray-600 text-xs mt-0.5">
                    {{ $scores->total() }} data
                    @if($parameterKe) · Parameter {{ $parameterKe }} @endif
                    · Tahun {{ $tahun }}
                </p>

                <div class="flex items-center gap-2 py-2">
                    @if($parameterKe)
                    <a href="{{ route('admin.reports.rekap.parameter', ['tahun' => $tahun, 'parameter_ke' => $parameterKe]) }}"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-900 border border-red-900/50 hover:border-red-700 text-red-400 hover:text-red-300 text-xs font-bold uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-file-pdf text-xs"></i> PDF Parameter {{ $parameterKe }}
                    </a>
                    @else
                    <span class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-gray-900 border border-gray-800 text-gray-600 text-xs font-bold uppercase tracking-wider cursor-not-allowed"
                        title="Pilih parameter terlebih dahulu">
                        <i class="fa-solid fa-file-pdf text-xs"></i> PDF Parameter
                    </span>
                    @endif
                    <a href="{{ route('admin.reports.rekap.tahun', ['tahun' => $tahun]) }}"
                        class="flex items-center gap-1.5 px-3 py-2 rounded-xl bg-red-800 hover:bg-red-700 text-white text-xs font-bold uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-file-pdf text-xs"></i> PDF Tahunan
                    </a>
                </div>
            </div>
        </div>


        @if($scores->isEmpty())
            <div class="text-center py-16">
                <i class="fa-solid fa-clipboard-list text-gray-700 text-4xl mb-3"></i>
                <p class="text-gray-500 text-sm">Belum ada data penilaian.</p>
                <a href="{{ route('admin.samapta.create') }}"
                    class="inline-block mt-3 text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest">
                    + Input nilai pertama
                </a>
            </div>
        @else

            {{-- ═══ CARD LAYOUT (mobile + tablet < lg) ═══ --}}
            <div class="lg:hidden divide-y divide-gray-900">
                @foreach($scores as $score)
                @php
                    $gradeBg = match($score->grade ?? 'E') {
                        'A' => 'bg-green-900/50 text-green-400',
                        'B' => 'bg-blue-900/50 text-blue-400',
                        'C' => 'bg-yellow-900/50 text-yellow-400',
                        'D' => 'bg-orange-900/50 text-orange-400',
                        default => 'bg-red-900/50 text-red-400'
                    };
                @endphp
                <div class="p-4">
                    {{-- Row 1: Avatar + Nama + Parameter + Grade --}}
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-10 h-10 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-sm shrink-0">
                                {{ strtoupper(substr($score->athlete?->user?->name ?? '?', 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm truncate">{{ $score->athlete?->user?->name ?? '—' }}</p>
                                <p class="text-gray-500 text-[11px]">
                                    {{ ($score->athlete?->gender === 'pria') ? 'Putra' : 'Putri' }} ·
                                    {{ $score->institution ?? 'POLRI' }} ·
                                    {{ $score->assessment_date->format('d M Y') }}
                                    @if($score->session_label)
                                        · {{ $score->session_label }}
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 shrink-0 ml-2">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-800 text-gray-300">
                                P{{ $score->parameter_ke ?? '—' }}
                            </span>
                            <span class="px-2.5 py-1 rounded-full text-xs font-black {{ $gradeBg }}">
                                {{ $score->grade ?? '—' }}
                            </span>
                        </div>
                    </div>

                    {{-- Row 2: Score mini-cards --}}
                    <div class="grid grid-cols-4 gap-2 mb-3">
                        <div class="bg-black/50 rounded-xl p-2 text-center">
                            <p class="text-gray-600 text-[9px] uppercase tracking-widest mb-0.5">Jas A</p>
                            <p class="text-white font-bold text-sm leading-none">{{ $score->nilai_jasmani_a ?? $score->score_lari ?? '—' }}</p>
                        </div>
                        <div class="bg-black/50 rounded-xl p-2 text-center">
                            <p class="text-gray-600 text-[9px] uppercase tracking-widest mb-0.5">Jas B</p>
                            <p class="text-white font-bold text-sm leading-none">{{ $score->nilai_jasmani_b ? number_format($score->nilai_jasmani_b,1) : '—' }}</p>
                        </div>
                        <div class="bg-black/50 rounded-xl p-2 text-center">
                            <p class="text-gray-600 text-[9px] uppercase tracking-widest mb-0.5">Renang</p>
                            <p class="text-blue-400 font-bold text-sm leading-none">{{ $score->score_renang ?? '—' }}</p>
                        </div>
                        <div class="bg-red-900/20 rounded-xl p-2 text-center">
                            <p class="text-gray-500 text-[9px] uppercase tracking-widest mb-0.5">Final</p>
                            <p class="text-red-400 font-black text-base leading-none">{{ number_format($score->score_final, 1) }}</p>
                        </div>
                    </div>

                    {{-- Row 3: Actions --}}
                    <div class="flex items-center justify-end gap-2">
                        <a href="{{ route('admin.samapta.show', $score) }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white text-xs font-bold transition-all">
                            <i class="fa-solid fa-eye text-xs"></i> Detail
                        </a>
                        <a href="{{ route('admin.samapta.edit', $score) }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-300 hover:text-white text-xs font-bold transition-all">
                            <i class="fa-solid fa-pen text-xs"></i> Edit
                        </a>
                        <a href="{{ route('admin.reports.samapta.pdf', $score) }}"
                            class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-300 hover:text-red-400 text-xs font-bold transition-all">
                            <i class="fa-solid fa-file-pdf text-xs"></i> PDF
                        </a>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ═══ TABLE LAYOUT (desktop ≥ lg) ═══ --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Peserta</th>
                            <th class="text-center px-4 py-3">Parameter</th>
                            <th class="text-left px-4 py-3">Tanggal</th>
                            <th class="text-center px-4 py-3">Jas A</th>
                            <th class="text-center px-4 py-3">Jas B</th>
                            <th class="text-center px-4 py-3">Jasmani</th>
                            <th class="text-center px-4 py-3">Renang</th>
                            <th class="text-center px-4 py-3">Nilai Akhir</th>
                            <th class="text-center px-4 py-3">Grade</th>
                            <th class="text-center px-4 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($scores as $score)
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-xs shrink-0">
                                        {{ strtoupper(substr($score->athlete?->user?->name ?? '?', 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-xs">{{ $score->athlete?->user?->name ?? '—' }}</p>
                                        <p class="text-gray-600 text-[10px]">
                                            {{ Str::ucfirst($score->athlete?->gender ?? '') }} ·
                                            {{ $score->institution ?? 'POLRI' }}
                                        </p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-800 text-gray-300">
                                    P{{ $score->parameter_ke ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-gray-400 text-xs">
                                {{ $score->assessment_date->format('d M Y') }}
                                @if($score->session_label)
                                    <br><span class="text-gray-600 text-[10px]">{{ $score->session_label }}</span>
                                @endif
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300 text-xs font-bold">
                                {{ $score->nilai_jasmani_a ?? $score->score_lari ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-gray-300 text-xs font-bold">
                                {{ $score->nilai_jasmani_b ?? $score->score_jasmani_b ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-white text-xs font-bold">
                                {{ $score->nilai_total_jasmani ?? $score->score_ukg_avg ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center text-blue-400 text-xs font-bold">
                                {{ $score->score_renang ?? '—' }}
                            </td>
                            <td class="px-4 py-3 text-center font-black text-red-500 text-base">
                                {{ number_format($score->score_final, 1) }}
                            </td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-black
                                    {{ $score->grade === 'A' ? 'bg-green-900/50 text-green-400' :
                                      ($score->grade === 'B' ? 'bg-blue-900/50 text-blue-400' :
                                      ($score->grade === 'C' ? 'bg-yellow-900/50 text-yellow-400' :
                                      ($score->grade === 'D' ? 'bg-orange-900/50 text-orange-400' :
                                      'bg-red-900/50 text-red-400'))) }}">
                                    {{ $score->grade ?? '—' }}
                                </span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <div class="flex items-center justify-center gap-1.5">
                                    <a href="{{ route('admin.samapta.show', $score) }}"
                                        class="w-7 h-7 rounded-lg bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white inline-flex items-center justify-center transition-all"
                                        title="Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.samapta.edit', $score) }}"
                                        class="w-7 h-7 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white inline-flex items-center justify-center transition-all"
                                        title="Edit">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.reports.samapta.pdf', $score) }}"
                                        class="w-7 h-7 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 inline-flex items-center justify-center transition-all"
                                        title="Download PDF">
                                        <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Pagination --}}
            @if($scores->hasPages())
            <div class="px-5 py-4 border-t border-gray-800">
                {{ $scores->links('pagination::tailwind') }}
            </div>
            @endif
        @endif
    </div>
</div>
@endsection