<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Laporan POLRI Samapta — Star Jasmani</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 portrait; margin: 14mm 12mm 14mm 12mm; }
        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #1a1a1a;
            font-size: 9px;
            width: 100%;
        }

        /* ── HEADER ── */
        .header-table { width: 100%; background: #7f1d1d; border-radius: 6px; margin-bottom: 8px; }
        .header-table td { padding: 10px 14px; color: #fff; vertical-align: middle; }
        .brand { font-size: 16px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; }
        .brand span { color: #fca5a5; }
        .brand-sub { font-size: 7px; color: #fca5a5; letter-spacing: 1.5px; text-transform: uppercase; margin-top: 2px; }
        .hdr-title { font-size: 13px; font-weight: 900; text-align: center; letter-spacing: 1px; text-transform: uppercase; }
        .hdr-subtitle { font-size: 7px; color: #fca5a5; text-align: center; margin-top: 3px; letter-spacing: 1px; text-transform: uppercase; }
        .hdr-right { text-align: right; font-size: 7.5px; color: #fca5a5; line-height: 1.8; }

        /* ── DISCLAIMER ── */
        .disclaimer { background: #fefce8; border: 1px solid #fde047; border-left: 4px solid #eab308; border-radius: 4px; padding: 5px 10px; font-size: 7.5px; color: #713f12; margin-bottom: 8px; line-height: 1.6; }

        /* ── IDENTITY ROW ── */
        .identity-table { width: 100%; border: 1px solid #e5e7eb; border-collapse: collapse; margin-bottom: 8px; overflow: hidden; border-radius: 6px; }
        .identity-table td { padding: 6px 10px; border-right: 1px solid #e5e7eb; text-align: center; }
        .identity-table td:last-child { border-right: none; }
        .id-label { font-size: 6.5px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 2px; }
        .id-value { font-size: 10px; font-weight: 900; color: #1a1a1a; }
        .id-sub { font-size: 7px; color: #6b7280; margin-top: 1px; }

        /* ── HERO STATS ── */
        .hero-table { width: 100%; border: 2px solid #7f1d1d; border-collapse: collapse; margin-bottom: 8px; }
        .hero-table td { padding: 10px 8px; text-align: center; border-right: 1px solid #e5e7eb; vertical-align: middle; }
        .hero-table td:last-child { border-right: none; }
        .hero-label { font-size: 6.5px; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; font-weight: 700; margin-bottom: 4px; }
        .hero-big { font-size: 34px; font-weight: 900; line-height: 1; }
        .hero-tag { font-size: 7.5px; font-weight: 700; text-transform: uppercase; margin-top: 3px; letter-spacing: 0.5px; }

        /* ── COMPONENT TABLE ── */
        .section-wrap { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 8px; }
        .section-hdr { background: #7f1d1d; padding: 5px 10px; font-size: 7.5px; font-weight: 900; color: #fff; text-transform: uppercase; letter-spacing: 1.5px; }
        .comp-table { width: 100%; border-collapse: collapse; }
        .comp-table th { background: #1a1a1a; color: #fff; padding: 4px 6px; font-size: 7px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.5px; text-align: center; }
        .comp-table th.left { text-align: left; }
        .comp-table td { padding: 4px 6px; border-bottom: 1px solid #f3f4f6; font-size: 8px; color: #374151; text-align: center; vertical-align: middle; }
        .comp-table tr:last-child td { border-bottom: none; }
        .comp-table td.left { text-align: left; font-weight: 700; }
        .comp-table td.nilai { font-weight: 900; font-size: 11px; }
        .comp-table tr.sec-row td { background: #f9fafb; font-size: 6.5px; font-weight: 900; color: #9ca3af; text-transform: uppercase; letter-spacing: 1px; padding: 3px 6px; text-align: left; }
        .comp-table tr.subtotal td { background: #fff7f7; font-weight: 900; font-size: 8px; }
        .comp-table tr.total-row td { background: #7f1d1d; color: #fff; font-weight: 900; font-size: 11px; }
        .bar-bg { background: #f3f4f6; border-radius: 3px; height: 7px; width: 100%; }
        .bar-red  { background: #7f1d1d; border-radius: 3px; height: 7px; }
        .bar-blue { background: #1d4ed8; border-radius: 3px; height: 7px; }

        /* ── FORMULA ── */
        .formula-box { background: #fff7f7; border: 1px solid #fca5a5; border-left: 4px solid #7f1d1d; border-radius: 4px; padding: 7px 10px; font-size: 8px; color: #374151; line-height: 2; margin-bottom: 8px; }

        /* ── GRADE LEGEND ── */
        .grade-wrap { border: 1px solid #e5e7eb; border-radius: 6px; overflow: hidden; margin-bottom: 8px; }
        .grade-table { width: 100%; border-collapse: collapse; }
        .grade-table td { text-align: center; padding: 6px 4px; border-right: 1px solid #e5e7eb; }
        .grade-table td:last-child { border-right: none; }
        .grade-letter { font-size: 18px; font-weight: 900; line-height: 1; }
        .grade-range { font-size: 7px; color: #6b7280; margin-top: 2px; }
        .grade-desc { font-size: 6.5px; color: #9ca3af; margin-top: 1px; }

        /* ── FOOTER ── */
        .footer { border-top: 2px solid #7f1d1d; margin-top: 8px; padding-top: 6px; }
        .footer-table { width: 100%; border-collapse: collapse; }
        .footer-table td { font-size: 7px; color: #9ca3af; line-height: 1.8; vertical-align: top; }
        .footer-brand { color: #7f1d1d; font-size: 9px; font-weight: 900; }
    </style>
</head>
<body>

@php
    $grade       = $result->grade ?? 'E';
    $isLulus     = $result->is_lulus ?? false;
    $gender      = $result->gender ?? 'pria';
    $pullupLabel = $gender === 'wanita' ? 'Chin Up' : 'Pull Up';
    $pullupUnit  = $gender === 'wanita' ? 'dtk'     : 'reps';
    $gradeLabel  = $result->grade_label ?? match($grade) {
        'A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang', default => 'Sangat Kurang'
    };
    $gradeColor  = match($grade) {
        'A' => '#166534', 'B' => '#1d4ed8', 'C' => '#92400e', 'D' => '#9a3412', default => '#7f1d1d'
    };
    $tokenShort  = strtoupper(substr($result->token, 0, 8));
    $scoreColor  = fn($s) => $s >= 80 ? '#166534' : ($s >= 70 ? '#1d4ed8' : ($s >= 60 ? '#92400e' : '#9a3412'));
@endphp

{{-- ══ HEADER ══ --}}
<table class="header-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 30%;">
            <div class="brand">STAR <span>JASMANI</span></div>
            <div class="brand-sub">Digital Assessment System</div>
        </td>
        <td style="width: 40%; text-align: center;">
            <div class="hdr-title">Laporan Kalkulator POLRI</div>
            <div class="hdr-subtitle">Samapta B — Estimasi Nilai Jasmani</div>
        </td>
        <td style="width: 30%;">
            <div class="hdr-right">
                Dicetak: {{ now()->format('d M Y, H:i') }} WIB<br>
                Berlaku hingga: {{ $result->expires_at?->format('d M Y') ?? '—' }}<br>
                Ref: <strong style="color: #fff;">{{ $tokenShort }}</strong>
            </div>
        </td>
    </tr>
</table>

{{-- ══ DISCLAIMER ══ --}}
<div class="disclaimer">
    <strong>&#9888; Hasil Estimasi — Bukan Dokumen Resmi Seleksi.</strong>
    Laporan ini digenerate berdasarkan standar tabel POLRI Samapta B dan hanya bersifat referensi pribadi.
    Nilai resmi ditentukan oleh panitia seleksi POLRI pada saat tes berlangsung.
</div>

{{-- ══ IDENTITY ROW ══ --}}
<table class="identity-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 25%;">
            <div class="id-label">Gender</div>
            <div class="id-value">{{ $gender === 'pria' ? 'Pria' : 'Wanita' }}</div>
            <div class="id-sub">Program POLRI</div>
        </td>
        <td style="width: 25%;">
            <div class="id-label">Tanggal Hitung</div>
            <div class="id-value" style="font-size: 9px;">{{ $result->created_at?->format('d M Y') ?? '—' }}</div>
            <div class="id-sub">{{ $result->created_at?->format('H:i') ?? '' }} WIB</div>
        </td>
        <td style="width: 25%;">
            <div class="id-label">Formula</div>
            <div class="id-value" style="font-size: 9px;">80% UKG + 20% Renang</div>
            <div class="id-sub">POLRI Default</div>
        </td>
        <td style="width: 25%;">
            <div class="id-label">No. Referensi</div>
            <div class="id-value" style="font-size: 9px; letter-spacing: 1px;">{{ $tokenShort }}</div>
            <div class="id-sub">Berlaku 7 hari</div>
        </td>
    </tr>
</table>

{{-- ══ HERO STATS ══ --}}
<table class="hero-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 40%; background: #fff7f7;">
            <div class="hero-label">Nilai Akhir</div>
            <div class="hero-big" style="color: {{ $gradeColor }};">{{ number_format($result->score_final ?? 0, 1) }}</div>
            <div class="hero-tag" style="color: #6b7280;">/ 100 Poin</div>
        </td>
        <td style="width: 25%;">
            <div class="hero-label">Grade</div>
            <div class="hero-big" style="color: {{ $gradeColor }};">{{ $grade }}</div>
            <div class="hero-tag" style="color: {{ $gradeColor }};">{{ $gradeLabel }}</div>
        </td>
        <td style="width: 35%; {{ $isLulus ? 'background: #f0fdf4;' : 'background: #fef2f2;' }}">
            <div class="hero-label">Status Kelulusan</div>
            @if($isLulus)
                <div class="hero-big" style="color: #166534; font-size: 22px; margin-top: 4px;">LULUS</div>
                <div class="hero-tag" style="color: #166534;">Nilai Akhir &#8805; 70</div>
            @else
                <div class="hero-big" style="color: #7f1d1d; font-size: 16px; margin-top: 4px;">BELUM LULUS</div>
                <div class="hero-tag" style="color: #7f1d1d;">Nilai Akhir &lt; 70</div>
            @endif
        </td>
    </tr>
</table>

{{-- ══ COMPONENT SCORES TABLE ══ --}}
<div class="section-wrap">
    <div class="section-hdr">Detail Hasil Tes Jasmani</div>
    <table class="comp-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="left" style="width: 28%;">Jenis Tes</th>
                <th style="width: 12%;">Hasil</th>
                <th style="width: 8%;">Sat.</th>
                <th style="width: 38%; text-align: left; padding-left: 6px;">Progress</th>
                <th style="width: 14%;">Nilai</th>
            </tr>
        </thead>
        <tbody>

            {{-- JASMANI A --}}
            <tr class="sec-row"><td colspan="5">&#9658; Jasmani A &#8212; Lari</td></tr>
            <tr>
                <td class="left">Lari 12 Menit</td>
                <td>{{ number_format($result->raw_lari_meter) }}</td>
                <td>meter</td>
                <td style="padding: 4px 6px;">
                    <div class="bar-bg"><div class="bar-red" style="width: {{ min(100, $result->score_lari ?? 0) }}%;"></div></div>
                </td>
                <td class="nilai" style="color: {{ $scoreColor($result->score_lari ?? 0) }};">{{ number_format($result->score_lari ?? 0, 1) }}</td>
            </tr>
            <tr class="subtotal">
                <td colspan="4" style="text-align: right; color: #7f1d1d; font-size: 7px; padding-right: 6px;">Jasmani A =</td>
                <td class="nilai" style="color: #7f1d1d;">{{ number_format($result->score_lari ?? 0, 1) }}</td>
            </tr>

            {{-- JASMANI B --}}
            <tr class="sec-row"><td colspan="5">&#9658; Jasmani B &#8212; Kekuatan &amp; Kelincahan</td></tr>
            <tr>
                <td class="left">{{ $pullupLabel }}</td>
                <td>{{ $result->raw_pullup_reps }}</td>
                <td>{{ $pullupUnit }}</td>
                <td style="padding: 4px 6px;">
                    <div class="bar-bg"><div class="bar-red" style="width: {{ min(100, $result->score_pullup ?? 0) }}%;"></div></div>
                </td>
                <td class="nilai" style="color: {{ $scoreColor($result->score_pullup ?? 0) }};">{{ number_format($result->score_pullup ?? 0, 1) }}</td>
            </tr>
            <tr>
                <td class="left">Sit Up</td>
                <td>{{ $result->raw_situp_reps }}</td>
                <td>reps</td>
                <td style="padding: 4px 6px;">
                    <div class="bar-bg"><div class="bar-red" style="width: {{ min(100, $result->score_situp ?? 0) }}%;"></div></div>
                </td>
                <td class="nilai" style="color: {{ $scoreColor($result->score_situp ?? 0) }};">{{ number_format($result->score_situp ?? 0, 1) }}</td>
            </tr>
            <tr>
                <td class="left">Push Up</td>
                <td>{{ $result->raw_pushup_reps }}</td>
                <td>reps</td>
                <td style="padding: 4px 6px;">
                    <div class="bar-bg"><div class="bar-red" style="width: {{ min(100, $result->score_pushup ?? 0) }}%;"></div></div>
                </td>
                <td class="nilai" style="color: {{ $scoreColor($result->score_pushup ?? 0) }};">{{ number_format($result->score_pushup ?? 0, 1) }}</td>
            </tr>
            <tr>
                <td class="left">Shuttle Run</td>
                <td>{{ $result->raw_shuttle_seconds }}</td>
                <td>detik</td>
                <td style="padding: 4px 6px;">
                    <div class="bar-bg"><div class="bar-red" style="width: {{ min(100, $result->score_shuttle ?? 0) }}%;"></div></div>
                </td>
                <td class="nilai" style="color: {{ $scoreColor($result->score_shuttle ?? 0) }};">{{ number_format($result->score_shuttle ?? 0, 1) }}</td>
            </tr>
            <tr class="subtotal">
                <td colspan="4" style="text-align: right; color: #7f1d1d; font-size: 7px; padding-right: 6px;">
                    Jasmani B = avg({{ $pullupLabel }}, Sit Up, Push Up, Shuttle) =
                </td>
                <td class="nilai" style="color: #7f1d1d;">{{ number_format($result->score_jasmani_b ?? 0, 1) }}</td>
            </tr>

            {{-- RENANG --}}
            <tr class="sec-row"><td colspan="5">&#9658; Renang 50m (Bobot 20%)</td></tr>
            <tr>
                <td class="left">Renang 50m</td>
                <td>{{ $result->raw_renang_seconds }}</td>
                <td>detik</td>
                <td style="padding: 4px 6px;">
                    <div class="bar-bg"><div class="bar-blue" style="width: {{ min(100, $result->score_renang ?? 0) }}%;"></div></div>
                </td>
                <td class="nilai" style="color: #1d4ed8;">{{ number_format($result->score_renang ?? 0, 1) }}</td>
            </tr>

            {{-- TOTAL --}}
            <tr class="total-row">
                <td colspan="4" class="left" style="font-size: 8.5px; letter-spacing: 1px;">NILAI AKHIR POLRI SAMAPTA B</td>
                <td style="font-size: 14px; text-align: center;">{{ number_format($result->score_final ?? 0, 1) }}</td>
            </tr>

        </tbody>
    </table>
</div>

{{-- ══ FORMULA BOX ══ --}}
<div class="formula-box">
    <strong style="color: #7f1d1d;">Rincian Perhitungan:</strong><br>
    Jasmani A (Lari)&nbsp;&nbsp;&nbsp;&nbsp;= <strong>{{ number_format($result->score_lari ?? 0, 1) }}</strong><br>
    Jasmani B&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= avg({{ $pullupLabel }} + Sit Up + Push Up + Shuttle Run) = <strong>{{ number_format($result->score_jasmani_b ?? 0, 1) }}</strong><br>
    Nilai UKG&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= (Jasmani A + Jasmani B) &#247; 2 = <strong>{{ number_format($result->score_ukg_avg ?? 0, 1) }}</strong><br>
    Nilai Renang&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= <strong style="color: #1d4ed8;">{{ number_format($result->score_renang ?? 0, 1) }}</strong><br>
    <strong style="color: #7f1d1d;">Nilai Akhir</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;= ({{ number_format($result->score_ukg_avg ?? 0, 1) }} &#215; 80%) + ({{ number_format($result->score_renang ?? 0, 1) }} &#215; 20%)
    = <strong style="font-size: 11px; color: #7f1d1d;">{{ number_format($result->score_final ?? 0, 1) }}</strong>
    &#8594; Grade <strong style="color: {{ $gradeColor }};">{{ $grade }} &#8212; {{ $gradeLabel }}</strong>
</div>

{{-- ══ GRADE LEGEND ══ --}}
<div class="grade-wrap">
    <div class="section-hdr" style="font-size: 7px;">Keterangan Grade POLRI Samapta B</div>
    <table class="grade-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="{{ $grade === 'A' ? 'background: #f0fdf4;' : '' }}">
                <div class="grade-letter" style="color: #166534;">A</div>
                <div class="grade-range">&#8805; 80</div>
                <div class="grade-desc">Sangat Baik</div>
            </td>
            <td style="{{ $grade === 'B' ? 'background: #eff6ff;' : '' }}">
                <div class="grade-letter" style="color: #1d4ed8;">B</div>
                <div class="grade-range">70&#8211;79</div>
                <div class="grade-desc">Baik</div>
            </td>
            <td style="{{ $grade === 'C' ? 'background: #fefce8;' : '' }}">
                <div class="grade-letter" style="color: #92400e;">C</div>
                <div class="grade-range">60&#8211;69</div>
                <div class="grade-desc">Cukup</div>
            </td>
            <td style="{{ $grade === 'D' ? 'background: #fff7ed;' : '' }}">
                <div class="grade-letter" style="color: #9a3412;">D</div>
                <div class="grade-range">50&#8211;59</div>
                <div class="grade-desc">Kurang</div>
            </td>
            <td style="{{ $grade === 'E' ? 'background: #fef2f2;' : '' }}">
                <div class="grade-letter" style="color: #7f1d1d;">E</div>
                <div class="grade-range">&lt; 50</div>
                <div class="grade-desc">Sangat Kurang</div>
            </td>
        </tr>
    </table>
</div>

{{-- ══ FOOTER ══ --}}
<div class="footer">
    <table class="footer-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 65%;">
                <span class="footer-brand">STAR JASMANI</span> &#183; Digital Assessment System<br>
                Laporan digenerate otomatis &#183; {{ now()->format('d M Y, H:i') }} WIB<br>
                No. Referensi: {{ $tokenShort }} &#183; Berlaku hingga: {{ $result->expires_at?->format('d M Y') ?? '—' }}<br>
                &#169; {{ date('Y') }} Star Jasmani &#8212; Hasil estimasi, bukan dokumen resmi POLRI.
            </td>
            <td style="width: 35%; text-align: right;">
                Nilai resmi ditentukan panitia seleksi POLRI.<br>
                Laporan ini tidak dapat digunakan sebagai<br>
                bukti kelulusan seleksi POLRI.
            </td>
        </tr>
    </table>
</div>

</body>
</html>
