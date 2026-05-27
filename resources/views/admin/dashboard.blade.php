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
<div class="min-h-screen bg-black text-white p-4 lg:p-6">

    {{-- ── TOP BAR ── --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <p class="text-gray-600 text-xs uppercase tracking-widest font-bold mb-1">Admin Panel · Coach Dashboard</p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">
                Halo, <span class="text-red-500">{{ explode(' ', auth()->user()->name)[0] }}</span> 👋
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ now()->isoFormat('dddd, D MMMM Y') }} · Coach Dashboard</p>
        </div>
        <div class="flex items-center gap-3">
            {{-- Filter Tahun --}}
            <form method="GET" action="{{ route('admin.dashboard') }}" class="flex items-center gap-2">
                <select name="tahun" onchange="this.form.submit()"
                    class="bg-gray-950 border border-gray-800 text-white text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-red-800">
                    @foreach($tahunList as $t)
                        <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
                    @endforeach
                    <option value="{{ now()->year }}" {{ $tahun == now()->year ? 'selected' : '' }}>{{ now()->year }}</option>
                </select>
                <select name="batch_id" onchange="this.form.submit()"
                    class="bg-gray-950 border border-gray-800 text-white text-xs rounded-lg px-3 py-2 focus:outline-none focus:border-red-800">
                    <option value="">Semua Batch</option>
                    @foreach($batches as $batch)
                        <option value="{{ $batch->id }}" {{ $batchId == $batch->id ? 'selected' : '' }}>
                            {{ $batch->name }}
                        </option>
                    @endforeach
                </select>
            </form>
            <a href="{{ route('admin.samapta.create') }}"
                class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all">
                <i class="fa-solid fa-plus"></i> Input Nilai
            </a>
        </div>
    </div>

    {{-- ── STATS CARDS ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-5 gap-3 mb-6">

        <div class="stat-card rounded-2xl p-4">
            <div class="flex items-start justify-between mb-2">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Total Member</p>
                <div class="w-7 h-7 bg-red-900/30 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-users text-red-500 text-xs"></i>
                </div>
            </div>
            <p class="text-3xl font-black text-white mb-1">{{ $totalMember }}</p>
            <p class="text-gray-600 text-[10px]">
                <span class="text-green-400">{{ $totalPutra }} Putra</span> ·
                <span class="text-pink-400">{{ $totalPutri }} Putri</span>
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

    {{-- ── ROW 2: TREND + DISTRIBUSI GRADE ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Trend Perkembangan Nilai --}}
        <div class="lg:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Trend Perkembangan Nilai</h2>
                    <p class="text-gray-600 text-xs mt-0.5">7 hari terakhir</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-red-600 inline-block"></span> Nilai Akhir</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-gray-600 border-dashed border-t inline-block"></span> Rata-rata</span>
                </div>
            </div>
            <canvas id="trendChart" height="120"></canvas>
        </div>

        {{-- Distribusi Grade --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="mb-4">
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Distribusi Grade</h2>
                <p class="text-gray-600 text-xs mt-0.5">{{ $totalGrade }} total penilaian</p>
            </div>
            <div class="flex items-center justify-center mb-4">
                <canvas id="gradeChart" width="160" height="160"></canvas>
            </div>
            <div class="space-y-2">
                @php
                    $gradeColors = ['A'=>'#16a34a','B'=>'#2563eb','C'=>'#d97706','D'=>'#ea580c','E'=>'#dc2626'];
                    $gradeLabels = ['A'=>'80-100','B'=>'70-79','C'=>'60-69','D'=>'50-59','E'=>'0-49'];
                @endphp
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
    </div>

    {{-- ── ROW 3: GRAFIK KOMPARASI + TREND KELULUSAN ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-4">

        {{-- Grafik 1: Putra vs Putri per Parameter --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Komparasi Putra vs Putri</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Rata-rata nilai akhir per parameter</p>
                </div>
                <div class="flex items-center gap-2 text-xs">
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-700 rounded-sm inline-block"></span><span class="text-gray-400">Putra</span></span>
                    <span class="flex items-center gap-1"><span class="w-3 h-3 bg-red-950 rounded-sm inline-block"></span><span class="text-gray-400">Putri</span></span>
                </div>
            </div>
            <canvas id="komparasiChart" height="160"></canvas>
        </div>

        {{-- Grafik 3: Distribusi Grade per Parameter --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
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
            <canvas id="gradeParamChart" height="160"></canvas>
        </div>
    </div>

    {{-- ── ROW 4: PERFORMA KOMPONEN + STATUS FISIK ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-4">

        {{-- Grafik 2: Distribusi Komponen Tes --}}
        <div class="lg:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
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
            <canvas id="komponenChart" height="130"></canvas>
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

    {{-- ── ROW 5: MEMBER TERBARU + TOP PERFORMER ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

        {{-- Member Terbaru --}}
        <div class="lg:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Member Terbaru</h2>
                    <p class="text-gray-600 text-xs mt-0.5">5 atlet terakhir bergabung</p>
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
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Nama</th>
                            <th class="text-left px-5 py-3">Program</th>
                            <th class="text-center px-5 py-3">Nilai Terakhir</th>
                            <th class="text-center px-5 py-3">Status</th>
                            <th class="text-center px-5 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($memberTerbaru as $athlete)
                        @php $latestScore = $athlete->samaptaScores->first(); @endphp
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="px-5 py-3">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-xs flex-shrink-0">
                                        {{ strtoupper(substr($athlete->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="text-white font-bold text-xs">{{ $athlete->user->name }}</p>
                                        <p class="text-gray-600 text-[10px]">{{ $athlete->birth_date?->age ?? '—' }} Tahun</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-5 py-3">
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-gray-800 text-gray-300">
                                    {{ $athlete->target_institution ?? 'POLRI' }}
                                </span>
                            </td>
                            <td class="px-5 py-3 text-center font-black text-white text-sm">
                                {{ $latestScore ? number_format($latestScore->score_final, 1) : '—' }}
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
            @endif
        </div>

        {{-- Top Performer --}}
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
                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-sm flex-shrink-0
                            {{ $i === 0 ? 'bg-yellow-500 text-black' : ($i === 1 ? 'bg-gray-400 text-black' : 'bg-orange-700 text-white') }}">
                            {{ $i + 1 }}
                        </div>
                        <div class="w-8 h-8 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-xs flex-shrink-0">
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
    </div>

</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: true,
    plugins: {
        legend: { display: false },
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
        x: {
            grid: { color: '#1f2937' },
            ticks: { color: '#6b7280', font: { size: 10 } }
        },
        y: {
            min: 0, max: 100,
            grid: { color: '#1f2937' },
            ticks: { color: '#6b7280', font: { size: 10 }, stepSize: 20 }
        }
    }
};

// ── 1. Trend Nilai 7 Hari ──
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: @json($trendLabels),
        datasets: [
            {
                label: 'Nilai Akhir',
                data: @json($trendNilai),
                borderColor: '#991b1b',
                backgroundColor: 'rgba(153,27,27,0.1)',
                borderWidth: 2,
                pointBackgroundColor: '#991b1b',
                pointRadius: 4,
                fill: true,
                tension: 0.4,
            },
            {
                label: 'Rata-rata',
                data: @json($trendAvgLine),
                borderColor: '#374151',
                borderWidth: 1.5,
                borderDash: [5, 5],
                pointRadius: 0,
                fill: false,
                tension: 0,
            }
        ]
    },
    options: { ...chartDefaults,
        plugins: { ...chartDefaults.plugins, legend: { display: false } }
    }
});

// ── 2. Distribusi Grade Doughnut ──
new Chart(document.getElementById('gradeChart'), {
    type: 'doughnut',
    data: {
        labels: @json(array_keys($gradeDistribution->toArray())),
        datasets: [{
            data: @json(array_values($gradeDistribution->toArray())),
            backgroundColor: ['#16a34a','#2563eb','#d97706','#ea580c','#dc2626'],
            borderColor: '#111',
            borderWidth: 3,
            hoverOffset: 6,
        }]
    },
    options: {
        responsive: false,
        cutout: '68%',
        plugins: {
            legend: { display: false },
            tooltip: {
                backgroundColor: '#111',
                borderColor: '#374151',
                borderWidth: 1,
                titleColor: '#fff',
                bodyColor: '#9ca3af',
            }
        }
    }
});

// ── 3. Komparasi Putra vs Putri ──
new Chart(document.getElementById('komparasiChart'), {
    type: 'bar',
    data: {
        labels: @json($parameterLabels),
        datasets: [
            {
                label: 'Putra',
                data: @json($avgPutraPerParameter),
                backgroundColor: '#991b1b',
                borderRadius: 4,
                borderSkipped: false,
            },
            {
                label: 'Putri',
                data: @json($avgPutriPerParameter),
                backgroundColor: '#450a0a',
                borderRadius: 4,
                borderSkipped: false,
            }
        ]
    },
    options: { ...chartDefaults,
        plugins: {
            ...chartDefaults.plugins,
            legend: {
                display: false,
            },
            tooltip: {
                ...chartDefaults.plugins.tooltip,
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                }
            }
        }
    }
});

// ── 4. Distribusi Grade per Parameter ──
new Chart(document.getElementById('gradeParamChart'), {
    type: 'bar',
    data: {
        labels: @json($gradeParamList->map(fn($p) => "Parameter {$p}")),
        datasets: @json($gradeDatasets->map(fn($d) => array_merge($d, ['borderRadius' => 3, 'borderSkipped' => false])))
    },
    options: {
        ...chartDefaults,
        scales: {
            x: { ...(chartDefaults.scales?.x ?? {}), stacked: true },
            y: { ...(chartDefaults.scales?.y ?? {}), stacked: true, max: undefined, min: 0 }
        },
        plugins: {
            ...chartDefaults.plugins,
            legend: {
                display: true,
                labels: { color: '#9ca3af', font: { size: 10 }, boxWidth: 12 }
            },
            tooltip: {
                ...chartDefaults.plugins.tooltip,
                callbacks: {
                    label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y} atlet`
                }
            }
        }
    }
});

// ── 5. Performa Komponen ──
@php
    $komponenLabels = ['Lari', 'Push-Up', 'Sit-Up', 'Pull-Up', 'Shuttle', 'Renang'];
    $komponenPutraData = $komponenPutra ? [
        $komponenPutra->avg_lari, $komponenPutra->avg_pushup, $komponenPutra->avg_situp,
        $komponenPutra->avg_pullup, $komponenPutra->avg_shuttle, $komponenPutra->avg_renang
    ] : [0,0,0,0,0,0];
    $komponenPutriData = $komponenPutri ? [
        $komponenPutri->avg_lari, $komponenPutri->avg_pushup, $komponenPutri->avg_situp,
        $komponenPutri->avg_pullup, $komponenPutri->avg_shuttle, $komponenPutri->avg_renang
    ] : [0,0,0,0,0,0];
@endphp
new Chart(document.getElementById('komponenChart'), {
    type: 'bar',
    data: {
        labels: @json($komponenLabels),
        datasets: [
            {
                label: 'Putra',
                data: @json($komponenPutraData),
                backgroundColor: '#991b1b',
                borderRadius: 4,
                borderSkipped: false,
            },
            {
                label: 'Putri',
                data: @json($komponenPutriData),
                backgroundColor: '#450a0a',
                borderRadius: 4,
                borderSkipped: false,
            }
        ]
    },
    options: { ...chartDefaults,
        plugins: {
            ...chartDefaults.plugins,
            legend: { display: false }
        }
    }
});
</script>
@endpush