<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Laporan Samapta - {{ $samaptaScore->athlete->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            font-size: 9px;
            width: 100%;
        }
        .page { padding: 12px 15px; width: 740px; }

        /* ── HEADER ── */
        .header-table { width: 100%; background: #7f1d1d; border-radius: 8px; margin-bottom: 8px; }
        .header-table td { padding: 10px 18px; color: #ffffff; vertical-align: middle; }
        .header-brand { font-size: 17px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
        .header-brand span { color: #fca5a5; }
        .header-sub { font-size: 7.5px; color: #fca5a5; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px; }
        .header-name { font-size: 19px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; text-align: center; }
        .header-right-text { text-align: right; font-size: 8px; color: #fca5a5; line-height: 1.8; }

        /* ── TOP STATS (5 kolom) ── */
        .stats-table { width: 100%; border: 2px solid #7f1d1d; border-collapse: collapse; margin-bottom: 8px; }
        .stats-table td { padding: 8px 5px; text-align: center; border-right: 1px solid #e5e7eb; }
        .stats-table td:last-child { border-right: none; }
        .stat-label { font-size: 6.5px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 3px; }
        .stat-value { font-size: 24px; font-weight: 900; color: #7f1d1d; line-height: 1; margin-bottom: 3px; }
        .stat-value.dark { color: #1a1a1a; }
        .stat-value.white { color: #ffffff; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 50px; font-size: 7px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-red { background: #7f1d1d; color: #fff; }
        .badge-gray { background: #f3f4f6; color: #374151; }
        .badge-green { background: #14532d; color: #4ade80; }
        .badge-blue { background: #1e3a5f; color: #60a5fa; }
        .badge-yellow { background: #713f12; color: #facc15; }
        .badge-orange { background: #7c2d12; color: #fb923c; }
        .badge-dark-red { background: #450a0a; color: #f87171; }
        .badge-normal { background: #14532d; color: #4ade80; }
        .badge-kurang { background: #1e3a5f; color: #60a5fa; }
        .badge-gemuk { background: #713f12; color: #facc15; }
        .badge-obesitas { background: #450a0a; color: #f87171; }

        /* ── MAIN LAYOUT ── */
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table > tbody > tr > td { vertical-align: top; padding: 0; }
        .left-col { width: 34%; padding-right: 8px !important; }
        .right-col { width: 66%; }

        /* ── CARDS ── */
        .card { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 7px; width: 100%; }
        .card-header { background: #7f1d1d; padding: 5px 10px; font-size: 8px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 1.5px; text-align: center; }
        .card-table { width: 100%; border-collapse: collapse; }
        .card-table td { padding: 4px 8px; border-bottom: 1px solid #f9fafb; font-size: 8.5px; }
        .card-table tr:last-child td { border-bottom: none; }
        .card-key { background: #f9fafb; color: #6b7280; font-weight: 700; font-size: 7px; text-transform: uppercase; letter-spacing: 0.5px; width: 42%; border-right: 1px solid #f3f4f6; }
        .card-val { color: #1a1a1a; font-weight: 700; }

        /* ── JASMANI BREAKDOWN CARD ── */
        .jasmani-table { width: 100%; border-collapse: collapse; }
        .jasmani-table td { padding: 5px 8px; border-bottom: 1px solid #f9fafb; vertical-align: middle; font-size: 8.5px; }
        .jasmani-table tr:last-child td { border-bottom: none; }
        .bar-bg { background: #f3f4f6; border-radius: 3px; height: 8px; width: 100%; }
        .bar-fill-red  { background: #7f1d1d; border-radius: 3px; height: 8px; }
        .bar-fill-blue { background: #1d4ed8; border-radius: 3px; height: 8px; }
        .bar-fill-dark { background: #1a1a1a; border-radius: 3px; height: 8px; }

        /* ── TEST TABLE ── */
        .test-outer { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 7px; width: 100%; }
        .test-outer-header { background: #7f1d1d; padding: 5px 10px; font-size: 8px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 1.5px; text-align: center; }
        .test-inner { width: 100%; border-collapse: collapse; }
        .test-inner th { background: #1a1a1a; color: #fff; padding: 4px 6px; font-size: 7px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; text-align: center; }
        .test-inner th:first-child { text-align: left; }
        .test-inner td { padding: 4px 6px; border-bottom: 1px solid #f3f4f6; font-size: 8.5px; color: #374151; text-align: center; }
        .test-inner td:first-child { text-align: left; font-weight: 700; }
        .test-inner td.nilai-col { font-weight: 900; color: #7f1d1d; font-size: 11px; }
        .test-inner td.nilai-blue { font-weight: 900; color: #1d4ed8; font-size: 11px; }
        .test-inner tr.section-row td { background: #f9fafb; font-size: 7px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; padding: 3px 6px; }
        .test-inner tr.subtotal-row td { background: #fff7f7; font-weight: 900; }
        .test-inner tr.total-row td { background: #7f1d1d; color: #fff; font-weight: 900; font-size: 12px; }

        /* ── BAR CHART ── */
        .chart-outer { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 7px; }
        .chart-inner { padding: 8px 8px 4px; }
        .chart-bars-table { width: 100%; border-collapse: collapse; border-bottom: 2px solid #e5e7eb; }
        .chart-bars-table td { vertical-align: bottom; text-align: center; padding: 0 3px; }
        .bar-score-label { font-size: 7.5px; font-weight: 900; color: #7f1d1d; display: block; margin-bottom: 2px; }
        .bar-block { border-radius: 3px 3px 0 0; display: block; margin: 0 auto; width: 22px; }
        .chart-labels-table { width: 100%; border-collapse: collapse; margin-top: 3px; }
        .chart-labels-table td { text-align: center; font-size: 6.5px; color: #6b7280; font-weight: 700; text-transform: uppercase; padding: 0 1px; }

        /* ── FORMULA ── */
        .formula-box { background: #fff7f7; border: 1px solid #fca5a5; border-left: 4px solid #7f1d1d; border-radius: 4px; padding: 6px 10px; font-size: 8.5px; color: #374151; margin-bottom: 7px; line-height: 1.9; }

        /* ── FOOTER ── */
        .footer-table { width: 100%; border-collapse: collapse; border-top: 2px solid #7f1d1d; margin-top: 8px; }
        .footer-table td { vertical-align: bottom; padding-top: 8px; font-size: 7.5px; color: #9ca3af; line-height: 1.8; }
        .footer-brand { color: #7f1d1d; font-size: 10px; font-weight: 900; }
        .sig-line { width: 100px; border-bottom: 1px solid #1a1a1a; margin: 28px auto 4px; }
        .sig-name { text-align: center; font-size: 8px; font-weight: 700; color: #374151; }
        .sig-sub { text-align: center; font-size: 7px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

@php
    $s = $samaptaScore;
    $athlete = $s->athlete;

    // Nilai Jasmani A, B, Total
    $jasmaniA     = $s->nilai_jasmani_a     ?? $s->score_lari    ?? 0;
    $jasmaniB     = $s->nilai_jasmani_b     ?? $s->score_jasmani_b ?? 0;
    $nilaiJasmani = $s->nilai_total_jasmani ?? $s->score_ukg_avg  ?? 0;
    $nilaiRenang  = $s->score_renang ?? 0;
    $nilaiAkhir   = $s->score_final  ?? 0;

    // Bobot dari snapshot atau default
    $ukgPct    = $s->snapshot_ukg_weight    ? round($s->snapshot_ukg_weight * 100)    : 80;
    $renangPct = $s->snapshot_renang_weight ? round($s->snapshot_renang_weight * 100) : 20;

    // Grade badge
    $gradeBadge = match($s->grade) {
        'A' => 'badge-green', 'B' => 'badge-blue',
        'C' => 'badge-yellow', 'D' => 'badge-orange',
        default => 'badge-dark-red'
    };

    // BMI
    $bmi = null; $bmiStatus = null; $bmiBadge = null;
    if ($athlete->height_cm && $athlete->weight_kg) {
        $bmi = round($athlete->weight_kg / (($athlete->height_cm / 100) ** 2), 1);
        $bmiStatus = $bmi < 17 ? 'Kurang' : ($bmi < 25 ? 'Normal' : ($bmi < 30 ? 'Gemuk' : 'Obesitas'));
        $bmiBadge = match($bmiStatus) {
            'Normal' => 'badge-normal', 'Kurang' => 'badge-kurang',
            'Gemuk' => 'badge-gemuk', default => 'badge-obesitas'
        };
    }

    // Kategori Jasmani
    $kategoriJasmani = fn($v) => match(true) {
        $v >= 80 => 'TINGGI', $v >= 70 => 'BAIK',
        $v >= 60 => 'CUKUP', $v >= 50 => 'KURANG',
        default  => 'RENDAH'
    };
@endphp

    {{-- ══ HEADER ══ --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:28%">
                <div class="header-brand">STAR <span>JASMANI</span></div>
                <div class="header-sub">Digital Assessment System</div>
            </td>
            <td style="width:44%; text-align:center;">
                <div class="header-name">{{ $athlete->user->name }}</div>
                <div style="font-size:8px; color:#fca5a5; margin-top:3px; letter-spacing:1px; text-transform:uppercase;">
                    Laporan Hasil Penilaian Jasmani Kedinasan
                </div>
            </td>
            <td style="width:28%">
                <div class="header-right-text">
                    Dicetak: {{ now()->format('d M Y') }}<br>
                    Institusi: {{ $s->institution }}<br>
                    Sesi: {{ $s->session_label ?? 'Parameter '.$s->parameter_ke }}
                </div>
            </td>
        </tr>
    </table>

    {{-- ══ TOP STATS — 5 kolom: Nilai Akhir | Jasmani A | Jasmani B | Nilai Jasmani | Tanggal ══ --}}
    <table class="stats-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- Nilai Akhir --}}
            <td style="background:#fff7f7;">
                <div class="stat-label">Nilai Akhir</div>
                <div class="stat-value">{{ number_format($nilaiAkhir, 1) }}</div>
                <span class="badge {{ $gradeBadge }}">Grade {{ $s->grade }}</span>
            </td>
            {{-- Jasmani A --}}
            <td>
                <div class="stat-label">Jasmani A</div>
                <div class="stat-value dark">{{ number_format($jasmaniA, 0) }}</div>
                <span class="badge badge-gray">Lari</span>
            </td>
            {{-- Jasmani B --}}
            <td>
                <div class="stat-label">Jasmani B</div>
                <div class="stat-value dark">{{ number_format($jasmaniB, 1) }}</div>
                <span class="badge badge-gray">Push/Sit/Pull/Shuttle</span>
            </td>
            {{-- Nilai Jasmani --}}
            <td>
                <div class="stat-label">Nilai Jasmani</div>
                <div class="stat-value dark">{{ number_format($nilaiJasmani, 2) }}</div>
                <span class="badge badge-gray">Bobot {{ $ukgPct }}%</span>
            </td>
            {{-- Institusi --}}
            <td style="background:#7f1d1d;">
                <div class="stat-label" style="color:#fca5a5;">Institusi</div>
                <div class="stat-value white" style="font-size:16px; padding-top:4px;">{{ $s->institution }}</div>
                <span class="badge" style="background:#fff; color:#7f1d1d;">
                    {{ $s->assessment_date->format('d M Y') }}
                </span>
            </td>
        </tr>
    </table>

    {{-- ══ MAIN CONTENT ══ --}}
    <table class="main-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- ── LEFT COLUMN ── --}}
            <td class="left-col">

                {{-- Data Peserta --}}
                <div class="card">
                    <div class="card-header">Data Peserta</div>
                    <table class="card-table" cellpadding="0" cellspacing="0">
                        <tr><td class="card-key">Nama</td><td class="card-val">{{ $athlete->user->name }}</td></tr>
                        <tr><td class="card-key">Gender</td><td class="card-val">{{ Str::ucfirst($athlete->gender) }}</td></tr>
                        <tr><td class="card-key">Target</td><td class="card-val">{{ $athlete->target_institution ?? '—' }}</td></tr>
                        <tr><td class="card-key">Batch</td><td class="card-val">{{ $athlete->batch ?? '—' }}</td></tr>
                        <tr><td class="card-key">Tes Atas</td><td class="card-val">{{ $athlete->upper_body_test }}</td></tr>
                        <tr><td class="card-key">Coach</td><td class="card-val">{{ $s->coach->name }}</td></tr>
                    </table>
                </div>

                {{-- Data Fisik & BMI --}}
                @if($bmi)
                <div class="card">
                    <div class="card-header">Data Fisik & BMI</div>
                    <table class="card-table" cellpadding="0" cellspacing="0">
                        <tr><td class="card-key">Tinggi</td><td class="card-val">{{ $athlete->height_cm }} cm</td></tr>
                        <tr><td class="card-key">Berat</td><td class="card-val">{{ $athlete->weight_kg }} kg</td></tr>
                        <tr><td class="card-key">BMI</td><td class="card-val" style="color:#7f1d1d; font-size:12px; font-weight:900;">{{ $bmi }}</td></tr>
                        <tr><td class="card-key">Kategori</td><td class="card-val"><span class="badge {{ $bmiBadge }}">{{ $bmiStatus }}</span></td></tr>
                    </table>
                </div>
                @endif

                {{-- Ringkasan Data — Jasmani A, B, Jasmani, Renang, Nilai Akhir --}}
                <div class="card">
                    <div class="card-header">Ringkasan Data</div>
                    <table class="jasmani-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="width:38%; font-weight:700; font-size:8px; color:#374151;">Jasmani A (Lari)</td>
                            <td style="width:47%">
                                <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $jasmaniA }}%;"></div></div>
                            </td>
                            <td style="width:15%; text-align:right; font-weight:900; color:#7f1d1d; font-size:10px;">{{ number_format($jasmaniA, 0) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:700; font-size:8px; color:#374151;">Jasmani B (UKG)</td>
                            <td>
                                <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $jasmaniB }}%;"></div></div>
                            </td>
                            <td style="text-align:right; font-weight:900; color:#7f1d1d; font-size:10px;">{{ number_format($jasmaniB, 1) }}</td>
                        </tr>
                        <tr style="background:#fff7f7;">
                            <td style="font-weight:900; color:#7f1d1d; font-size:8.5px;">Nilai Jasmani</td>
                            <td>
                                <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $nilaiJasmani }}%; height:9px; border-radius:3px;"></div></div>
                            </td>
                            <td style="text-align:right; font-weight:900; color:#7f1d1d; font-size:11px;">{{ number_format($nilaiJasmani, 2) }}</td>
                        </tr>
                        <tr>
                            <td style="font-weight:700; font-size:8px; color:#1d4ed8;">Renang ({{ $renangPct }}%)</td>
                            <td>
                                <div class="bar-bg"><div class="bar-fill-blue" style="width:{{ $nilaiRenang }}%;"></div></div>
                            </td>
                            <td style="text-align:right; font-weight:900; color:#1d4ed8; font-size:10px;">{{ $nilaiRenang ?: '—' }}</td>
                        </tr>
                        <tr style="background:#7f1d1d;">
                            <td style="font-weight:900; color:#fff; font-size:9px;" colspan="2">Total Nilai Akhir</td>
                            <td style="text-align:right; font-weight:900; color:#fff; font-size:14px;">{{ number_format($nilaiAkhir, 1) }}</td>
                        </tr>
                    </table>
                </div>

            </td>

            {{-- ── RIGHT COLUMN ── --}}
            <td class="right-col">

                {{-- Tabel Hasil Test — dikelompokkan per Jasmani A, B, Renang --}}
                <div class="test-outer">
                    <div class="test-outer-header">Detail Hasil Test Jasmani</div>
                    <table class="test-inner" cellpadding="0" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width:28%; text-align:left;">Jenis Tes</th>
                                <th style="width:12%;">Hasil</th>
                                <th style="width:10%;">Satuan</th>
                                <th style="width:40%; text-align:left; padding-left:8px;">Progress</th>
                                <th style="width:10%;">Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            {{-- JASMANI A --}}
                            <tr class="section-row">
                                <td colspan="5">▸ Jasmani A — Lari</td>
                            </tr>
                            <tr>
                                <td>Lari 12 Menit</td>
                                <td>{{ $s->raw_lari_meter ?? '—' }}</td>
                                <td>Meter</td>
                                <td style="padding:4px 8px;">
                                    <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $s->score_lari ?? 0 }}%;"></div></div>
                                </td>
                                <td class="nilai-col">{{ $s->score_lari ?? '—' }}</td>
                            </tr>
                            <tr class="subtotal-row">
                                <td colspan="4" style="text-align:right; color:#7f1d1d; font-size:8px; padding-right:8px;">Jasmani A =</td>
                                <td class="nilai-col">{{ number_format($jasmaniA, 0) }}</td>
                            </tr>

                            {{-- JASMANI B --}}
                            <tr class="section-row">
                                <td colspan="5">▸ Jasmani B — Kekuatan & Kelincahan</td>
                            </tr>
                            <tr>
                                <td>Push Up</td>
                                <td>{{ $s->raw_pushup_reps ?? '—' }}</td>
                                <td>Reps</td>
                                <td style="padding:4px 8px;">
                                    <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $s->score_pushup ?? 0 }}%;"></div></div>
                                </td>
                                <td class="nilai-col">{{ $s->score_pushup ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td>Sit Up</td>
                                <td>{{ $s->raw_situp_reps ?? '—' }}</td>
                                <td>Reps</td>
                                <td style="padding:4px 8px;">
                                    <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $s->score_situp ?? 0 }}%;"></div></div>
                                </td>
                                <td class="nilai-col">{{ $s->score_situp ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td>{{ $athlete->upper_body_test }}</td>
                                <td>{{ $s->raw_pullup_reps ?? '—' }}</td>
                                <td>Reps</td>
                                <td style="padding:4px 8px;">
                                    <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $s->score_pullup ?? 0 }}%;"></div></div>
                                </td>
                                <td class="nilai-col">{{ $s->score_pullup ?? '—' }}</td>
                            </tr>
                            <tr>
                                <td>Shuttle Run</td>
                                <td>{{ $s->raw_shuttle_seconds ?? '—' }}</td>
                                <td>Detik</td>
                                <td style="padding:4px 8px;">
                                    <div class="bar-bg"><div class="bar-fill-red" style="width:{{ $s->score_shuttle ?? 0 }}%;"></div></div>
                                </td>
                                <td class="nilai-col">{{ $s->score_shuttle ?? '—' }}</td>
                            </tr>
                            <tr class="subtotal-row">
                                <td colspan="4" style="text-align:right; color:#7f1d1d; font-size:8px; padding-right:8px;">Jasmani B =</td>
                                <td class="nilai-col">{{ number_format($jasmaniB, 1) }}</td>
                            </tr>

                            {{-- RENANG --}}
                            <tr class="section-row">
                                <td colspan="5">▸ Renang (Bobot {{ $renangPct }}%)</td>
                            </tr>
                            <tr>
                                <td>Renang</td>
                                <td>{{ $s->raw_renang_seconds ?? '—' }}</td>
                                <td>Skor</td>
                                <td style="padding:4px 8px;">
                                    <div class="bar-bg"><div class="bar-fill-blue" style="width:{{ $nilaiRenang }}%;"></div></div>
                                </td>
                                <td class="nilai-blue">{{ $nilaiRenang ?: '—' }}</td>
                            </tr>

                            {{-- TOTAL --}}
                            <tr class="total-row">
                                <td colspan="4" style="text-align:left; letter-spacing:1px;">
                                    NILAI AKHIR {{ $s->institution }}
                                </td>
                                <td style="text-align:center; font-size:15px;">
                                    {{ number_format($nilaiAkhir, 2) }}
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                {{-- Bar Chart --}}
                <div class="chart-outer">
                    <div class="test-outer-header">Grafik Hasil Test</div>
                    <div class="chart-inner">
                        @php
                            $chartBars = [
                                ['label' => 'Renang',  'score' => $nilaiRenang,       'color' => '#1d4ed8'],
                                ['label' => 'Lari',    'score' => $s->score_lari,    'color' => '#7f1d1d'],
                                ['label' => 'Push Up', 'score' => $s->score_pushup,  'color' => '#991b1b'],
                                ['label' => 'Sit Up',  'score' => $s->score_situp,   'color' => '#b91c1c'],
                                ['label' => $athlete->upper_body_test, 'score' => $s->score_pullup, 'color' => '#dc2626'],
                                ['label' => 'Shuttle', 'score' => $s->score_shuttle, 'color' => '#ef4444'],
                            ];
                            $maxH = 55;
                        @endphp
                        <table class="chart-bars-table" cellpadding="0" cellspacing="0">
                            <tr style="height:{{ $maxH }}px; vertical-align:bottom;">
                                @foreach($chartBars as $bar)
                                <td>
                                    <span class="bar-score-label" style="color:{{ $bar['color'] }}">{{ $bar['score'] ?? '—' }}</span>
                                    <span class="bar-block" style="background:{{ $bar['color'] }}; height:{{ (($bar['score'] ?? 0) / 100) * $maxH }}px; min-height:2px;"></span>
                                </td>
                                @endforeach
                            </tr>
                        </table>
                        <table class="chart-labels-table" cellpadding="0" cellspacing="0">
                            <tr>
                                @foreach($chartBars as $bar)
                                <td>{{ $bar['label'] }}</td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>

                {{-- Formula --}}
                <div class="formula-box">
                    <strong style="color:#7f1d1d;">Formula Nilai Akhir {{ $s->institution }}:</strong><br>
                    Jasmani A &nbsp;&nbsp;= Nilai Lari &nbsp;&nbsp;= <strong>{{ number_format($jasmaniA, 0) }}</strong><br>
                    Jasmani B &nbsp;&nbsp;= Rata-rata (Push-up + Sit-up + {{ $athlete->upper_body_test }} + Shuttle) = <strong>{{ number_format($jasmaniB, 2) }}</strong><br>
                    Nilai Jasmani = Rata-rata (Jasmani A + Jasmani B) = <strong>{{ number_format($nilaiJasmani, 2) }}</strong><br>
                    Nilai Akhir &nbsp;= (Nilai Jasmani × {{ $ukgPct }}%) + (Renang × {{ $renangPct }}%)
                    = ({{ number_format($nilaiJasmani, 2) }} × {{ $ukgPct }}%) + ({{ $nilaiRenang }} × {{ $renangPct }}%)
                    = <strong style="color:#7f1d1d; font-size:11px;">{{ number_format($nilaiAkhir, 2) }}</strong>
                </div>

                @if($s->notes)
                <div style="background:#f9fafb; border:1px solid #e5e7eb; border-radius:4px; padding:6px 10px; font-size:8.5px; color:#6b7280; line-height:1.6;">
                    <strong style="color:#374151;">Catatan Coach:</strong><br>{{ $s->notes }}
                </div>
                @endif

            </td>
        </tr>
    </table>

    {{-- ══ FOOTER ══ --}}
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:60%">
                <span class="footer-brand">STAR JASMANI</span> · Digital Assessment System<br>
                Dokumen digenerate otomatis · {{ now()->format('d M Y, H:i') }} WIB<br>
                © {{ date('Y') }} Star Jasmani. All rights reserved.
            </td>
            <td style="width:40%; text-align:center;">
                <div class="sig-line"></div>
                <div class="sig-name">{{ $s->coach->name }}</div>
                <div class="sig-sub">Coach / Assessor Star Jasmani</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>