@extends('layouts.app')
@section('title', 'Dashboard Kebugaran')

@push('styles')
<style>
.ring-progress { transition: stroke-dashoffset 1.2s cubic-bezier(.4,0,.2,1); }
.param-card    { transition: border-color .2s, box-shadow .2s; }
.param-card:hover { border-color: #52525b; box-shadow: 0 0 0 1px #52525b; }
.spectrum-bar  { background: linear-gradient(to right, #ef4444 0%, #ef4444 25%, #f59e0b 25%, #f59e0b 50%, #22c55e 50%, #22c55e 75%, #10b981 75%, #10b981 100%); }
</style>
@endpush

@section('content')
@php
    $catColorMap = [
        'sangat_baik' => ['text'=>'text-emerald-400','bg'=>'bg-emerald-900/40','border'=>'border-emerald-700','hex'=>'#10b981','label'=>'Sangat Baik'],
        'baik'        => ['text'=>'text-green-400',   'bg'=>'bg-green-900/40',  'border'=>'border-green-700',  'hex'=>'#22c55e','label'=>'Baik'],
        'cukup'       => ['text'=>'text-amber-400',   'bg'=>'bg-amber-900/40',  'border'=>'border-amber-700',  'hex'=>'#f59e0b','label'=>'Cukup'],
        'kurang'      => ['text'=>'text-red-400',     'bg'=>'bg-red-900/40',    'border'=>'border-red-700',    'hex'=>'#ef4444','label'=>'Kurang'],
    ];
    $scoreColor = $catColorMap[$totalScore['category']] ?? $catColorMap['kurang'];
@endphp

<div class="min-h-screen bg-zinc-950 text-white">

    {{-- ══ HEADER ══ --}}
    <div class="border-b border-zinc-800/60 bg-zinc-950/80 backdrop-blur sticky top-0 z-30 px-4 lg:px-8 py-4">
        <div class="flex items-center justify-between gap-4">
            <div>
                <h1 class="text-lg lg:text-xl font-black tracking-tight text-white leading-none">
                    DASHBOARD <span class="text-red-500">STAR JASMANI</span>
                </h1>
                <p class="text-zinc-500 text-xs mt-0.5">Monitoring Kebugaran &amp; Komposisi Tubuh</p>
            </div>
            <div class="flex items-center gap-3 shrink-0">
                {{-- Tanggal --}}
                <div class="hidden sm:flex items-center gap-2 bg-zinc-900 border border-zinc-800 rounded-xl px-3 py-2">
                    <i class="fa-regular fa-calendar text-red-500 text-xs"></i>
                    <div class="text-right">
                        <p class="text-white text-xs font-bold leading-none">{{ now()->isoFormat('D MMM YYYY') }}</p>
                        <p class="text-zinc-500 text-[10px] leading-none mt-0.5">{{ now()->isoFormat('dddd') }}</p>
                    </div>
                </div>
                {{-- Period switcher --}}
                @if($periods->count() > 1)
                <form method="GET">
                    <select name="period_id" onchange="this.form.submit()"
                        class="bg-zinc-900 border border-zinc-700 text-white text-xs rounded-xl px-3 py-2 focus:ring-2 focus:ring-red-800 focus:outline-none">
                        @foreach($periods as $p)
                        <option value="{{ $p->id }}" {{ ($period && $period->id == $p->id) ? 'selected' : '' }}>{{ $p->name }}</option>
                        @endforeach
                    </select>
                </form>
                @endif
            </div>
        </div>
    </div>

    <div class="p-4 lg:p-8">

    @if(!$period)
    {{-- ── EMPTY STATE ── --}}
    <div class="flex flex-col items-center justify-center py-32 text-center">
        <div class="w-20 h-20 rounded-full bg-zinc-900 flex items-center justify-center mb-5">
            <i class="fa-solid fa-heart-pulse text-3xl text-zinc-700"></i>
        </div>
        <h2 class="text-zinc-300 font-bold text-lg">Belum Ada Data Kebugaran</h2>
        <p class="text-zinc-600 text-sm mt-2 max-w-sm">Coach kamu belum membuat periode latihan. Hubungi coach untuk memulai program kebugaran.</p>
        <a href="https://wa.me/6285603875675" target="_blank"
            class="mt-6 inline-flex items-center gap-2 bg-green-800 hover:bg-green-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-all">
            <i class="fa-brands fa-whatsapp"></i> Hubungi Coach
        </a>
    </div>
    @else

    {{-- ══ ROW 1: PROFIL + SKOR + RINGKASAN ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-5">

        {{-- PROFIL --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-user text-red-500 text-xs"></i>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Profil</p>
            </div>
            <div class="flex items-start gap-4">
                {{-- Avatar --}}
                <div class="w-16 h-16 rounded-2xl bg-linear-to-br from-red-800 to-red-950 flex items-center justify-center text-white font-black text-2xl shrink-0 border border-red-700/40">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-white font-black text-base leading-tight truncate">{{ auth()->user()->name }}</p>
                    <p class="text-zinc-500 text-xs mt-0.5">{{ ucfirst($athlete->gender === 'pria' ? 'Laki-laki' : 'Perempuan') }}</p>
                </div>
            </div>
            <div class="mt-4 space-y-2 text-xs">
                @if($athlete->birth_date)
                <div class="flex justify-between">
                    <span class="text-zinc-500">Usia</span>
                    <span class="text-white font-bold">{{ $athlete->birth_date->age }} Tahun</span>
                </div>
                @endif
                @if($athlete->height_cm)
                <div class="flex justify-between">
                    <span class="text-zinc-500">Tinggi Badan</span>
                    <span class="text-white font-bold">{{ $athlete->height_cm }} cm</span>
                </div>
                @endif
                @if($athlete->weight_kg)
                <div class="flex justify-between">
                    <span class="text-zinc-500">Berat Badan</span>
                    <span class="text-white font-bold">{{ $athlete->weight_kg }} kg</span>
                </div>
                @endif
                <div class="flex justify-between">
                    <span class="text-zinc-500">Periode</span>
                    <span class="text-white font-bold truncate max-w-30 text-right">{{ $period->name }}</span>
                </div>
                @if($sessions->last())
                <div class="flex justify-between pt-1 border-t border-zinc-800">
                    <span class="text-zinc-500">Tanggal Tes</span>
                    <span class="text-white font-bold">{{ $sessions->last()->date->isoFormat('D MMM Y') }}</span>
                </div>
                @endif
            </div>
        </div>

        {{-- SKOR TOTAL --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5 flex flex-col items-center justify-center">
            <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400 mb-5">Skor Kebugaran Total</p>
            <div class="relative w-44 h-44">
                <svg class="w-full h-full -rotate-90" viewBox="0 0 120 120">
                    <circle cx="60" cy="60" r="50" fill="none" stroke="#27272a" stroke-width="11"/>
                    <circle cx="60" cy="60" r="50" fill="none"
                        stroke="{{ $scoreColor['hex'] }}"
                        stroke-width="11"
                        stroke-linecap="round"
                        stroke-dasharray="{{ round(2 * M_PI * 50, 2) }}"
                        stroke-dashoffset="{{ round(2 * M_PI * 50 * (1 - $totalScore['score'] / 100), 2) }}"
                        class="ring-progress"/>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <span class="text-5xl font-black text-white leading-none">{{ $totalScore['score'] }}</span>
                    <span class="text-xs font-black uppercase tracking-widest mt-2 {{ $scoreColor['text'] }}">
                        {{ $scoreColor['label'] }}
                    </span>
                </div>
            </div>
            <p class="text-zinc-500 text-xs mt-4 text-center leading-relaxed">
                @php
                    $motivasi = [
                        'sangat_baik' => 'Luar biasa! Pertahankan kondisi optimal ini.',
                        'baik'        => 'Pertahankan pola latihan dan gaya hidup sehat!',
                        'cukup'       => 'Bagus! Konsistensi akan membawamu ke level berikutnya.',
                        'kurang'      => 'Mulai dari sini — setiap langkah kecil itu berarti!',
                    ];
                @endphp
                {{ $motivasi[$totalScore['category']] }}
            </p>
        </div>

        {{-- RINGKASAN --}}
        <div class="bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-chart-bar text-red-500 text-xs"></i>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Ringkasan</p>
            </div>
            <div class="space-y-2">
                @foreach($parameters as $key => $param)
                @php $s = $latestScores[$key] ?? null; $cc = $s ? ($catColorMap[$s['category']] ?? $catColorMap['kurang']) : null; @endphp
                <div class="flex items-center justify-between py-1.5 border-b border-zinc-800/60 last:border-0">
                    <div class="flex items-center gap-2.5">
                        <i class="fa-solid {{ $param['icon'] }} text-zinc-500 text-xs w-3.5 text-center"></i>
                        <span class="text-zinc-300 text-xs font-medium">{{ $param['label'] }}</span>
                    </div>
                    @if($s && $cc)
                    <span class="text-[10px] font-black px-2 py-0.5 rounded-full {{ $cc['bg'] }} {{ $cc['text'] }}">
                        {{ $cc['label'] }}
                    </span>
                    @else
                    <span class="text-[10px] text-zinc-600">—</span>
                    @endif
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══ ROW 2: PARAMETER CARDS ══ --}}
    @if(count($latestScores))
    <div class="mb-2">
        <p class="text-[10px] font-black uppercase tracking-widest text-zinc-500 mb-3">Parameter Terbaru</p>
    </div>
    <div class="grid grid-cols-2 sm:grid-cols-4 lg:grid-cols-4 xl:grid-cols-8 gap-3 mb-5">
        @foreach($parameters as $key => $param)
        @php
            $s  = $latestScores[$key] ?? null;
            $cc = $s ? ($catColorMap[$s['category']] ?? $catColorMap['kurang']) : null;
            $pct = $s ? min(max($s['percentage'], 2), 98) : 0;

            // Range labels for bar
            $rangeLabels = [
                'bmi'             => ['10','18.5','25','30'],
                'komposisi_otot'  => [$athlete->gender==='pria'?'35%':'30%', $athlete->gender==='pria'?'40%':'35%', $athlete->gender==='pria'?'45%':'40%'],
                'komposisi_lemak' => [$athlete->gender==='pria'?'6%':'14%', $athlete->gender==='pria'?'18%':'25%', $athlete->gender==='pria'?'25%':'32%'],
                'push_up'         => [$athlete->gender==='pria'?'15':'8', $athlete->gender==='pria'?'25':'15', $athlete->gender==='pria'?'40':'25'],
                'sit_up'          => [$athlete->gender==='pria'?'20':'15', $athlete->gender==='pria'?'30':'25', $athlete->gender==='pria'?'45':'40'],
                'squat'           => [$athlete->gender==='pria'?'20':'15', $athlete->gender==='pria'?'35':'25', $athlete->gender==='pria'?'50':'40'],
                'sit_and_reach'   => [$athlete->gender==='pria'?'20':'23', $athlete->gender==='pria'?'30':'33', $athlete->gender==='pria'?'40':'43'],
                'balke'           => [$athlete->gender==='pria'?'36.2':'28.6', $athlete->gender==='pria'?'43.9':'36.3', $athlete->gender==='pria'?'51.6':'44.2'],
            ];
            $rl = $rangeLabels[$key] ?? [];
        @endphp
        <div class="param-card bg-zinc-900 border border-zinc-800 rounded-2xl p-4 flex flex-col">
            {{-- Header --}}
            <div class="flex items-start justify-between mb-2">
                <div class="flex items-center gap-2">
                    <i class="fa-solid {{ $param['icon'] }} text-red-500 text-xs"></i>
                    <p class="text-[10px] font-black uppercase tracking-wider text-zinc-400 leading-tight">{{ $param['label'] }}</p>
                </div>
            </div>

            {{-- Value --}}
            @if($s)
            <div class="mb-1">
                <span class="text-3xl font-black text-white leading-none">{{ $s['value'] }}</span>
                @if($param['unit'])<span class="text-xs text-zinc-500 ml-0.5">{{ $param['unit'] }}</span>@endif
            </div>
            <span class="inline-block text-[10px] font-black px-2 py-0.5 rounded-full mb-3 {{ $cc['bg'] }} {{ $cc['text'] }} w-fit">
                {{ $cc['label'] }}
            </span>

            {{-- Spectrum bar --}}
            <div class="mt-auto">
                <div class="relative mb-1.5 pt-2">
                    {{-- pointer --}}
                    <div class="absolute top-0 z-10" style="left: calc({{ $pct }}% - 4px)">
                        <div class="w-0 h-0 border-l-4 border-r-4 border-t-[5px] border-l-transparent border-r-transparent" style="border-top-color: white"></div>
                    </div>
                    {{-- bar --}}
                    <div class="spectrum-bar h-2 rounded-full"></div>
                </div>
                {{-- range labels --}}
                @if(count($rl) >= 3)
                <div class="flex justify-between text-[9px] text-zinc-600 mt-0.5">
                    @foreach($rl as $rlv)
                    <span>{{ $rlv }}</span>
                    @endforeach
                </div>
                @endif
                <p class="text-[9px] text-zinc-600 mt-1">Rentang {{ $cc['label'] }}: {{ $s['range'] ?? '—' }}</p>
            </div>
            @else
            <div class="flex-1 flex items-center justify-center">
                <p class="text-zinc-700 text-sm">Belum diukur</p>
            </div>
            @endif
        </div>
        @endforeach
    </div>
    @endif

    {{-- ══ ROW 3: GRAFIK + REKOMENDASI ══ --}}
    <div class="grid grid-cols-1 lg:grid-cols-5 gap-4 mb-5">

        {{-- GRAFIK PERKEMBANGAN --}}
        <div class="lg:col-span-3 bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Grafik Perkembangan</p>
                    <p class="text-zinc-600 text-xs mt-0.5">Tren nilai dari sesi ke sesi</p>
                </div>
                <div id="chart-legend" class="flex flex-wrap gap-1.5 justify-end max-w-55"></div>
            </div>
            @if($sessions->count() > 1 && !empty($chartData['labels']))
            <div class="relative h-56">
                <canvas id="trendChart"></canvas>
            </div>
            @else
            <div class="h-56 flex items-center justify-center text-zinc-700 flex-col gap-2">
                <i class="fa-solid fa-chart-line text-3xl"></i>
                <p class="text-sm">Minimal 2 sesi untuk menampilkan grafik</p>
            </div>
            @endif
        </div>

        {{-- REKOMENDASI --}}
        <div class="lg:col-span-2 bg-zinc-900 border border-zinc-800 rounded-2xl p-5">
            <div class="flex items-center gap-2 mb-4">
                <i class="fa-solid fa-lightbulb text-red-500 text-xs"></i>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Rekomendasi</p>
            </div>
            @php
            $recs = [];

            // Rekap parameter berdasar kategori
            $paramBaik    = [];
            $paramCukup   = [];
            $paramKurang  = [];
            foreach($latestScores as $key => $s) {
                $label = $parameters[$key]['label'] ?? $key;
                match($s['category']) {
                    'sangat_baik', 'baik' => $paramBaik[]   = $label,
                    'cukup'               => $paramCukup[]  = $label,
                    default               => $paramKurang[] = $label,
                };
            }

            // Skor total
            if($totalScore['score'] >= 85) {
                $recs[] = ['icon'=>'fa-circle-check','color'=>'text-emerald-400','title'=>'Pertahankan!','desc'=>'Kondisi kebugaran kamu sudah sangat baik. Jaga konsistensi latihan.'];
            } elseif($totalScore['score'] >= 65) {
                $recs[] = ['icon'=>'fa-thumbs-up','color'=>'text-green-400','title'=>'Progres Bagus!','desc'=>'Skor keseluruhan baik. Tingkatkan sedikit lagi untuk mencapai level Sangat Baik.'];
            } else {
                $recs[] = ['icon'=>'fa-bullseye','color'=>'text-amber-400','title'=>'Tetap Semangat!','desc'=>'Konsistensi adalah kunci. Latihan rutin 3x seminggu akan meningkatkan skor kamu.'];
            }

            // Rekomendasi per kelemahan
            $recsMap = [
                'Komposisi Otot'  => ['icon'=>'fa-dumbbell',     'color'=>'text-blue-400',   'title'=>'Tingkatkan Latihan Kekuatan','desc'=>'Tambahkan latihan beban 2–3x seminggu untuk meningkatkan massa otot.'],
                'Komposisi Lemak' => ['icon'=>'fa-fire',          'color'=>'text-orange-400', 'title'=>'Kurangi Lemak Tubuh','desc'=>'Kombinasikan kardio 30 menit/hari dengan diet seimbang rendah lemak jenuh.'],
                'Push Up'         => ['icon'=>'fa-hand-fist',     'color'=>'text-purple-400', 'title'=>'Latih Kekuatan Otot Atas','desc'=>'Lakukan push up bertahap mulai dari knee push up hingga full push up.'],
                'Sit Up'          => ['icon'=>'fa-person',        'color'=>'text-yellow-400', 'title'=>'Perkuat Core','desc'=>'Latihan plank dan sit up 3 set/hari untuk memperkuat otot perut.'],
                'Squat'           => ['icon'=>'fa-person-walking','color'=>'text-pink-400',   'title'=>'Kuatkan Otot Kaki','desc'=>'Tambahkan squat dan lunges dalam rutinitas harian sebanyak 3 set.'],
                'Sit & Reach'     => ['icon'=>'fa-arrows-up-down','color'=>'text-cyan-400',   'title'=>'Tingkatkan Fleksibilitas','desc'=>'Lakukan peregangan (stretching) rutin 10–15 menit setiap hari.'],
                'Balke (VO₂max)'  => ['icon'=>'fa-lungs',         'color'=>'text-teal-400',   'title'=>'Tingkatkan Kapasitas Aerobik','desc'=>'Lari atau jalan cepat 30 menit, 3–4x seminggu untuk meningkatkan VO₂max.'],
                'BMI'             => ['icon'=>'fa-weight-scale',  'color'=>'text-red-400',    'title'=>'Jaga Berat Badan Ideal','desc'=>'Konsumsi makanan bergizi seimbang dan pantau asupan kalori harian.'],
            ];

            foreach(array_merge($paramKurang, $paramCukup) as $lbl) {
                if(isset($recsMap[$lbl]) && count($recs) < 4) {
                    $recs[] = $recsMap[$lbl];
                }
            }

            // Jaga pola hidup (selalu muncul kalau ada slot)
            if(count($recs) < 4) {
                $recs[] = ['icon'=>'fa-heart','color'=>'text-rose-400','title'=>'Jaga Pola Hidup Sehat','desc'=>'Tidur cukup 7–8 jam, konsumsi air putih minimal 2 liter/hari, dan kelola stres.'];
            }
            @endphp
            <div class="space-y-3">
                @foreach($recs as $rec)
                <div class="flex items-start gap-3 p-3 rounded-xl bg-zinc-800/50">
                    <div class="w-7 h-7 rounded-full bg-zinc-800 flex items-center justify-center shrink-0 mt-0.5">
                        <i class="fa-solid {{ $rec['icon'] }} text-xs {{ $rec['color'] }}"></i>
                    </div>
                    <div>
                        <p class="text-sm font-bold {{ $rec['color'] }} leading-tight">{{ $rec['title'] }}</p>
                        <p class="text-zinc-500 text-[11px] mt-0.5 leading-relaxed">{{ $rec['desc'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    {{-- ══ ROW 4: RIWAYAT SESI ══ --}}
    @if($sessions->count())
    <div class="bg-zinc-900 border border-zinc-800 rounded-2xl overflow-hidden">
        <div class="px-5 py-4 border-b border-zinc-800 flex items-center justify-between">
            <div>
                <p class="text-[10px] font-black uppercase tracking-widest text-zinc-400">Riwayat Sesi</p>
                <p class="text-zinc-600 text-xs mt-0.5">{{ $sessions->count() }} sesi dalam periode {{ $period->name }}</p>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm min-w-150">
                <thead>
                    <tr class="border-b border-zinc-800 text-zinc-500 text-[10px] uppercase tracking-widest">
                        <th class="px-5 py-3 text-left">Sesi</th>
                        <th class="px-5 py-3 text-left">Tanggal</th>
                        @foreach($parameters as $key => $param)
                        <th class="px-3 py-3 text-center">{{ $param['label'] }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-800/50">
                    @foreach($sessions->sortByDesc('session_number') as $sess)
                    <tr class="hover:bg-zinc-800/30 transition-colors">
                        <td class="px-5 py-3 font-black text-white">#{{ $sess->session_number }}</td>
                        <td class="px-5 py-3 text-zinc-400 text-xs">{{ $sess->date->isoFormat('D MMM Y') }}</td>
                        @foreach(array_keys($parameters) as $key)
                        @php
                            $sc  = $sess->scoreFor($key);
                            $cat = $sc ? \App\Services\KebugaranScoring::category($key, (float)$sc->value, $athlete->gender) : null;
                            $cc2 = $cat ? ($catColorMap[$cat] ?? $catColorMap['kurang']) : null;
                        @endphp
                        <td class="px-3 py-3 text-center">
                            @if($sc)
                            <span class="font-bold text-xs {{ $cc2['text'] }}">
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
    </div>{{-- /p-4 lg:p-8 --}}
</div>
@endsection

@push('scripts')
@if(isset($period) && $period && $sessions->count() > 1 && !empty($chartData['labels']))
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>
<script>
(function() {
    const labels   = @json($chartData['labels']);
    const datasets = @json($chartData['datasets']);
    const params   = @json(collect($parameters)->map(fn($p) => $p['label']));

    const palette = ['#ef4444','#f97316','#eab308','#22c55e','#06b6d4','#8b5cf6','#ec4899','#10b981'];
    const keys    = Object.keys(datasets);

    const chartDatasets = keys.map((key, i) => ({
        label:           params[key] || key,
        data:            datasets[key],
        borderColor:     palette[i % palette.length],
        backgroundColor: palette[i % palette.length] + '18',
        borderWidth:     2,
        pointRadius:     4,
        pointHoverRadius: 6,
        tension:         0.35,
        spanGaps:        true,
        fill:            false,
    }));

    const ctx   = document.getElementById('trendChart').getContext('2d');
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
                    padding: 10,
                    callbacks: {
                        label: ctx => ` ${ctx.dataset.label}: ${ctx.parsed.y}`
                    }
                }
            },
            scales: {
                x: { grid: { color: '#27272a' }, ticks: { color: '#71717a', font: { size: 10 } } },
                y: { grid: { color: '#27272a' }, ticks: { color: '#71717a', font: { size: 10 } }, beginAtZero: true }
            }
        }
    });

    // Custom legend
    const legendEl = document.getElementById('chart-legend');
    chartDatasets.forEach((ds, i) => {
        const btn = document.createElement('button');
        btn.className = 'flex items-center gap-1 text-[10px] px-2 py-1 rounded-lg border border-zinc-700 text-zinc-400 hover:text-white transition-all';
        btn.innerHTML = `<span class="w-2 h-2 rounded-full shrink-0" style="background:${palette[i % palette.length]}"></span>${ds.label}`;
        btn.addEventListener('click', () => {
            const meta = chart.getDatasetMeta(i);
            meta.hidden = !meta.hidden;
            btn.style.opacity = meta.hidden ? '0.35' : '1';
            chart.update();
        });
        legendEl.appendChild(btn);
    });
})();
</script>
@endif
@endpush
