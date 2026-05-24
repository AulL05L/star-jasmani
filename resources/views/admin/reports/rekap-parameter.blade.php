<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <title>Rekap Parameter {{ $parameterKe }} — {{ $tahun }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Sans',sans-serif; background:#fff; color:#1a1a1a; font-size:8.5px; width:100%; }
        .page { padding:14px 18px; width:740px; }

        .header-table { width:100%; background:#7f1d1d; border-radius:6px; margin-bottom:8px; }
        .header-table td { padding:10px 16px; color:#fff; vertical-align:middle; }
        .brand { font-size:16px; font-weight:900; letter-spacing:2px; text-transform:uppercase; }
        .brand span { color:#fca5a5; }
        .brand-sub { font-size:7px; color:#fca5a5; letter-spacing:1px; text-transform:uppercase; margin-top:2px; }
        .title-center { font-size:15px; font-weight:900; text-transform:uppercase; letter-spacing:1px; text-align:center; }
        .title-sub { font-size:8px; color:#fca5a5; text-align:center; margin-top:3px; }
        .right-info { text-align:right; font-size:7.5px; color:#fca5a5; line-height:1.8; }

        .stats-table { width:100%; border:2px solid #7f1d1d; border-collapse:collapse; margin-bottom:8px; }
        .stats-table td { padding:8px 6px; text-align:center; border-right:1px solid #e5e7eb; }
        .stats-table td:last-child { border-right:none; }
        .stat-label { font-size:7px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; font-weight:700; margin-bottom:3px; }
        .stat-val { font-size:22px; font-weight:900; color:#7f1d1d; line-height:1; }
        .stat-val.dark { color:#1a1a1a; }
        .stat-val.green { color:#16a34a; }

        .tbl-outer { border:1px solid #e5e7eb; border-radius:6px; overflow:hidden; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl th { background:#1a1a1a; color:#fff; padding:5px 7px; font-size:7.5px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; text-align:center; }
        .tbl th:first-child { text-align:left; }
        .tbl td { padding:4px 7px; border-bottom:1px solid #f3f4f6; font-size:8.5px; color:#374151; text-align:center; }
        .tbl td:first-child { text-align:left; }
        .tbl tr:last-child td { border-bottom:none; }
        .tbl tr:nth-child(even) td { background:#fafafa; }

        .badge { display:inline-block; padding:1px 7px; border-radius:50px; font-size:7px; font-weight:900; text-transform:uppercase; }
        .bg-green { background:#14532d; color:#4ade80; }
        .bg-blue  { background:#1e3a5f; color:#60a5fa; }
        .bg-yellow{ background:#713f12; color:#facc15; }
        .bg-orange{ background:#7c2d12; color:#fb923c; }
        .bg-red   { background:#450a0a; color:#f87171; }

        .bar-bg { background:#f3f4f6; border-radius:3px; height:7px; width:100%; }
        .bar-fill { background:#7f1d1d; border-radius:3px; height:7px; }

        .footer-table { width:100%; border-top:2px solid #7f1d1d; margin-top:10px; border-collapse:collapse; }
        .footer-table td { padding-top:8px; font-size:7.5px; color:#9ca3af; line-height:1.8; vertical-align:bottom; }
        .footer-brand { color:#7f1d1d; font-size:10px; font-weight:900; }
        .sig-line { width:100px; border-bottom:1px solid #1a1a1a; margin:28px auto 4px; }
        .sig-name { text-align:center; font-size:8px; font-weight:700; color:#374151; }
        .sig-sub  { text-align:center; font-size:7px; color:#9ca3af; }
    </style>
</head>
<body>
<div class="page">

    {{-- Header --}}
    <table class="header-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:28%">
                <div class="brand">STAR <span>JASMANI</span></div>
                <div class="brand-sub">Digital Assessment System</div>
            </td>
            <td style="width:44%">
                <div class="title-center">Rekap Penilaian — Parameter {{ $parameterKe }}</div>
                <div class="title-sub">Laporan Kolektif Seluruh Atlet · Tahun {{ $tahun }}</div>
            </td>
            <td style="width:28%">
                <div class="right-info">
                    Dicetak: {{ now()->format('d M Y') }}<br>
                    Parameter: {{ $parameterKe }} dari 4<br>
                    Total Atlet: {{ $stats['total'] }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Stats --}}
    <table class="stats-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="background:#fff7f7">
                <div class="stat-label">Total Penilaian</div>
                <div class="stat-val">{{ $stats['total'] }}</div>
            </td>
            <td>
                <div class="stat-label">Rata-rata Nilai</div>
                <div class="stat-val dark">{{ $stats['avg'] }}</div>
            </td>
            <td>
                <div class="stat-label">Nilai Tertinggi</div>
                <div class="stat-val dark">{{ $stats['tertinggi'] }}</div>
            </td>
            <td>
                <div class="stat-label">Nilai Terendah</div>
                <div class="stat-val dark">{{ $stats['terendah'] }}</div>
            </td>
            <td style="background:#f0fdf4">
                <div class="stat-label">Jumlah Lulus</div>
                <div class="stat-val green">{{ $stats['lulus'] }}</div>
            </td>
            <td style="background:#7f1d1d">
                <div class="stat-label" style="color:#fca5a5">% Kelulusan</div>
                <div class="stat-val" style="color:#fff; font-size:18px;">
                    {{ $stats['total'] > 0 ? round($stats['lulus'] / $stats['total'] * 100) : 0 }}%
                </div>
            </td>
        </tr>
    </table>

    {{-- Tabel Rekap --}}
    <div class="tbl-outer">
        <table class="tbl" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width:4%; text-align:center;">No</th>
                    <th style="width:20%; text-align:left;">Nama Atlet</th>
                    <th style="width:8%;">Gender</th>
                    <th style="width:8%;">Jas A</th>
                    <th style="width:8%;">Jas B</th>
                    <th style="width:10%;">Jasmani</th>
                    <th style="width:8%;">Renang</th>
                    <th style="width:18%; text-align:left; padding-left:8px;">Progress</th>
                    <th style="width:8%;">Nilai Akhir</th>
                    <th style="width:8%;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($scores as $i => $score)
                @php
                    $gradeBadge = match($score->grade) {
                        'A' => 'bg-green', 'B' => 'bg-blue',
                        'C' => 'bg-yellow', 'D' => 'bg-orange',
                        default => 'bg-red'
                    };
                @endphp
                <tr>
                    <td style="text-align:center; color:#9ca3af;">{{ $i + 1 }}</td>
                    <td style="text-align:left; font-weight:700;">{{ $score->athlete->user->name ?? '—' }}</td>
                    <td>{{ $score->athlete->gender === 'pria' ? 'Putra' : 'Putri' }}</td>
                    <td style="font-weight:700;">{{ $score->nilai_jasmani_a ?? $score->score_lari ?? '—' }}</td>
                    <td style="font-weight:700;">{{ $score->nilai_jasmani_b ? number_format($score->nilai_jasmani_b, 1) : '—' }}</td>
                    <td style="font-weight:700; color:#7f1d1d;">{{ $score->nilai_total_jasmani ? number_format($score->nilai_total_jasmani, 1) : '—' }}</td>
                    <td style="color:#1d4ed8; font-weight:700;">{{ $score->score_renang ?? '—' }}</td>
                    <td style="padding:4px 8px;">
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ $score->score_final ?? 0 }}%;"></div>
                        </div>
                    </td>
                    <td style="font-weight:900; color:#7f1d1d; font-size:11px;">{{ number_format($score->score_final, 1) }}</td>
                    <td><span class="badge {{ $gradeBadge }}">{{ $score->grade }}</span></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Footer --}}
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:60%">
                <span class="footer-brand">STAR JASMANI</span> · Digital Assessment System<br>
                Rekap Parameter {{ $parameterKe }} · Tahun {{ $tahun }} · {{ $stats['total'] }} atlet<br>
                © {{ date('Y') }} Star Jasmani. All rights reserved.
            </td>
            <td style="width:40%; text-align:center;">
                <div class="sig-line"></div>
                <div class="sig-name">Fariz Fahrun, S.Or.</div>
                <div class="sig-sub">Coach / Assessor Star Jasmani</div>
            </td>
        </tr>
    </table>

</div>
</body>
</html>