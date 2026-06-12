@extends('layouts.app')

@section('title', 'Dashboard Admin')

@push('styles')
<style>
    .stat-card {
        background: linear-gradient(135deg, #111111 0%, #1a1a1a 100%);
        border: 1px solid #27272a;
        transition: all 0.3s ease;
    }
    .stat-card:hover {
        border-color: #991b1b;
        transform: translateY(-2px);
        box-shadow: 0 8px 30px rgba(153,27,27,0.15);
    }
    .progress-bar { height: 4px; border-radius: 2px; background: #27272a; overflow: hidden; }
    .progress-fill { height: 100%; border-radius: 2px; transition: width 1s ease; }
    .chart-container { position: relative; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-black text-white p-3 lg:p-5">

    {{-- ── TOP BAR ── --}}
    <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between mb-4">
        <div>
            <p class="text-gray-600 text-xs uppercase tracking-widest font-bold mb-1">Admin Panel · Coach Dashboard</p>
            <h1 class="text-xl md:text-2xl lg:text-3xl font-extrabold tracking-tighter">
                Halo, <span class="text-red-500">{{ explode(' ', auth()->user()->name)[0] }}</span> 👋
            </h1>
            <p class="text-gray-500 text-xs md:text-sm mt-1">{{ now()->isoFormat('dddd, D MMMM Y') }}</p>
        </div>
        <div class="flex items-center gap-2 flex-wrap">
            {{-- Program Tabs --}}
            <div class="flex bg-gray-950 border border-gray-800 rounded-xl p-1 gap-1">
                <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['program' => 'polri'])) }}"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all
                    {{ $program === 'polri' ? 'bg-red-800 text-white' : 'text-gray-500 hover:text-white' }}">
                    <i class="fa-solid fa-shield-halved mr-1"></i> POLRI
                </a>
                <a href="{{ route('admin.dashboard', array_merge(request()->query(), ['program' => 'kebugaran'])) }}"
                    class="px-4 py-1.5 rounded-lg text-xs font-bold uppercase tracking-wider transition-all
                    {{ $program === 'kebugaran' ? 'bg-red-800 text-white' : 'text-gray-500 hover:text-white' }}">
                    <i class="fa-solid fa-heart-pulse mr-1"></i> Kebugaran
                </a>
            </div>

            {{-- Filter tahun & batch (hanya relevan untuk POLRI) --}}
            @if($program === 'polri')
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <input type="hidden" name="program" value="{{ $program }}">
                <select name="tahun" onchange="this.form.submit()"
                    class="bg-gray-950 border border-gray-800 text-white text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-red-800">
                    @foreach($tahunList->unique() as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                </select>
                <select name="batch_id" onchange="this.form.submit()"
                    class="bg-gray-950 border border-gray-800 text-white text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-red-800 max-w-30 md:max-w-none truncate">
                    <option value="">Semua Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>{{ $batch->name }}</option>
                    @endforeach
                </select>
            </form>
            @endif

            <a href="{{ $program === 'polri' ? route('admin.samapta.create') : route('admin.kebugaran.period.create') }}"
                class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-3 md:px-4 py-2 md:py-2.5 rounded-xl transition-all whitespace-nowrap">
                <i class="fa-solid fa-plus"></i>
                <span class="hidden sm:inline">{{ $program === 'polri' ? 'Input Nilai' : 'Buat Periode' }}</span>
            </a>
        </div>
    </div>

    {{-- ── STATS CARDS ── --}}
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-5 gap-3 mb-4">

        <div class="stat-card rounded-2xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Total Member</p>
                <div class="w-7 h-7 bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-red-500 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $totalMember }}</p>
            <p class="text-gray-600 text-[10px]">
                <span class="text-red-400">{{ $totalPolri }} POLRI</span> ·
                <span class="text-emerald-400">{{ $totalKebugaran }} Kebugaran</span>
            </p>
            <div class="progress-bar mt-2">
                <div class="progress-fill bg-red-700" style="width: {{ min(100, $totalMember * 4) }}%"></div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Member Baru</p>
                <div class="w-7 h-7 bg-green-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user-plus text-green-500 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $memberBaruBulanIni }}</p>
            <p class="text-[10px] {{ $memberBaruBulanIni >= $memberBaruBulanLalu ? 'text-green-400' : 'text-red-400' }}">
                {{ $memberBaruBulanIni >= $memberBaruBulanLalu ? '+' : '' }}{{ $memberBaruBulanIni - $memberBaruBulanLalu }} dari bulan lalu
            </p>
            <div class="progress-bar mt-2">
                <div class="progress-fill bg-green-700" style="width: {{ min(100, $memberBaruBulanIni * 10) }}%"></div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Program Berjalan</p>
                <div class="w-7 h-7 bg-blue-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-clipboard-list text-blue-500 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $programBerjalan }}</p>
            <p class="text-gray-600 text-[10px]">Parameter {{ $parameterTerakhir }} berjalan · {{ $tahun }}</p>
            <div class="progress-bar mt-2">
                <div class="progress-fill bg-blue-700" style="width: {{ min(100, $programBerjalan * 20) }}%"></div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Rata-rata Nilai</p>
                <div class="w-7 h-7 bg-orange-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-orange-500 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $rataRataNilai }}</p>
            <p class="text-[10px] {{ $trendRataRata >= 0 ? 'text-green-400' : 'text-red-400' }}">
                {{ $trendRataRata >= 0 ? '↑' : '↓' }} {{ abs($trendRataRata) }}% dari minggu lalu
            </p>
            <div class="progress-bar mt-2">
                <div class="progress-fill bg-orange-700" style="width: {{ $rataRataNilai }}%"></div>
            </div>
        </div>

        <div class="stat-card rounded-2xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Evaluasi Minggu Ini</p>
                <div class="w-7 h-7 bg-purple-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-calendar-check text-purple-500 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $evaluasiMingguIni }}</p>
            <p class="text-gray-600 text-[10px]">Jadwal evaluasi</p>
            <div class="progress-bar mt-2">
                <div class="progress-fill bg-purple-700" style="width: {{ min(100, $evaluasiMingguIni * 10) }}%"></div>
            </div>
        </div>
    </div>

    {{-- ── ROW 2: PROGRAM SNAPSHOT + DISTRIBUSI GRADE ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">

        {{-- Program Snapshot --}}
        <div class="md:col-span-1 lg:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-3">

            {{-- POLRI Card --}}
            <a href="{{ route('admin.dashboard', ['program' => 'polri']) }}"
                class="block bg-gray-950 border rounded-2xl p-5 transition-all hover:-translate-y-0.5
                {{ $program === 'polri' ? 'border-red-800 shadow-lg shadow-red-900/20' : 'border-gray-800 hover:border-gray-700' }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-red-900/30 flex items-center justify-center">
                        <i class="fa-solid fa-shield-halved text-red-500"></i>
                    </div>
                    @if($program === 'polri')
                        <span class="text-[9px] font-bold uppercase tracking-widest text-red-500 bg-red-900/20 px-2 py-1 rounded-full">Aktif</span>
                    @endif
                </div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Program POLRI</p>
                <p class="text-4xl font-black text-white mb-3">{{ $totalPolri }}</p>
                <div class="grid grid-cols-2 gap-2 text-center border-t border-gray-800 pt-3">
                    <div>
                        <p class="text-xl font-black text-red-400">{{ $rataRataNilai ?: '—' }}</p>
                        <p class="text-[9px] text-gray-600 uppercase tracking-wide">Rata-rata Nilai</p>
                    </div>
                    <div>
                        <p class="text-xl font-black text-white">{{ $evaluasiMingguIni }}</p>
                        <p class="text-[9px] text-gray-600 uppercase tracking-wide">Evaluasi Minggu Ini</p>
                    </div>
                </div>
            </a>

            {{-- Kebugaran Card --}}
            <a href="{{ route('admin.dashboard', ['program' => 'kebugaran']) }}"
                class="block bg-gray-950 border rounded-2xl p-5 transition-all hover:-translate-y-0.5
                {{ $program === 'kebugaran' ? 'border-red-800 shadow-lg shadow-red-900/20' : 'border-gray-800 hover:border-gray-700' }}">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-10 h-10 rounded-xl bg-emerald-900/20 flex items-center justify-center">
                        <i class="fa-solid fa-heart-pulse text-emerald-500"></i>
                    </div>
                    @if($program === 'kebugaran')
                        <span class="text-[9px] font-bold uppercase tracking-widest text-red-500 bg-red-900/20 px-2 py-1 rounded-full">Aktif</span>
                    @endif
                </div>
                <p class="text-[10px] font-bold uppercase tracking-widest text-gray-500 mb-1">Program Kebugaran</p>
                <p class="text-4xl font-black text-white mb-3">{{ $totalKebugaran }}</p>
                <div class="grid grid-cols-2 gap-2 text-center border-t border-gray-800 pt-3">
                    <div>
                        <p class="text-xl font-black text-emerald-400">{{ $kebugaranStats['total_periods'] }}</p>
                        <p class="text-[9px] text-gray-600 uppercase tracking-wide">Total Periode</p>
                    </div>
                    <div>
                        <p class="text-xl font-black text-white">{{ $kebugaranStats['total_sessions'] }}</p>
                        <p class="text-[9px] text-gray-600 uppercase tracking-wide">Total Sesi</p>
                    </div>
                </div>
            </a>

        </div>

        {{-- Grade / Kategori — conditional per program --}}
        @if($program === 'polri')
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="mb-4">
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Distribusi Grade</h2>
                <p class="text-gray-600 text-xs mt-0.5">{{ $totalGrade }} total penilaian</p>
            </div>
            <div class="flex items-center justify-center mb-4">
                <canvas id="gradeChart" width="160" height="160"></canvas>
            </div>
            <div class="space-y-2">
                @php $gradeColors=['A'=>'#16a34a','B'=>'#2563eb','C'=>'#d97706','D'=>'#ea580c','E'=>'#dc2626']; $gradeLabels=['A'=>'80-100','B'=>'70-79','C'=>'60-69','D'=>'50-59','E'=>'0-49']; @endphp
                @foreach($gradeDistribution as $grade => $count)
                <div class="flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <span class="w-3 h-3 rounded-sm inline-block" style="background:{{ $gradeColors[$grade] ?? '#6b7280' }}"></span>
                        <span class="text-gray-400 text-xs">Grade {{ $grade }} ({{ $gradeLabels[$grade] ?? '' }})</span>
                    </div>
                    <div class="flex items-center gap-2">
                        <span class="text-white font-black text-sm">{{ $count }}</span>
                        <span class="text-gray-600 text-xs">({{ $totalGrade > 0 ? round($count/$totalGrade*100) : 0 }}%)</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @else
        {{-- Distribusi Kategori Kebugaran --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="mb-4">
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Distribusi Kategori</h2>
                <p class="text-gray-600 text-xs mt-0.5">Berdasarkan skor total terbaru</p>
            </div>
            @php
                $catCounts = ['sangat_baik'=>0,'baik'=>0,'cukup'=>0,'kurang'=>0];
                foreach($kebugaranChartData as $d) $catCounts[$d['category']]++;
                $catTotal = array_sum($catCounts);
                $catDef = ['sangat_baik'=>['Sangat Baik','#10b981'],'baik'=>['Baik','#22c55e'],'cukup'=>['Cukup','#f59e0b'],'kurang'=>['Kurang','#ef4444']];
            @endphp
            <div class="space-y-3">
                @foreach($catDef as $key => [$label, $color])
                <div>
                    <div class="flex justify-between text-xs mb-1">
                        <span class="text-gray-400">{{ $label }}</span>
                        <span class="text-white font-bold">{{ $catCounts[$key] }} atlet</span>
                    </div>
                    <div class="h-2 bg-gray-800 rounded-full overflow-hidden">
                        <div class="h-full rounded-full transition-all" style="width:{{ $catTotal > 0 ? round($catCounts[$key]/$catTotal*100) : 0 }}%; background:{{ $color }}"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @if($kebugaranStats['last_session'])
            <p class="text-gray-700 text-[10px] mt-4 pt-3 border-t border-gray-800">
                Sesi terakhir: {{ \Carbon\Carbon::parse($kebugaranStats['last_session'])->isoFormat('D MMM Y') }}
            </p>
            @endif
        </div>
        @endif
    </div>

    {{-- ── POLRI ROWS 3 & 4 ── --}}
    @if($program === 'polri')

    {{-- ROW 3: Komparasi + Grade per Parameter --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 mb-3">
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Komparasi Putra vs Putri</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Rata-rata nilai akhir per parameter</p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-700 rounded-sm inline-block"></span><span class="text-gray-400">Putra</span></span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-950 rounded-sm inline-block"></span><span class="text-gray-400">Putri</span></span>
                </div>
            </div>
            <canvas id="komparasiChart" height="130"></canvas>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Distribusi Grade per Parameter</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Jumlah atlet per grade tiap parameter</p>
                </div>
                <div class="flex items-center gap-2 flex-wrap text-[10px]">
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-green-400 inline-block"></span><span class="text-gray-400">A</span></span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-blue-400 inline-block"></span><span class="text-gray-400">B</span></span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-yellow-400 inline-block"></span><span class="text-gray-400">C</span></span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-orange-400 inline-block"></span><span class="text-gray-400">D</span></span>
                    <span class="flex items-center gap-1"><span class="w-2.5 h-2.5 rounded-sm bg-red-400 inline-block"></span><span class="text-gray-400">E</span></span>
                </div>
            </div>
            <canvas id="gradeParamChart" height="130"></canvas>
        </div>
    </div>

    {{-- ── ROW 4: PERFORMA KOMPONEN + STATUS FISIK ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3 mb-3">

        {{-- Grafik 2: Distribusi Komponen Tes --}}
        <div class="md:col-span-1 lg:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Performa Per Komponen</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Rata-rata skor tiap item tes fisik</p>
                </div>
                <form method="GET" action="{{ route('admin.dashboard') }}">
                    <input type="hidden" name="tahun" value="{{ $tahun }}">
                    <input type="hidden" name="batch_id" value="{{ $batchId }}">
                    <select name="gender" onchange="this.form.submit()"
                        class="bg-black border border-gray-800 text-white text-xs rounded-lg px-3 py-1.5 focus:outline-none focus:border-red-800">
                        <option value="all" {{ $genderFilter === 'all' ? 'selected' : '' }}>Semua</option>
                        <option value="putra" {{ $genderFilter === 'putra' ? 'selected' : '' }}>Putra</option>
                        <option value="putri" {{ $genderFilter === 'putri' ? 'selected' : '' }}>Putri</option>
                    </select>
                </form>
            </div>
            <canvas id="komponenChart" height="110"></canvas>
        </div>

        {{-- Status Fisik Rata-rata --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <h2 class="text-white font-bold text-sm uppercase tracking-widest mb-4">Statistik Fisik Rata-rata</h2>
            @if($statusFisik)
            <div class="space-y-3">
                @php
                    $komponenList = [
                        ['label' => 'Lari', 'val' => $statusFisik->avg_lari, 'icon' => 'fa-person-running', 'color' => 'bg-red-700'],
                        ['label' => 'Push-Up', 'val' => $statusFisik->avg_pushup, 'icon' => 'fa-dumbbell', 'color' => 'bg-orange-700'],
                        ['label' => 'Sit-Up', 'val' => $statusFisik->avg_situp, 'icon' => 'fa-child-reaching', 'color' => 'bg-yellow-700'],
                        ['label' => 'Pull-Up', 'val' => $statusFisik->avg_pullup, 'icon' => 'fa-arrow-up', 'color' => 'bg-green-700'],
                        ['label' => 'Shuttle', 'val' => $statusFisik->avg_shuttle, 'icon' => 'fa-shuffle', 'color' => 'bg-blue-700'],
                        ['label' => 'Renang', 'val' => $statusFisik->avg_renang, 'icon' => 'fa-water', 'color' => 'bg-purple-700'],
                    ];
                @endphp
                @foreach($komponenList as $k)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid {{ $k['icon'] }} text-gray-500 text-xs w-3"></i>
                            <span class="text-gray-400 text-xs">{{ $k['label'] }}</span>
                        </div>
                        <span class="text-white font-black text-xs">{{ $k['val'] ?? '—' }}</span>
                    </div>
                    <div class="progress-bar">
                        <div class="progress-fill {{ $k['color'] }}" style="width: {{ $k['val'] ?? 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>
            @else
                <p class="text-gray-600 text-sm text-center py-8">Belum ada data.</p>
            @endif
        </div>
    </div>

    {{-- ── KEBUGARAN ROWS 3 & 4 ── --}}
    @else

    {{-- Kebugaran Row 3: Per-Atlet Progress Table --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden mb-3">
        <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
            <div>
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Progress Per Atlet</h2>
                <p class="text-gray-600 text-xs mt-0.5">Nilai sesi terakhir vs sesi sebelumnya — naik/turun per parameter</p>
            </div>
            <a href="{{ route('admin.kebugaran.index') }}"
                class="text-emerald-500 hover:text-emerald-400 text-xs font-bold uppercase tracking-widest">
                Lihat Detail →
            </a>
        </div>
        @if(empty($kebugaranChartData))
            <div class="text-center py-12">
                <i class="fa-solid fa-dumbbell text-gray-700 text-3xl mb-3"></i>
                <p class="text-gray-500 text-sm">Belum ada data kebugaran. Buat periode & sesi terlebih dahulu.</p>
            </div>
        @else
        <div class="overflow-x-auto">
        <table class="w-full text-sm min-w-[700px]">
            <thead>
                <tr class="border-b border-gray-800 text-gray-600 text-[10px] uppercase tracking-widest">
                    <th class="text-left px-4 py-3 sticky left-0 bg-gray-950">Atlet</th>
                    <th class="text-center px-3 py-3">Sesi</th>
                    <th class="text-center px-3 py-3">Total Skor</th>
                    <th class="text-center px-3 py-3">Kategori</th>
                    @foreach(\App\Services\KebugaranScoring::$parameters as $pk => $pInfo)
                    <th class="text-center px-2 py-3 whitespace-nowrap">{{ $pInfo['label'] }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-900">
                @foreach($kebugaranChartData as $d)
                @php
                    $catColor = ['sangat_baik'=>'emerald','baik'=>'green','cukup'=>'amber','kurang'=>'red'][$d['category']] ?? 'gray';
                    $deltaClass = ($d['delta'] ?? 0) > 0 ? 'text-emerald-400' : (($d['delta'] ?? 0) < 0 ? 'text-red-400' : 'text-gray-500');
                    $deltaIcon  = ($d['delta'] ?? 0) > 0 ? 'fa-arrow-up' : (($d['delta'] ?? 0) < 0 ? 'fa-arrow-down' : 'fa-minus');
                @endphp
                <tr class="hover:bg-gray-900/50 transition-colors">
                    <td class="px-4 py-3 sticky left-0 bg-gray-950">
                        <div class="flex items-center gap-2">
                            <div class="w-7 h-7 rounded-full bg-emerald-800 flex items-center justify-center text-white font-black text-xs shrink-0">
                                {{ strtoupper(substr($d['name'], 0, 1)) }}
                            </div>
                            <div>
                                <p class="text-white font-bold text-xs">{{ $d['name'] }}</p>
                                <p class="text-gray-600 text-[10px]">{{ $d['gender'] === 'pria' ? 'Pria' : 'Wanita' }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-3 py-3 text-center text-gray-400 text-xs">{{ $d['sessionCount'] }}</td>
                    <td class="px-3 py-3 text-center">
                        <span class="text-white font-black text-sm">{{ $d['totalScore'] }}</span>
                        @if($d['delta'] !== null)
                        <span class="{{ $deltaClass }} text-[10px] font-bold block leading-none mt-0.5">
                            <i class="fa-solid {{ $deltaIcon }} text-[8px]"></i>
                            {{ $d['delta'] > 0 ? '+' : '' }}{{ $d['delta'] }}
                        </span>
                        @endif
                    </td>
                    <td class="px-3 py-3 text-center">
                        <span class="px-1.5 py-0.5 rounded text-[9px] font-bold bg-{{ $catColor }}-900/40 text-{{ $catColor }}-400">
                            {{ \App\Services\KebugaranScoring::categoryLabel($d['category']) }}
                        </span>
                    </td>
                    @foreach(\App\Services\KebugaranScoring::$parameters as $pk => $pInfo)
                    @php
                        $val     = $d['scores'][$pk] ?? null;
                        $prevVal = $d['prevScores'][$pk] ?? null;
                        $up = ($val !== null && $prevVal !== null && $val > $prevVal);
                        $dn = ($val !== null && $prevVal !== null && $val < $prevVal);
                    @endphp
                    <td class="px-2 py-3 text-center whitespace-nowrap">
                        @if($val !== null)
                            <span class="text-white text-xs font-bold">{{ $val }}</span>
                            @if($up)<i class="fa-solid fa-arrow-up text-emerald-400 text-[8px] ml-0.5"></i>
                            @elseif($dn)<i class="fa-solid fa-arrow-down text-red-400 text-[8px] ml-0.5"></i>
                            @else<i class="fa-solid fa-minus text-gray-600 text-[8px] ml-0.5"></i>@endif
                        @else
                            <span class="text-gray-700 text-xs">—</span>
                        @endif
                    </td>
                    @endforeach
                </tr>
                @endforeach
            </tbody>
        </table>
        </div>
        @endif
    </div>

    {{-- Kebugaran Row 4: Avg Per Parameter Chart + Ranking --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-3 mb-3">
        <div class="md:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <div class="mb-3">
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Rata-rata Per Parameter</h2>
                <p class="text-gray-600 text-xs mt-0.5">Persentase pencapaian target — semua atlet kebugaran (sesi terakhir)</p>
            </div>
            <canvas id="kebugaranParamChart" height="120"></canvas>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <h2 class="text-white font-bold text-sm uppercase tracking-widest mb-4">Ranking Skor</h2>
            @if(empty($kebugaranChartData))
                <p class="text-gray-600 text-sm text-center py-8">Belum ada data.</p>
            @else
            @php $sortedKebugaran = collect($kebugaranChartData)->sortByDesc('totalScore')->values(); @endphp
            <div class="space-y-3">
                @foreach($sortedKebugaran as $i => $d)
                @php $sc = $d['totalScore']; $scColor = $sc>=85?'emerald':($sc>=65?'green':($sc>=45?'amber':'red')); @endphp
                <div class="flex items-center gap-3">
                    <div class="w-6 h-6 rounded-full flex items-center justify-center font-black text-xs shrink-0
                        {{ $i===0?'bg-yellow-500 text-black':($i===1?'bg-gray-400 text-black':'bg-orange-700 text-white') }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="w-7 h-7 rounded-full bg-emerald-800 flex items-center justify-center text-white font-black text-xs shrink-0">
                        {{ strtoupper(substr($d['name'], 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-xs truncate">{{ $d['name'] }}</p>
                        <p class="text-gray-600 text-[10px]">{{ $d['gender']==='pria'?'Pria':'Wanita' }} · {{ $d['sessionCount'] }} sesi</p>
                    </div>
                    <div class="text-right">
                        <p class="text-{{ $scColor }}-400 font-black text-sm">{{ $sc }}</p>
                        @if($d['delta'] !== null)
                        <p class="{{ $d['delta']>=0?'text-emerald-400':'text-red-400' }} text-[10px]">
                            {{ $d['delta']>=0?'+':'' }}{{ $d['delta'] }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
    </div>

    @endif
    {{-- ── END PROGRAM CONDITIONAL ── --}}

    {{-- ── ROW LAST: MEMBER TERBARU + PROGRAM-SPECIFIC RIGHT COLUMN ── --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">

        {{-- Member Terbaru --}}
        <div class="md:col-span-1 lg:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Member Terbaru</h2>
                    <p class="text-gray-600 text-xs mt-0.5">
                        5 terbaru ·
                        <span class="{{ $program === 'polri' ? 'text-red-400' : 'text-emerald-400' }} font-bold">
                            {{ $program === 'polri' ? 'POLRI' : 'Kebugaran' }}
                        </span>
                    </p>
                </div>
                <a href="{{ route('admin.athletes.index') }}"
                    class="text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest">
                    Lihat Semua →
                </a>
            </div>
            @if($memberTerbaru->isEmpty())
                <div class="text-center py-12">
                    <i class="fa-solid fa-users text-gray-700 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada member.</p>
                </div>
            @else
                {{-- Card layout (mobile/tablet < lg) --}}
                <div class="lg:hidden divide-y divide-gray-900">
                    @foreach($memberTerbaru as $athlete)
                    @php $latestScore = $program === 'polri' ? $athlete->samaptaScores->first() : null; @endphp
                    <div class="px-4 py-3 flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-9 h-9 rounded-full {{ $program==='polri'?'bg-red-800':'bg-emerald-800' }} flex items-center justify-center text-white font-black text-xs shrink-0">
                                {{ strtoupper(substr($athlete->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm truncate">{{ $athlete->user->name }}</p>
                                <p class="text-gray-500 text-[11px]">
                                    @if($program === 'polri')
                                        {{ $athlete->target_institution ?? 'POLRI' }} · {{ $athlete->batch ?? '—' }}
                                    @else
                                        {{ $athlete->kebugaranPeriods->count() }} periode
                                    @endif
                                </p>
                            </div>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <div class="text-right">
                                <p class="text-white font-black text-sm">
                                    @if($program === 'polri')
                                        {{ $latestScore ? number_format($latestScore->score_final, 1) : '—' }}
                                    @else
                                        {{ $athlete->kebugaranPeriods->sum(fn($p)=>$p->sessions->count()) }} sesi
                                    @endif
                                </p>
                                <p class="text-gray-600 text-[10px]">{{ $program === 'polri' ? 'Nilai' : 'Total' }}</p>
                            </div>
                            <a href="{{ route('admin.athletes.show', $athlete) }}"
                                class="w-8 h-8 bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white rounded-xl inline-flex items-center justify-center transition-all shrink-0">
                                <i class="fa-solid fa-eye text-xs"></i>
                            </a>
                        </div>
                    </div>
                    @endforeach
                </div>
                {{-- Table layout (desktop ≥ lg) --}}
                <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Nama</th>
                            <th class="text-left px-5 py-3">{{ $program === 'polri' ? 'Institusi' : 'Periode' }}</th>
                            <th class="text-center px-5 py-3">{{ $program === 'polri' ? 'Nilai Terakhir' : 'Total Sesi' }}</th>
                            <th class="text-center px-5 py-3">Status</th>
                            <th class="text-center px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($memberTerbaru as $athlete)
                        @php $latestScore = $program === 'polri' ? $athlete->samaptaScores->first() : null; @endphp
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full {{ $program==='polri'?'bg-red-800':'bg-emerald-800' }} flex items-center justify-center text-white font-black text-xs shrink-0">
                                        {{ strtoupper(substr($athlete->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-xs">{{ $athlete->user->name }}</p>
                                        <p class="text-gray-600 text-[10px]">{{ $athlete->birth_date?->age ?? '—' }} Tahun</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                @if($program === 'polri')
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-800 text-gray-300">
                                    {{ $athlete->target_institution ?? 'POLRI' }}
                                </span>
                                @else
                                <span class="text-gray-400 text-xs">{{ $athlete->kebugaranPeriods->count() }} periode</span>
                                @endif
                            </td>
                            <td class="px-5 py-3 text-center font-black text-white text-sm">
                                @if($program === 'polri'){{ $latestScore ? number_format($latestScore->score_final, 1) : '—' }}
                                @else{{ $athlete->kebugaranPeriods->sum(fn($p)=>$p->sessions->count()) }}@endif
                            </td>
                            <td class="px-5 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                    {{ $athlete->user->is_active ? 'bg-green-900/50 text-green-400' : 'bg-gray-800 text-gray-500' }}">
                                    {{ $athlete->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center">
                                <a href="{{ route('admin.athletes.show', $athlete) }}"
                                    class="w-7 h-7 bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white rounded-lg inline-flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
            @endif
        </div>

        {{-- Right Column: conditional per program --}}
        @if($program === 'polri')
        {{-- Top Performer (POLRI) --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Top Performer</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Nilai akhir tertinggi {{ $tahun }}</p>
                </div>
            </div>
            @if($topPerformer->isEmpty())
                <div class="text-center py-8">
                    <i class="fa-solid fa-trophy text-gray-700 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada data.</p>
                </div>
            @else
                <div class="space-y-4">
                    @foreach($topPerformer as $i => $score)
                    <div class="flex items-center gap-3">
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm shrink-0
                            {{ $i === 0 ? 'bg-yellow-500 text-black' : ($i === 1 ? 'bg-gray-400 text-black' : 'bg-orange-700 text-white') }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-xs shrink-0">
                            {{ strtoupper(substr($score->athlete?->user?->name ?? '?', 0, 1)) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-white font-bold text-xs truncate">{{ $score->athlete?->user?->name ?? '—' }}</p>
                            <p class="text-gray-600 text-[10px]">{{ $score->athlete?->target_institution ?? 'POLRI' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-green-400 font-black text-sm">{{ number_format($score->score_final, 1) }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>
        @else
        {{-- Top Skor Kebugaran --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-5">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Top Skor Kebugaran</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Skor total tertinggi saat ini</p>
                </div>
            </div>
            @if(empty($kebugaranChartData))
                <div class="text-center py-8">
                    <i class="fa-solid fa-trophy text-gray-700 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada data.</p>
                </div>
            @else
            @php $topKebugaran = collect($kebugaranChartData)->sortByDesc('totalScore')->values(); @endphp
            <div class="space-y-4">
                @foreach($topKebugaran as $i => $d)
                @php $sc = $d['totalScore']; $scColor = $sc>=85?'emerald':($sc>=65?'green':($sc>=45?'amber':'red')); @endphp
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm shrink-0
                        {{ $i===0?'bg-yellow-500 text-black':($i===1?'bg-gray-400 text-black':'bg-orange-700 text-white') }}">
                        {{ $i + 1 }}
                    </div>
                    <div class="w-8 h-8 rounded-full bg-emerald-800 flex items-center justify-center text-white font-black text-xs shrink-0">
                        {{ strtoupper(substr($d['name'], 0, 1)) }}
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-white font-bold text-xs truncate">{{ $d['name'] }}</p>
                        <p class="text-gray-600 text-[10px]">{{ $d['gender']==='pria'?'Pria':'Wanita' }} · {{ $d['sessionCount'] }} sesi</p>
                    </div>
                    <div class="text-right">
                        <p class="text-{{ $scColor }}-400 font-black text-sm">{{ $sc }}</p>
                        @if($d['delta'] !== null)
                        <p class="{{ $d['delta']>=0?'text-emerald-400':'text-red-400' }} text-[10px]">
                            {{ $d['delta']>=0?'+':'' }}{{ $d['delta'] }}
                        </p>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @endif
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.2.0/dist/chartjs-plugin-datalabels.min.js"></script>
<script>
Chart.register(ChartDataLabels);

const chartDefaults = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: { display: false },
        datalabels: { display: false },
        tooltip: {
            backgroundColor: '#111',
            borderColor: '#374151',
            borderWidth: 1,
            titleColor: '#fff',
            bodyColor: '#9ca3af',
            padding: 10,
        }
    },
    scales: {
        x: { grid: { color: '#1f2937' }, ticks: { color: '#6b7280', font: { size: 10 } } },
        y: { min: 0, max: 100, grid: { color: '#1f2937' }, ticks: { color: '#6b7280', font: { size: 10 }, stepSize: 20 } }
    }
};

const komponenColors = ['#38bdf8','#fb923c','#34d399','#a78bfa','#fbbf24','#22d3ee'];

@if($program === 'polri')

// ── POLRI: Grade Doughnut ──
new Chart(document.getElementById('gradeChart'), {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($gradeDistribution->toArray())),
        datasets: [{
            data: @json(array_values($gradeDistribution->toArray())),
            backgroundColor: ['#16a34a','#2563eb','#d97706','#ea580c','#dc2626'],
            borderColor: '#111', borderWidth: 3, hoverOffset: 6,
        }]
    },
    options: {
        responsive: false, cutout: '68%',
        plugins: { legend: { display: false }, datalabels: { display: false },
            tooltip: { backgroundColor: '#111', borderColor: '#374151', borderWidth: 1, titleColor: '#fff', bodyColor: '#9ca3af' }
        }
    }
});

// ── POLRI: Komparasi Putra vs Putri ──
new Chart(document.getElementById('komparasiChart'), {
    type: 'bar',
    data: {
        labels: @json($parameterLabels),
        datasets: [
            { label: 'Putra', data: @json($avgPutraPerParameter), backgroundColor: '#991b1b', borderRadius: 4, borderSkipped: false },
            { label: 'Putri', data: @json($avgPutriPerParameter), backgroundColor: '#450a0a', borderRadius: 4, borderSkipped: false }
        ]
    },
    options: { ...chartDefaults, plugins: { ...chartDefaults.plugins,
        tooltip: { ...chartDefaults.plugins.tooltip, callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}` } }
    }}
});

// ── POLRI: Grade per Parameter ──
new Chart(document.getElementById('gradeParamChart'), {
    type: 'bar',
    data: {
        labels: @json($gradeParamList->map(fn($p) => "Parameter {$p}")),
        datasets: @json($gradeDatasets->map(fn($d) => array_merge($d, ['borderRadius' => 3, 'borderSkipped' => false])))
    },
    options: { ...chartDefaults,
        scales: {
            x: { ...(chartDefaults.scales?.x ?? {}), stacked: true },
            y: { ...(chartDefaults.scales?.y ?? {}), stacked: true, max: undefined, min: 0 }
        },
        plugins: { ...chartDefaults.plugins,
            legend: { display: true, labels: { color: '#9ca3af', font: { size: 10 }, boxWidth: 12 } },
            tooltip: { ...chartDefaults.plugins.tooltip, callbacks: { label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} atlet` } },
            datalabels: { display: ctx => ctx.dataset.data[ctx.dataIndex] > 0, color: '#fff', font: { size: 8, weight: 'bold' }, formatter: val => val || '' }
        }
    }
});

// ── POLRI: Performa Komponen ──
@php
    $komponenLabels = ['Lari', 'Push-Up', 'Sit-Up', 'Pull-Up', 'Shuttle', 'Renang'];
    $komponenPutraData = $komponenPutra
        ? [$komponenPutra->avg_lari,$komponenPutra->avg_pushup,$komponenPutra->avg_situp,$komponenPutra->avg_pullup,$komponenPutra->avg_shuttle,$komponenPutra->avg_renang]
        : [0,0,0,0,0,0];
    $komponenPutriData = $komponenPutri
        ? [$komponenPutri->avg_lari,$komponenPutri->avg_pushup,$komponenPutri->avg_situp,$komponenPutri->avg_pullup,$komponenPutri->avg_shuttle,$komponenPutri->avg_renang]
        : [0,0,0,0,0,0];
@endphp
new Chart(document.getElementById('komponenChart'), {
    type: 'bar',
    data: {
        labels: @json($komponenLabels),
        datasets: [
            { label: 'Putra', data: @json($komponenPutraData), backgroundColor: komponenColors, borderRadius: 4, borderSkipped: false },
            { label: 'Putri', data: @json($komponenPutriData), backgroundColor: komponenColors.map(c => c + '99'), borderRadius: 4, borderSkipped: false }
        ]
    },
    options: { ...chartDefaults, plugins: { ...chartDefaults.plugins,
        legend: { display: true, labels: { color: '#9ca3af', font: { size: 10 }, boxWidth: 10 } },
        datalabels: { display: ctx => ctx.dataset.data[ctx.dataIndex] > 0, color: '#fff', font: { size: 9, weight: 'bold' }, anchor: 'end', align: 'top', offset: -2, formatter: val => val ? Math.round(val) : '' }
    }}
});

@else

// ── KEBUGARAN: Rata-rata per Parameter ──
@php
    $kebParamLabels = collect(\App\Services\KebugaranScoring::$parameters)->map(fn($p) => $p['label'])->values();
    $kebParamAvgVals = collect(\App\Services\KebugaranScoring::$parameters)->keys()
        ->map(fn($pk) => $kebugaranParamAvg[$pk] ?? 0)->values();
    $kebParamColors = ['#10b981','#22c55e','#3b82f6','#f59e0b','#ef4444','#8b5cf6','#06b6d4','#f97316'];
@endphp
new Chart(document.getElementById('kebugaranParamChart'), {
    type: 'bar',
    data: {
        labels: @json($kebParamLabels),
        datasets: [{
            label: 'Rata-rata',
            data: @json($kebParamAvgVals),
            backgroundColor: @json($kebParamColors),
            borderRadius: 5,
            borderSkipped: false,
        }]
    },
    options: { ...chartDefaults,
        scales: {
            x: { ...chartDefaults.scales.x },
            y: { ...chartDefaults.scales.y, max: 100, ticks: { ...chartDefaults.scales.y.ticks, callback: v => v + '%' } }
        },
        plugins: { ...chartDefaults.plugins,
            tooltip: { ...chartDefaults.plugins.tooltip, callbacks: { label: ctx => ` Rata-rata: ${ctx.parsed.y}%` } },
            datalabels: {
                display: ctx => ctx.dataset.data[ctx.dataIndex] > 0,
                color: '#fff', font: { size: 9, weight: 'bold' },
                anchor: 'end', align: 'top', offset: -2,
                formatter: val => val ? Math.round(val) + '%' : '',
            }
        }
    }
});

@endif
</script>
@endpush