<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Laporan Kebugaran - {{ $athlete->user->name }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            font-size: 9px;
            width: 100%;
        }

        .page { padding: 15px 18px; width: 740px; }

        /* ── HEADER ── */
        .header-table {
            width: 100%;
            background: #1a1a1a;
            border-radius: 8px;
            margin-bottom: 10px;
        }

        .header-table td { padding: 12px 20px; color: #ffffff; vertical-align: middle; }

        .header-brand { font-size: 18px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
        .header-brand span { color: #ef4444; }
        .header-sub { font-size: 8px; color: #9ca3af; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px; }
        .header-name { font-size: 18px; font-weight: 900; text-transform: uppercase; letter-spacing: 2px; text-align: center; }
        .header-right-text { text-align: right; font-size: 8px; color: #9ca3af; line-height: 1.8; }

        /* ── TOP STATS ── */
        .stats-table { width: 100%; border: 2px solid #1a1a1a; border-collapse: collapse; margin-bottom: 10px; border-radius: 8px; overflow: hidden; }
        .stats-table td { padding: 10px 8px; text-align: center; border-right: 1px solid #e5e7eb; }
        .stats-table td:last-child { border-right: none; }

        .stat-label { font-size: 7px; color: #6b7280; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 4px; }
        .stat-value { font-size: 26px; font-weight: 900; line-height: 1; margin-bottom: 4px; }

        .badge { display: inline-block; padding: 2px 10px; border-radius: 50px; font-size: 7px; font-weight: 900; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-normal   { background: #14532d; color: #4ade80; }
        .badge-kurang   { background: #1e3a5f; color: #60a5fa; }
        .badge-gemuk    { background: #713f12; color: #facc15; }
        .badge-obesitas { background: #450a0a; color: #f87171; }
        .badge-gray     { background: #f3f4f6; color: #374151; }
        .badge-black    { background: #1a1a1a; color: #ffffff; }

        /* ── MAIN TABLE ── */
        .main-table { width: 100%; border-collapse: collapse; }
        .main-table > tbody > tr > td { vertical-align: top; padding: 0; }
        .left-col { width: 32%; padding-right: 8px !important; }
        .right-col { width: 68%; }

        /* ── CARDS ── */
        .card { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 8px; width: 100%; }
        .card-header { background: #1a1a1a; padding: 5px 10px; font-size: 8px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: 1.5px; text-align: center; }
        .card-header.red { background: #991b1b; }

        .card-table { width: 100%; border-collapse: collapse; }
        .card-table td { padding: 5px 8px; border-bottom: 1px solid #f9fafb; font-size: 9px; }
        .card-table tr:last-child td { border-bottom: none; }
        .card-key { background: #f9fafb; color: #6b7280; font-weight: 700; font-size: 7.5px; text-transform: uppercase; letter-spacing: 0.5px; width: 45%; border-right: 1px solid #f3f4f6; }
        .card-val { color: #1a1a1a; font-weight: 700; }

        /* ── BMI METER ── */
        .bmi-meter-wrap {
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 12px;
            margin-bottom: 8px;
            text-align: center;
        }

        .bmi-big { font-size: 42px; font-weight: 900; line-height: 1; margin-bottom: 4px; }
        .bmi-normal   { color: #16a34a; }
        .bmi-kurang   { color: #2563eb; }
        .bmi-gemuk    { color: #d97706; }
        .bmi-obesitas { color: #dc2626; }

        .bmi-scale-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .bmi-scale-table td { text-align: center; padding: 4px 2px; font-size: 7px; font-weight: 700; border-radius: 2px; }

        /* ── HISTORY TABLE ── */
        .hist-outer { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 8px; }
        .hist-inner { width: 100%; border-collapse: collapse; }
        .hist-inner th { background: #1a1a1a; color: #fff; padding: 5px 8px; font-size: 7.5px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; text-align: center; }
        .hist-inner td { padding: 5px 8px; border-bottom: 1px solid #f3f4f6; font-size: 9px; color: #374151; text-align: center; }
        .hist-inner td:first-child { text-align: left; }
        .hist-inner tr:last-child td { border-bottom: none; }

        /* ── BAR CHART (berat badan trend) ── */
        .chart-outer { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 8px; }
        .chart-header { background: #1a1a1a; padding: 5px 10px; font-size: 8px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 1.5px; text-align: center; }
        .chart-inner { padding: 10px; }
        .chart-bars-table { width: 100%; border-collapse: collapse; border-bottom: 2px solid #e5e7eb; }
        .chart-bars-table td { vertical-align: bottom; text-align: center; padding: 0 4px; }
        .bar-score-label { font-size: 7.5px; font-weight: 900; color: #1a1a1a; display: block; margin-bottom: 2px; }
        .bar-block { border-radius: 3px 3px 0 0; display: block; margin: 0 auto; width: 28px; }
        .chart-labels-table { width: 100%; border-collapse: collapse; margin-top: 4px; }
        .chart-labels-table td { text-align: center; font-size: 6.5px; color: #6b7280; font-weight: 700; padding: 0 2px; }

        /* ── REKOMENDASI ── */
        .rek-box { background: #f9fafb; border: 1px solid #e5e7eb; border-left: 4px solid #1a1a1a; border-radius: 4px; padding: 8px 10px; font-size: 8.5px; color: #374151; margin-bottom: 8px; line-height: 1.8; }

        /* ── FOOTER ── */
        .footer-table { width: 100%; border-collapse: collapse; border-top: 2px solid #1a1a1a; margin-top: 10px; }
        .footer-table td { vertical-align: bottom; padding-top: 8px; font-size: 7.5px; color: #9ca3af; line-height: 1.8; }
        .footer-brand { color: #1a1a1a; font-size: 10px; font-weight: 900; }
        .sig-line { width: 100px; border-bottom: 1px solid #1a1a1a; margin: 30px auto 4px; }
        .sig-name { text-align: center; font-size: 8px; font-weight: 700; color: #374151; }
        .sig-sub { text-align: center; font-size: 7px; color: #9ca3af; }
    </style>
</head>
<body>
<div class="page">

    @php
        $latest = $athlete->bmiRecords->first();
        $bmiVal = $latest?->bmi_value ?? 0;
        $bmiStatus = $latest?->bmi_status ?? 'Normal';
        $bmiClass = match($bmiStatus) {
            'Normal' => 'bmi-normal', 'Kurang' => 'bmi-kurang',
            'Gemuk' => 'bmi-gemuk', default => 'bmi-obesitas'
        };
        $badgeClass = match($bmiStatus) {
            'Normal' => 'badge-normal', 'Kurang' => 'badge-kurang',
            'Gemuk' => 'badge-gemuk', default => 'badge-obesitas'
        };
    @endphp

    {{-- HEADER --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:30%">
                <div class="header-brand">STAR <span>JASMANI</span></div>
                <div class="header-sub">General Fitness Assessment</div>
            </td>
            <td style="width:40%; text-align:center;">
                <div class="header-name">{{ $athlete->user->name }}</div>
                <div style="font-size:8px; color:#9ca3af; margin-top:3px; letter-spacing:1px; text-transform:uppercase;">
                    Laporan Kebugaran & Body Mass Index
                </div>
            </td>
            <td style="width:30%">
                <div class="header-right-text">
                    Dicetak: {{ now()->format('d M Y') }}<br>
                    Total Pengukuran: {{ $athlete->bmiRecords->count() }}x<br>
                    Program: General Fitness
                </div>
            </td>
        </tr>
    </table>

    {{-- TOP STATS --}}
    <table class="stats-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="background:#f9fafb;">
                <div class="stat-label">BMI Terakhir</div>
                <div class="stat-value {{ $bmiClass }}">{{ $bmiVal }}</div>
                <span class="badge {{ $badgeClass }}">{{ $bmiStatus }}</span>
            </td>
            <td>
                <div class="stat-label">Tinggi Badan</div>
                <div class="stat-value" style="color:#1a1a1a; font-size:22px;">{{ $athlete->height_cm ?? '—' }}</div>
                <span class="badge badge-gray">cm</span>
            </td>
            <td>
                <div class="stat-label">Berat Badan</div>
                <div class="stat-value" style="color:#1a1a1a; font-size:22px;">{{ $athlete->weight_kg ?? '—' }}</div>
                <span class="badge badge-gray">kg</span>
            </td>
            <td>
                <div class="stat-label">Total Sesi</div>
                <div class="stat-value" style="color:#1a1a1a; font-size:22px;">{{ $athlete->bmiRecords->count() }}</div>
                <span class="badge badge-gray">Pengukuran</span>
            </td>
            <td style="background:#1a1a1a;">
                <div class="stat-label" style="color:#9ca3af;">Program</div>
                <div class="stat-value" style="color:#ffffff; font-size:13px; padding-top:4px;">General</div>
                <span class="badge" style="background:#ef4444; color:#fff;">Fitness</span>
            </td>
        </tr>
    </table>

    {{-- MAIN CONTENT --}}
    <table class="main-table" cellpadding="0" cellspacing="0">
        <tr>
            {{-- LEFT --}}
            <td class="left-col">

                {{-- Data Peserta --}}
                <div class="card">
                    <div class="card-header">Data Peserta</div>
                    <table class="card-table" cellpadding="0" cellspacing="0">
                        <tr><td class="card-key">Nama</td><td class="card-val">{{ $athlete->user->name }}</td></tr>
                        <tr><td class="card-key">Gender</td><td class="card-val">{{ Str::ucfirst($athlete->gender) }}</td></tr>
                        @if($athlete->birth_date)
                        <tr><td class="card-key">Usia</td><td class="card-val">{{ $athlete->birth_date->age }} tahun</td></tr>
                        @endif
                        <tr><td class="card-key">Telepon</td><td class="card-val">{{ $athlete->phone ?? '—' }}</td></tr>
                        <tr><td class="card-key">Program</td><td class="card-val">General Fitness</td></tr>
                    </table>
                </div>

                {{-- BMI Meter --}}
                <div class="bmi-meter-wrap">
                    <div style="font-size:7px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; margin-bottom:6px; font-weight:700;">Indeks Massa Tubuh</div>
                    <div class="bmi-big {{ $bmiClass }}">{{ $bmiVal }}</div>
                    <span class="badge {{ $badgeClass }}" style="font-size:8px; padding: 3px 14px;">{{ $bmiStatus }}</span>

                    {{-- BMI Scale --}}
                    <table class="bmi-scale-table" cellpadding="0" cellspacing="0">
                        <tr>
                            <td style="background:#dbeafe; color:#1d4ed8; border-radius:3px;">Kurang<br>&lt;17</td>
                            <td style="background:#dcfce7; color:#15803d; border-radius:3px;">Normal<br>17-24.9</td>
                            <td style="background:#fef9c3; color:#a16207; border-radius:3px;">Gemuk<br>25-29.9</td>
                            <td style="background:#fee2e2; color:#dc2626; border-radius:3px;">Obesitas<br>≥30</td>
                        </tr>
                    </table>

                    <div style="margin-top:8px; font-size:7.5px; color:#6b7280; line-height:1.6;">
                        Formula: Berat (kg) ÷ Tinggi² (m)<br>
                        = {{ $athlete->weight_kg }} ÷ {{ round(($athlete->height_cm/100)**2, 4) }} = <strong style="color:#1a1a1a;">{{ $bmiVal }}</strong>
                    </div>
                </div>

                {{-- Rekomendasi --}}
                <div class="rek-box">
                    <strong>Rekomendasi:</strong><br>
                    @if($bmiStatus === 'Normal')
                        BMI Anda berada dalam kategori ideal. Pertahankan pola makan seimbang dan rutinitas olahraga untuk menjaga kondisi tubuh optimal.
                    @elseif($bmiStatus === 'Kurang')
                        BMI Anda di bawah normal. Disarankan meningkatkan asupan kalori bergizi dan program strength training untuk menambah massa otot.
                    @elseif($bmiStatus === 'Gemuk')
                        BMI Anda di atas normal. Disarankan program cardio intensitas sedang dikombinasikan dengan pola makan kalori defisit yang terukur.
                    @else
                        BMI Anda dalam kategori obesitas. Sangat disarankan konsultasi lebih lanjut dengan Coach untuk program penurunan berat badan yang aman dan terstruktur.
                    @endif
                </div>

            </td>

            {{-- RIGHT --}}
            <td class="right-col">

                {{-- Riwayat Pengukuran --}}
                <div class="hist-outer">
                    <div style="background:#1a1a1a; padding:5px 10px; font-size:8px; font-weight:900; color:#fff; text-transform:uppercase; letter-spacing:1.5px; text-align:center;">
                        Riwayat Pengukuran BMI
                    </div>
                    @if($athlete->bmiRecords->isEmpty())
                        <div style="text-align:center; padding:20px; color:#6b7280; font-size:9px;">Belum ada data pengukuran.</div>
                    @else
                        <table class="hist-inner" cellpadding="0" cellspacing="0">
                            <thead>
                                <tr>
                                    <th style="text-align:left;">Tanggal</th>
                                    <th>Tinggi</th>
                                    <th>Berat</th>
                                    <th>BMI</th>
                                    <th>Status</th>
                                    <th>Progress Bar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($athlete->bmiRecords as $record)
                                @php
                                    $rBadge = match($record->bmi_status) {
                                        'Normal' => 'badge-normal', 'Kurang' => 'badge-kurang',
                                        'Gemuk' => 'badge-gemuk', default => 'badge-obesitas'
                                    };
                                    $rColor = match($record->bmi_status) {
                                        'Normal' => '#16a34a', 'Kurang' => '#2563eb',
                                        'Gemuk' => '#d97706', default => '#dc2626'
                                    };
                                    $bmiPercent = min(100, ($record->bmi_value / 40) * 100);
                                @endphp
                                <tr>
                                    <td style="text-align:left; font-weight:700;">{{ $record->recorded_date->format('d M Y') }}</td>
                                    <td>{{ $record->height_cm }} cm</td>
                                    <td>{{ $record->weight_kg }} kg</td>
                                    <td style="font-weight:900; color:{{ $rColor }}; font-size:11px;">{{ $record->bmi_value }}</td>
                                    <td><span class="badge {{ $rBadge }}">{{ $record->bmi_status }}</span></td>
                                    <td style="width:30%;">
                                        <div style="background:#f3f4f6; border-radius:4px; height:8px; width:100%;">
                                            <div style="background:{{ $rColor }}; border-radius:4px; height:8px; width:{{ $bmiPercent }}%;"></div>
                                        </div>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @endif
                </div>

                {{-- Bar Chart Berat Badan --}}
                @if($athlete->bmiRecords->count() > 1)
                <div class="chart-outer">
                    <div class="chart-header">Tren Berat Badan (kg)</div>
                    <div class="chart-inner">
                        @php
                            $records = $athlete->bmiRecords->reverse()->values();
                            $maxWeight = $records->max('weight_kg') ?: 100;
                            $chartHeight = 55;
                        @endphp
                        <table class="chart-bars-table" cellpadding="0" cellspacing="0">
                            <tr style="height: {{ $chartHeight }}px; vertical-align:bottom;">
                                @foreach($records as $rec)
                                @php
                                    $barH = ($rec->weight_kg / $maxWeight) * $chartHeight;
                                    $barColor = match($rec->bmi_status) {
                                        'Normal' => '#16a34a', 'Kurang' => '#2563eb',
                                        'Gemuk' => '#d97706', default => '#dc2626'
                                    };
                                @endphp
                                <td>
                                    <span class="bar-score-label">{{ $rec->weight_kg }}</span>
                                    <span class="bar-block" style="background:{{ $barColor }}; height:{{ $barH }}px; min-height:3px;"></span>
                                </td>
                                @endforeach
                            </tr>
                        </table>
                        <table class="chart-labels-table" cellpadding="0" cellspacing="0">
                            <tr>
                                @foreach($records as $rec)
                                <td>{{ $rec->recorded_date->format('d/m') }}</td>
                                @endforeach
                            </tr>
                        </table>
                    </div>
                </div>
                @endif

                {{-- Kategori BMI Referensi --}}
                <div style="border:1px solid #e5e7eb; border-radius:6px; overflow:hidden;">
                    <div style="background:#1a1a1a; padding:5px 10px; font-size:8px; font-weight:900; color:#fff; text-transform:uppercase; letter-spacing:1.5px; text-align:center;">
                        Referensi Kategori BMI (WHO)
                    </div>
                    <table style="width:100%; border-collapse:collapse;">
                        <tr>
                            <th style="background:#f9fafb; padding:5px 8px; font-size:7.5px; color:#6b7280; font-weight:700; text-transform:uppercase; text-align:left; border-bottom:1px solid #f3f4f6;">Kategori</th>
                            <th style="background:#f9fafb; padding:5px 8px; font-size:7.5px; color:#6b7280; font-weight:700; text-transform:uppercase; text-align:center; border-bottom:1px solid #f3f4f6;">Range BMI</th>
                            <th style="background:#f9fafb; padding:5px 8px; font-size:7.5px; color:#6b7280; font-weight:700; text-transform:uppercase; text-align:center; border-bottom:1px solid #f3f4f6;">Status Anda</th>
                        </tr>
                        @foreach([['Kurang','< 17.0','badge-kurang'],['Normal','17.0 - 24.9','badge-normal'],['Gemuk','25.0 - 29.9','badge-gemuk'],['Obesitas','≥ 30.0','badge-obesitas']] as $cat)
                        <tr>
                            <td style="padding:5px 8px; border-bottom:1px solid #f9fafb; font-size:9px; font-weight:700;">{{ $cat[0] }}</td>
                            <td style="padding:5px 8px; border-bottom:1px solid #f9fafb; font-size:9px; text-align:center; color:#6b7280;">{{ $cat[1] }}</td>
                            <td style="padding:5px 8px; border-bottom:1px solid #f9fafb; text-align:center;">
                                @if($bmiStatus === $cat[0])
                                    <span class="badge {{ $cat[2] }}">✓ Anda di sini</span>
                                @else
                                    <span style="color:#d1d5db; font-size:8px;">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </table>
                </div>

            </td>
        </tr>
    </table>

    {{-- FOOTER --}}
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:60%">
                <span class="footer-brand">STAR JASMANI</span> · General Fitness Assessment<br>
                Dokumen digenerate otomatis · {{ now()->format('d M Y, H:i') }} WIB<br>
                © {{ date('Y') }} Star Jasmani. All rights reserved.
            </td>
            <td style="width:40%; text-align:center;">
                <div class="sig-line"></div>
                <div class="sig-name">Fariz Fahrun, S.Or.</div>
                <div class="sig-sub">S&C Coach · Star Jasmani</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>