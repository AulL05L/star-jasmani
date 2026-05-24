@extends('layouts.app')
@section('title', 'Performa — ' . $athlete->user->name)

@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-6">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Performa Atlet</p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">
                {{ $athlete->user->name }}
            </h1>
            <p class="text-gray-500 text-sm mt-1">
                {{ $athlete->target_institution ?? '—' }} ·
                {{ $athlete->batch ?? '—' }} ·
                {{ $athlete->gender === 'pria' ? 'Putra' : 'Putri' }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.athletes.show', $athlete) }}"
                class="flex items-center gap-2 px-4 py-2 rounded-xl border border-gray-800 text-gray-400 hover:text-white text-xs font-bold uppercase tracking-wider transition-all">
                <i class="fa-solid fa-user text-xs"></i> Profil
            </a>
            <a href="{{ route('admin.athletes.index') }}"
                class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
            </a>
        </div>
    </div>

    @if($scores->isEmpty())
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-16 text-center">
            <i class="fa-solid fa-chart-bar text-gray-700 text-4xl mb-4"></i>
            <p class="text-white font-bold text-lg mb-2">Belum Ada Data Penilaian</p>
            <p class="text-gray-500 text-sm mb-6">Input nilai samapta terlebih dahulu untuk melihat performa.</p>
            <a href="{{ route('admin.samapta.create', ['athlete_id' => $athlete->id]) }}"
                class="inline-flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold py-3 px-6 rounded-xl transition-all text-sm">
                <i class="fa-solid fa-plus"></i> Input Nilai Sekarang
            </a>
        </div>
    @else

    {{-- Stats Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-6">
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">Nilai Terakhir</p>
            <p class="text-4xl font-black text-red-500">{{ number_format($latestScore->score_final, 1) }}</p>
            @php
                $gradeBg = match($latestScore->grade) {
                    'A' => 'bg-green-900/50 text-green-400', 'B' => 'bg-blue-900/50 text-blue-400',
                    'C' => 'bg-yellow-900/50 text-yellow-400', default => 'bg-red-900/50 text-red-400'
                };
            @endphp
            <span class="inline-block mt-1 px-2 py-0.5 rounded-full text-[10px] font-black {{ $gradeBg }}">
                Grade {{ $latestScore->grade }}
            </span>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">Jasmani Terakhir</p>
            <p class="text-4xl font-black text-white">{{ number_format($latestScore->score_ukg_avg ?? 0, 1) }}</p>
            <p class="text-gray-600 text-[10px] mt-1">Bobot {{ $latestScore->snapshot_ukg_weight ? round($latestScore->snapshot_ukg_weight * 100) : 80 }}%</p>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">Total Parameter</p>
            <p class="text-4xl font-black text-white">{{ $scores->count() }}</p>
            <p class="text-gray-600 text-[10px] mt-1">Sesi penilaian</p>
        </div>
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">BMI</p>
            @if($latestBmi)
                @php
                    $bmiColor = match($latestBmi->bmi_status) {
                        'Normal' => 'text-green-400', 'Kurang' => 'text-blue-400',
                        'Gemuk'  => 'text-yellow-400', default => 'text-red-400'
                    };
                @endphp
                <p class="text-4xl font-black {{ $bmiColor }}">{{ $latestBmi->bmi_value }}</p>
                <p class="text-gray-600 text-[10px] mt-1">{{ $latestBmi->bmi_status }}</p>
            @else
                <p class="text-4xl font-black text-gray-700">—</p>
            @endif
        </div>
    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 mb-6">

        {{-- Grafik Nilai Akhir --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-white font-bold text-sm uppercase tracking-widest">Tren Nilai Akhir</h2>
                    <p class="text-gray-600 text-xs mt-0.5">Per parameter sesi</p>
                </div>
                <div class="flex items-center gap-3 text-xs text-gray-500">
                    <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-red-600 inline-block"></span> Nilai Akhir</span>
                    <span class="flex items-center gap-1"><span class="w-3 h-0.5 bg-gray-600 inline-block"></span> Jasmani</span>
                </div>
            </div>
            <canvas id="chartNilaiAkhir" height="160"></canvas>
        </div>

        {{-- Grafik Komponen Jasmani B --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <div class="mb-4">
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Komponen Jasmani B</h2>
                <p class="text-gray-600 text-xs mt-0.5">Push-up · Sit-up · {{ $athlete->upper_body_test }} · Shuttle</p>
            </div>
            <canvas id="chartJasmaniB" height="160"></canvas>
        </div>
    </div>

    {{-- 6 Grafik Per Item --}}
    <div class="grid grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
        @php
            $chartItems = [
                ['id' => 'chartLari',    'label' => 'Lari 12 Menit',              'data' => $nilaiLari,    'color' => '#991b1b'],
                ['id' => 'chartPushup',  'label' => 'Push-Up',                    'data' => $nilaiPushup,  'color' => '#b91c1c'],
                ['id' => 'chartSitup',   'label' => 'Sit-Up',                     'data' => $nilaiSitup,   'color' => '#dc2626'],
                ['id' => 'chartPullup',  'label' => $athlete->upper_body_test,    'data' => $nilaiPullup,  'color' => '#ef4444'],
                ['id' => 'chartShuttle', 'label' => 'Shuttle Run',                'data' => $nilaiShuttle, 'color' => '#f87171'],
                ['id' => 'chartRenang',  'label' => 'Renang',                     'data' => $nilaiRenang,  'color' => '#2563eb'],
            ];
        @endphp
        @foreach($chartItems as $item)
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-4">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-white font-bold text-xs uppercase tracking-widest">{{ $item['label'] }}</h3>
                @php $lastVal = $item['data']->last(); @endphp
                @if($lastVal)
                    <span class="text-sm font-black" style="color: {{ $item['color'] }}">{{ $lastVal }}</span>
                @endif
            </div>
            <canvas id="{{ $item['id'] }}" height="100"></canvas>
        </div>
        @endforeach
    </div>

    {{-- Breakdown Terakhir + Riwayat --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">

        {{-- Breakdown Komponen Terakhir --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-5">
            <h2 class="text-white font-bold text-sm uppercase tracking-widest mb-2">Breakdown Terakhir</h2>
            <p class="text-gray-600 text-xs mb-4">Parameter {{ $latestScore->parameter_ke }} · {{ $latestScore->assessment_date->format('d M Y') }}</p>

            @php
                $komponenList = [
                    ['label' => 'Lari',               'score' => $latestScore->score_lari,    'color' => 'bg-red-700'],
                    ['label' => 'Push-Up',             'score' => $latestScore->score_pushup,  'color' => 'bg-orange-700'],
                    ['label' => 'Sit-Up',              'score' => $latestScore->score_situp,   'color' => 'bg-yellow-700'],
                    ['label' => $athlete->upper_body_test, 'score' => $latestScore->score_pullup, 'color' => 'bg-green-700'],
                    ['label' => 'Shuttle',             'score' => $latestScore->score_shuttle, 'color' => 'bg-blue-700'],
                    ['label' => 'Renang',              'score' => $latestScore->score_renang,  'color' => 'bg-purple-700'],
                ];
            @endphp

            <div class="space-y-3">
                @foreach($komponenList as $k)
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <span class="text-gray-400 text-xs">{{ $k['label'] }}</span>
                        <span class="text-white font-black text-xs">{{ $k['score'] ?? '—' }}</span>
                    </div>
                    <div class="h-1.5 bg-gray-900 rounded-full overflow-hidden">
                        <div class="{{ $k['color'] }} h-full rounded-full" style="width: {{ $k['score'] ?? 0 }}%"></div>
                    </div>
                </div>
                @endforeach
            </div>

            <div class="mt-4 pt-4 border-t border-gray-800 flex items-center justify-between">
                <div class="text-center">
                    <p class="text-gray-600 text-[10px]">Jasmani A</p>
                    <p class="text-white font-black">{{ $latestScore->nilai_jasmani_a ?? '—' }}</p>
                </div>
                <span class="text-gray-700">+</span>
                <div class="text-center">
                    <p class="text-gray-600 text-[10px]">Jasmani B</p>
                    <p class="text-white font-black">{{ number_format($latestScore->nilai_jasmani_b ?? 0, 1) }}</p>
                </div>
                <span class="text-gray-700">=</span>
                <div class="text-center">
                    <p class="text-gray-600 text-[10px]">Jasmani</p>
                    <p class="text-red-500 font-black">{{ number_format($latestScore->nilai_total_jasmani ?? 0, 1) }}</p>
                </div>
            </div>
        </div>

        {{-- Riwayat Tabel --}}
        <div class="lg:col-span-2 bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                <h2 class="text-white font-bold text-sm uppercase tracking-widest">Riwayat Parameter</h2>
                <a href="{{ route('admin.samapta.create', ['athlete_id' => $athlete->id]) }}"
                    class="flex items-center gap-1 text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i> Input Nilai
                </a>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-5 py-3">Parameter</th>
                            <th class="text-center px-4 py-3">Tanggal</th>
                            <th class="text-center px-4 py-3">Jas A</th>
                            <th class="text-center px-4 py-3">Jas B</th>
                            <th class="text-center px-4 py-3">Renang</th>
                            <th class="text-center px-4 py-3">Nilai Akhir</th>
                            <th class="text-center px-4 py-3">Grade</th>
                            <th class="text-center px-4 py-3">PDF</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($scores->sortByDesc('parameter_ke') as $score)
                        @php
                            $isLatest = $score->id === $latestScore->id;
                            $gb = match($score->grade) {
                                'A' => 'bg-green-900/50 text-green-400', 'B' => 'bg-blue-900/50 text-blue-400',
                                'C' => 'bg-yellow-900/50 text-yellow-400', default => 'bg-red-900/50 text-red-400'
                            };
                        @endphp
                        <tr class="hover:bg-gray-900/50 transition-colors {{ $isLatest ? 'bg-red-900/5' : '' }}">
                            <td class="px-5 py-3">
                                <p class="text-white font-bold text-xs">Parameter {{ $score->parameter_ke }}</p>
                                @if($isLatest)<span class="text-[10px] text-red-500">● Terbaru</span>@endif
                            </td>
                            <td class="px-4 py-3 text-center text-gray-400 text-xs">{{ $score->assessment_date->format('d M Y') }}</td>
                            <td class="px-4 py-3 text-center text-gray-300 text-xs font-bold">{{ $score->nilai_jasmani_a ?? '—' }}</td>
                            <td class="px-4 py-3 text-center text-gray-300 text-xs font-bold">{{ $score->nilai_jasmani_b ? number_format($score->nilai_jasmani_b, 1) : '—' }}</td>
                            <td class="px-4 py-3 text-center text-blue-400 text-xs font-bold">{{ $score->score_renang ?? '—' }}</td>
                            <td class="px-4 py-3 text-center font-black text-red-500">{{ number_format($score->score_final, 1) }}</td>
                            <td class="px-4 py-3 text-center">
                                <span class="px-2 py-0.5 rounded-full text-xs font-black {{ $gb }}">{{ $score->grade }}</span>
                            </td>
                            <td class="px-4 py-3 text-center">
                                <a href="{{ route('admin.reports.samapta.pdf', $score) }}"
                                    class="w-7 h-7 bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 rounded-lg inline-flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-file-pdf text-xs"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @endif
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
const labels  = @json($parameterLabels);
const opts = {
    responsive: true,
    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#111', borderColor: '#374151', borderWidth: 1, titleColor: '#fff', bodyColor: '#9ca3af' } },
    scales: {
        x: { grid: { color: '#1f2937' }, ticks: { color: '#6b7280', font: { size: 10 } } },
        y: { min: 0, max: 100, grid: { color: '#1f2937' }, ticks: { color: '#6b7280', font: { size: 10 }, stepSize: 20 } }
    }
};

// Tren Nilai Akhir
new Chart(document.getElementById('chartNilaiAkhir'), {
    type: 'line',
    data: {
        labels,
        datasets: [
            { label: 'Nilai Akhir', data: @json($nilaiAkhir), borderColor: '#991b1b', backgroundColor: 'rgba(153,27,27,0.1)', borderWidth: 2, pointBackgroundColor: '#991b1b', pointRadius: 5, fill: true, tension: 0.4 },
            { label: 'Jasmani', data: @json($nilaiJasmani), borderColor: '#374151', borderWidth: 1.5, borderDash: [4,4], pointRadius: 3, fill: false, tension: 0.4 }
        ]
    },
    options: { ...opts, plugins: { ...opts.plugins, legend: { display: false } } }
});

// Komponen Jasmani B
new Chart(document.getElementById('chartJasmaniB'), {
    type: 'bar',
    data: {
        labels,
        datasets: [
            { label: 'Push-Up', data: @json($nilaiPushup), backgroundColor: '#991b1b', borderRadius: 4, borderSkipped: false },
            { label: 'Sit-Up',  data: @json($nilaiSitup),  backgroundColor: '#b91c1c', borderRadius: 4, borderSkipped: false },
            { label: '{{ $athlete->upper_body_test }}', data: @json($nilaiPullup), backgroundColor: '#dc2626', borderRadius: 4, borderSkipped: false },
            { label: 'Shuttle', data: @json($nilaiShuttle), backgroundColor: '#374151', borderRadius: 4, borderSkipped: false },
        ]
    },
    options: opts
});

// 6 grafik individual
const singleCharts = [
    { id: 'chartLari',    data: @json($nilaiLari),    color: '#991b1b' },
    { id: 'chartPushup',  data: @json($nilaiPushup),  color: '#b91c1c' },
    { id: 'chartSitup',   data: @json($nilaiSitup),   color: '#dc2626' },
    { id: 'chartPullup',  data: @json($nilaiPullup),  color: '#ef4444' },
    { id: 'chartShuttle', data: @json($nilaiShuttle), color: '#f87171' },
    { id: 'chartRenang',  data: @json($nilaiRenang),  color: '#2563eb' },
];

singleCharts.forEach(({ id, data, color }) => {
    new Chart(document.getElementById(id), {
        type: 'bar',
        data: {
            labels,
            datasets: [{ data, backgroundColor: color, borderRadius: 4, borderSkipped: false }]
        },
        options: { ...opts, scales: { ...opts.scales, y: { ...opts.scales.y, min: 0, max: 100 } } }
    });
});
</script>
@endpush