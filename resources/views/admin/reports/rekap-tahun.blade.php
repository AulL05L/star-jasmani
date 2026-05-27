<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"/>
    <title>Rekap Tahunan {{ $tahun }}</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'DejaVu Sans',sans-serif; background:#fff; color:#1a1a1a; font-size:8px; width:100%; }
        .page { padding:14px 18px; width:740px; }

        .header-table { width:100%; background:#7f1d1d; border-radius:6px; margin-bottom:8px; }
        .header-table td { padding:10px 16px; color:#fff; vertical-align:middle; }
        .brand { font-size:16px; font-weight:900; letter-spacing:2px; text-transform:uppercase; }
        .brand span { color:#fca5a5; }
        .brand-sub { font-size:7px; color:#fca5a5; letter-spacing:1px; text-transform:uppercase; margin-top:2px; }
        .title-center { font-size:15px; font-weight:900; text-transform:uppercase; letter-spacing:1px; text-align:center; }
        .title-sub { font-size:8px; color:#fca5a5; text-align:center; margin-top:3px; }
        .right-info { text-align:right; font-size:7.5px; color:#fca5a5; line-height:1.8; }

        .section-header { background:#1a1a1a; color:#fff; padding:5px 10px; font-size:8px; font-weight:900; text-transform:uppercase; letter-spacing:1.5px; margin-top:8px; margin-bottom:0; }
        .section-header.first { margin-top:0; }

        .stats-row { width:100%; border:1px solid #e5e7eb; border-collapse:collapse; margin-bottom:6px; }
        .stats-row td { padding:6px 8px; text-align:center; border-right:1px solid #e5e7eb; font-size:8px; }
        .stats-row td:last-child { border-right:none; }
        .s-label { font-size:6.5px; color:#6b7280; text-transform:uppercase; letter-spacing:1px; font-weight:700; display:block; margin-bottom:2px; }
        .s-val { font-size:16px; font-weight:900; color:#7f1d1d; }
        .s-val.dark { color:#1a1a1a; }
        .s-val.green { color:#16a34a; }

        .tbl-outer { border:1px solid #e5e7eb; border-radius:0 0 4px 4px; overflow:hidden; margin-bottom:6px; }
        .tbl { width:100%; border-collapse:collapse; }
        .tbl th { background:#374151; color:#fff; padding:4px 6px; font-size:7px; font-weight:700; text-transform:uppercase; letter-spacing:0.5px; text-align:center; }
        .tbl th:first-child { text-align:left; }
        .tbl td { padding:3px 6px; border-bottom:1px solid #f3f4f6; font-size:8px; color:#374151; text-align:center; }
        .tbl td:first-child { text-align:left; font-weight:700; }
        .tbl tr:last-child td { border-bottom:none; }
        .tbl tr:nth-child(even) td { background:#fafafa; }

        .badge { display:inline-block; padding:1px 6px; border-radius:50px; font-size:6.5px; font-weight:900; text-transform:uppercase; }
        .bg-green { background:#14532d; color:#4ade80; }
        .bg-blue  { background:#1e3a5f; color:#60a5fa; }
        .bg-yellow{ background:#713f12; color:#facc15; }
        .bg-orange{ background:#7c2d12; color:#fb923c; }
        .bg-red   { background:#450a0a; color:#f87171; }

        .bar-bg { background:#f3f4f6; border-radius:3px; height:6px; width:100%; }
        .bar-fill { background:#7f1d1d; border-radius:3px; height:6px; }

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
                <div class="title-center">Rekap Tahunan {{ $tahun }}</div>
                <div class="title-sub">Laporan Lengkap Seluruh Parameter · {{ $parameterList->count() }} Parameter</div>
            </td>
            <td style="width:28%">
                <div class="right-info">
                    Dicetak: {{ now()->format('d M Y') }}<br>
                    Tahun: {{ $tahun }}<br>
                    Total Sesi: {{ $statsTotal['total'] }}
                </div>
            </td>
        </tr>
    </table>

    {{-- Ringkasan Tahunan --}}
    <div class="section-header first">Ringkasan Keseluruhan Tahun {{ $tahun }}</div>
    <table class="stats-row" cellpadding="0" cellspacing="0">
        <tr>
            <td><span class="s-label">Total Sesi</span><span class="s-val">{{ $statsTotal['total'] }}</span></td>
            <td><span class="s-label">Rata-rata</span><span class="s-val dark">{{ $statsTotal['avg'] }}</span></td>
            <td><span class="s-label">Tertinggi</span><span class="s-val dark">{{ $statsTotal['tertinggi'] }}</span></td>
            <td><span class="s-label">Terendah</span><span class="s-val dark">{{ $statsTotal['terendah'] }}</span></td>
            <td><span class="s-label">Grade A+B</span><span class="s-val green">{{ ($statsTotal['grade_a'] ?? 0) + ($statsTotal['grade_b'] ?? 0) }}</span></td>
            <td style="background:#fff7f7"><span class="s-label">Grade C/D/E</span>
                <span class="s-val" style="color:#7f1d1d;">
                    {{ ($statsTotal['grade_c'] ?? 0) + ($statsTotal['grade_d'] ?? 0) + ($statsTotal['grade_e'] ?? 0) }}
                </span>
            </td>
        </tr>
    </table>

    {{-- Per Parameter --}}
    @foreach($parameterList as $p)
    @php $pScores = $allScores->where('parameter_ke', $p); @endphp

    <div class="section-header">Parameter {{ $p }} — {{ $pScores->count() }} Atlet</div>
    <div class="tbl-outer">
        <table class="tbl" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th style="width:4%; text-align:center;">No</th>
                    <th style="width:22%; text-align:left;">Nama Atlet</th>
                    <th style="width:7%;">Gender</th>
                    <th style="width:8%;">Jas A</th>
                    <th style="width:8%;">Jas B</th>
                    <th style="width:9%;">Jasmani</th>
                    <th style="width:7%;">Renang</th>
                    <th style="width:17%; text-align:left; padding-left:6px;">Progress</th>
                    <th style="width:9%;">Nilai Akhir</th>
                    <th style="width:9%;">Grade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pScores->sortByDesc('score_final') as $i => $score)
                @php
                    $gradeBadge = match($score->grade) {
                        'A' => 'bg-green', 'B' => 'bg-blue',
                        'C' => 'bg-yellow', 'D' => 'bg-orange',
                        default => 'bg-red'
                    };
                @endphp
                <tr>
                    <td style="text-align:center; color:#9ca3af;">{{ $i + 1 }}</td>
                    <td style="text-align:left; font-weight:700;">{{ $score->athlete?->user?->name ?? '—' }}</td>
                    <td>{{ ($score->athlete?->gender === 'pria') ? 'Putra' : 'Putri' }}</td>
                    <td>{{ $score->nilai_jasmani_a ?? $score->score_lari ?? '—' }}</td>
                    <td>{{ $score->nilai_jasmani_b ? number_format($score->nilai_jasmani_b, 1) : '—' }}</td>
                    <td style="color:#7f1d1d; font-weight:700;">{{ $score->nilai_total_jasmani ? number_format($score->nilai_total_jasmani, 1) : '—' }}</td>
                    <td style="color:#1d4ed8;">{{ $score->score_renang ?? '—' }}</td>
                    <td style="padding:3px 6px;">
                        <div class="bar-bg">
                            <div class="bar-fill" style="width:{{ $score->score_final ?? 0 }}%;"></div>
                        </div>
                    </td>
                    <td style="font-weight:900; color:#7f1d1d; font-size:10px;">{{ number_format($score->score_final, 1) }}</td>
                    <td><span class="badge {{ $gradeBadge }}">{{ $score->grade }}</span></td>
                </tr>
                @endforeach
                {{-- Subtotal row --}}
                @php $ps = $statsPerParameter[$p]; @endphp
                <tr style="background:#fff7f7;">
                    <td colspan="8" style="text-align:right; font-size:7px; color:#7f1d1d; font-weight:700; padding-right:8px;">
                        Rata-rata: {{ $ps['avg'] }} | Tertinggi: {{ $ps['tertinggi'] }} | Terendah: {{ $ps['terendah'] }} | A: {{ $ps['grade_a'] ?? 0 }} B: {{ $ps['grade_b'] ?? 0 }} C: {{ $ps['grade_c'] ?? 0 }} D: {{ $ps['grade_d'] ?? 0 }} E: {{ $ps['grade_e'] ?? 0 }}
                    </td>
                    <td style="font-weight:900; color:#7f1d1d;">{{ $ps['avg'] }}</td>
                    <td style="font-weight:700; color:#16a34a; font-size:7px;">
                        A:{{ $ps['grade_a'] ?? 0 }} B:{{ $ps['grade_b'] ?? 0 }}
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    @endforeach

    {{-- Footer --}}
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width:60%">
                <span class="footer-brand">STAR JASMANI</span> · Digital Assessment System<br>
                Rekap Tahunan {{ $tahun }} · {{ $parameterList->count() }} Parameter · {{ $statsTotal['total'] }} sesi<br>
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