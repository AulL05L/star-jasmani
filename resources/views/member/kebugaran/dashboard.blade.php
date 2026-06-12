@extends('layouts.app')
@section('title', 'Dashboard Kebugaran')

@push('styles')
<style>
/* Ring progress */
.ring-progress { transition: stroke-dashoffset 1s ease-in-out; }

/* Card hover */
.param-card { transition: border-color .25s, transform .25s; }
.param-card:hover { transform: translateY(-2px); }

/* Progress bar */
.pbar-fill { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-zinc-950 text-white p-4 lg:p-8">

    {{-- ── HEADER ── --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <p class="text-red-500 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Dashboard Kebugaran</p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">
                Halo, <span class="text-red-500">{{ auth()->user()->name }}</span>
            </h1>
            <p class="text-zinc-500 text-sm mt-1">Pantau perkembangan kebugaran kamu dari waktu ke waktu</p>
        </div>

        {{-- Period Switcher --}}
        @if($periods->count() > 1)
        <form method="GET">
            <select name="period_id" onchange="this.form.submit()"
                class="bg-zinc-900 border border-zinc-700 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-800 focus:outline-none">
                @foreach($periods as $p)
                    <option value="{{ $p->id }}" {{ ($period && $period->id == $p->id) ? 'selected' : '' }}>
                        {{ $p->name }}
                    </option>
                @endforeach
            </select>
        </form>
        @endif
    </div>

    @if(!$period)
    {{-- No data state --}}
    <div class="flex flex-col items-center justify-center py-24 text-zinc-600">
        <i class="fa-solid fa-heart-pulse text-5xl mb-4 text-zinc-800"></i>
        <p class="font-bold text-lg text-zinc-500">Belum Ada Data Kebugaran</p>
        <p class="text-sm mt-1">Coach kamu belum membuat periode latihan. Hubungi coach untuk memulai.</p>
    </div>
    @else

    {{-- ── HERO: Total Score + Info Periode ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- Score Ring --}}
        <div class="lg:col-span-1 bg-zinc-900 border border-zinc-800 rounded-2xl p-6 flex flex-col items-center justify-center">
            <p class="text-[10px] uppercase tracking-widest text-zinc-500 mb-4">Skor Kebugaran Total</p>
            <div class="relative w-36 h-36">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#27272a" stroke-width="10"/>
                    <circle cx="60" cy="60" r="50" fill="none"
                        stroke="{{ match($totalScore['category']) {
                            'sangat_baik' => '#10b981',
                            'baik'        => '#22c55e',
                            'cukup'       => '#f59e0b',
                            default       => '#ef4444'
                        } }}"
                        stroke-width="10"
                        stroke-linecap="round"
                        stroke-dasharray="{{ 2 * pi() * 50 }}"
                        stroke-dashoffset="{{ 2 * pi() * 50 * (1 - $totalScore['score'] / 100) }}"
                        class="ring-progress"
                    />
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-4xl font-black text-white">{{ $totalScore['score'] }}</span>
                    <span class="text-[10px] uppercase tracking-widest mt-1
                        {{ match($totalScore['category']) {
                            'sangat_baik' => 'text-emerald-400',
                            'baik'        => 'text-green-400',
                            'cukup'       => 'text-amber-400',
                            default       => 'text-red-400'
                        } }}">
                        {{ \App\Services\KebugaranScoring::categoryLabel($totalScore['category']) }}
                    </span>
                </div>
            </div>
            <p class="text-zinc-500 text-xs mt-3 text-center">
                Berdasarkan {{ count($latestScores) }} parameter terbaru
            </p>
        </div>

        {{-- Info Periode + Stats --}}
        <div class="lg:col-span-2 grid grid-cols-2 sm:grid-cols-3 gap-4">
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 flex flex-col justify-between">
                <p class="text-[10px] uppercase tracking-widest text-zinc-500 mb-2">Periode Aktif</p>
                <p class="font-black text-white text-lg leading-tight">{{ $period->name }}</p>
                <p class="text-zinc-500 text-xs mt-1">{{ $period->start_date->format('d M Y') }}</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 flex flex-col justify-between">
                <p class="text-[10px] uppercase tracking-widest text-zinc-500 mb-2">Total Sesi</p>
                <p class="font-black text-white text-4xl">{{ $sessions->count() }}</p>
                <p class="text-zinc-500 text-xs mt-1">sesi evaluasi</p>
            </div>
            <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-4 flex flex-col justify-between">
                <p class="text-[10px] uppercase tracking-widest text-zinc-500 mb-2">Sesi Terakhir</p>
                @if($sessions->last())
                    <p class="font-black text-white text-lg">{{ $sessions->last()->date->format('d M Y') }}</p>
                    <p class="text-zinc-500 text-xs mt-1">Sesi #{{ $sessions->last()->session_number }}</p>
                @else
                    <p class="text-zinc-600 text-sm">—</p>
                @endif
            </div>
            {{-- Kategori distribution --}}
            @php
                $catCount = ['sangat_baik'=>0, 'baik'=>0, 'cukup'=>0, 'kurang'=>0];
                foreach($latestScores as $s) $catCount[$s['category']]++;
            @endphp
            <div class="col-span-2 sm:col-span-3 bg-zinc-900 border border-zinc-800 rounded-2xl p-4">
                <p class="text-[10px] uppercase tracking-widest text-zinc-500 mb-3">Ringkasan Kategori</p>
                <div class="grid grid-cols-4 gap-2">
                    @foreach(['sangat_baik'=>['Sangat Baik','emerald'], 'baik'=>['Baik','green'], 'cukup'=>['Cukup','amber'], 'kurang'=>['Kurang','red']] as $cat => [$label, $color])
                    <div class="text-center">
                        <p class="text-2xl font-black text-{{ $color }}-400">{{ $catCount[$cat] }}</p>
                        <p class="text-[10px] text-zinc-500 uppercase tracking-wide">{{ $label }}</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    {{-- ── 7 PARAMETER CARDS ── --}}
    @if(count($latestScores))
    <h2 class="text-xs font-bold uppercase tracking-widest text-zinc-500 mb-3">Parameter Terbaru</h2>
    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 xl:grid-cols-7 gap-3 mb-6">
        @foreach($parameters as $key => $param)
        @php $s = $latestScores[$key] ?? null; @endphp
        <div class="param-card bg-zinc-900 border rounded-2xl p-4
            {{ $s ? 'border-zinc-700 hover:border-'.($s['color'] === 'emerald' || $s['color'] === 'green' ? 'green' : ($s['color'] === 'amber' ? 'amber' : 'red')).'-700' : 'border-zinc-800' }}">
            <div class="flex items-center justify-between mb-3">
                <i class="fa-solid {{ $param['icon'] }} text-red-500 text-sm"></i>
                @if($s)
                <span class="text-[9px] font-bold uppercase tracking-wide px-2 py-0.5 rounded-full
                    {{ match($s['color']) {
                        'emerald','green' => 'bg-green-900/40 text-green-400',
                        'amber'           => 'bg-amber-900/40 text-amber-400',
                        default           => 'bg-red-900/40 text-red-400'
                    } }}">
                    {{ $s['label'] }}
                </span>
                @endif
            </div>
            <p class="text-[10px] text-zinc-500 uppercase tracking-wider mb-1">{{ $param['label'] }}</p>
            @if($s)
                <p class="text-2xl font-black text-white leading-none">
                    {{ $s['value'] }}
                    @if($param['unit'])<span class="text-sm font-normal text-zinc-400">{{ $param['unit'] }}</span>@endif
                </p>
                <div class="mt-3">
                    <div class="h-1.5 bg-zinc-800 rounded-full overflow-hidden">
                        <div class="pbar-fill h-full rounded-full
                            {{ match($s['color']) {
                                'emerald','green' => 'bg-green-500',
                                'amber'           => 'bg-amber-500',
                                default           => 'bg-red-500'
                            } }}"
                            style="width: {{ $s['percentage'] }}%">
                        </div>
                    </div>
                    <p class="text-[9px] text-zinc-600 mt-1">Target: {{ $s['range'] }}</p>
                </div>
            @else
                <p class="text-zinc-700 text-sm">—</p>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- ── TREND CHART ── --}}
    @if($sessions->count() > 1 && !empty($chartData['labels']))
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-6 mb-6">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h2 class="text-sm font-bold text-white">Perkembangan Per Parameter</h2>
                <p class="text-xs text-zinc-500 mt-0.5">Tren nilai dari sesi ke sesi</p>
            </div>
            {{-- Legend toggle --}}
            <div id="chart-legend" class="flex flex-wrap gap-2 justify-end max-w-xs"></div>
        </div>
        <div class="relative h-64">
            <canvas id="trendChart"></canvas>
        </div>
    </div>
    @endif

    {{-- ── SESSION HISTORY TABLE ── --}}
    @if($sessions->count())
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-800">
            <h2 class="text-sm font-bold text-white">Riwayat Sesi</h2>
            <p class="text-xs text-zinc-500 mt-0.5">Semua sesi dalam periode {{ $period->name }}</p>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-[600px]">
                <thead>
                    <tr class="border-b border-zinc-800">
                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500">Sesi</th>
                        <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500">Tanggal</th>
                        @foreach($parameters as $key => $param)
                        <th class="px-3 py-3 text-center text-[10px] uppercase tracking-widest text-zinc-500">{{ $param['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    @foreach($sessions as $sess)
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-5 py-3 font-bold text-white">#{{ $sess->session_number }}</td>
                        <td class="px-5 py-3 text-zinc-400 text-xs">{{ $sess->date->format('d M Y') }}</td>
                        @foreach(array_keys($parameters) as $key)
                        @php
                            $sc = $sess->scoreFor($key);
                            $cat = $sc ? \App\Services\KebugaranScoring::category($key, $sc->value, $athlete->gender) : null;
                        @endphp
                        <td class="px-3 py-3 text-center">
                            @if($sc)
                                <span class="font-bold text-xs
                                    {{ match($cat) {
                                        'sangat_baik','baik' => 'text-green-400',
                                        'cukup'              => 'text-amber-400',
                                        default              => 'text-red-400'
                                    } }}">
                                    {{ $sc->value }}{{ $parameters[$key]['unit'] ? ' '.$parameters[$key]['unit'] : '' }}
                                </span>
                            @else
                                <span class="text-zinc-700">—</span>
                            @endif
                        </td>
                        @endforeach
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif

    @endif {{-- end if $period --}}
</div>
@endsection

@push('scripts')
@if($sessions->count() > 1 && !empty($chartData['labels']))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function() {
    const labels   = @json($chartData['labels']);
    const datasets = @json($chartData['datasets']);
    const params   = @json(collect($parameters)->map(fn($p) => $p['label']));

    const colors = [
        '#ef4444','#f97316','#eab308','#22c55e',
        '#06b6d4','#8b5cf6','#ec4899'
    ];

    const paramKeys = Object.keys(datasets);
    const visible   = {};
    paramKeys.forEach(k => visible[k] = true);

    const chartDatasets = paramKeys.map((key, i) => ({
        label:           params[key] || key,
        data:            datasets[key],
        borderColor:     colors[i % colors.length],
        backgroundColor: colors[i % colors.length] + '22',
        borderWidth:     2,
        pointRadius:     4,
        pointHoverRadius:6,
        tension:         0.4,
        spanGaps:        true,
    }));

    const ctx = document.getElementById('trendChart').getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: { labels, datasets: chartDatasets },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: { mode: 'index', intersect: false },
            plugins: {
                legend: { display: false },
                tooltip: {
                    backgroundColor: '#18181b',
                    borderColor: '#3f3f46',
                    borderWidth: 1,
                    titleColor: '#fff',
                    bodyColor: '#a1a1aa',
                }
            },
            scales: {
                x: { grid: { color: '#27272a' }, ticks: { color: '#71717a', font: { size: 11 } } },
                y: { grid: { color: '#27272a' }, ticks: { color: '#71717a', font: { size: 11 } }, beginAtZero: true }
            }
        }
    });

    // Custom legend
    const legend = document.getElementById('chart-legend');
    chartDatasets.forEach((ds, i) => {
        const btn = document.createElement('button');
        btn.className = 'flex items-center gap-1.5 text-xs px-2 py-1 rounded-lg border border-zinc-700 text-zinc-300 hover:text-white transition-all';
        btn.innerHTML = `<span class="w-2.5 h-2.5 rounded-full flex-shrink-0" style="background:${colors[i % colors.length]}"></span>${ds.label}`;
        btn.addEventListener('click', () => {
            const meta = chart.getDatasetMeta(i);
            meta.hidden = !meta.hidden;
            btn.style.opacity = meta.hidden ? '0.4' : '1';
            chart.update();
        });
        legend.appendChild(btn);
    });
})();
</script>
@endif
@endpush
