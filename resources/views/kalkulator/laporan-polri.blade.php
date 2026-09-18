<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Laporan POLRI Samapta — Star Jasmani</title>
    <style>
        /*
            Laporan dicetak lewat DomPDF, jadi tata letaknya memakai <table>:
            flexbox dan grid tidak didukung.

            Palet sengaja dibatasi — hitam, beberapa tingkat abu, dan satu merah
            aksen. Merah hanya muncul di tiga tempat: kata JASMANI, garis di bawah
            kepala surat, dan nilai akhir. Hijau dan merah tua hanya dipakai untuk
            status kelulusan, karena di situlah warna memang membawa arti.

            Margin halaman dibuat lapang (20mm) dan JUGA diberi padding pada
            pembungkus: DomPDF tidak selalu menghormati @page margin secara utuh,
            jadi padding itu jaring pengaman supaya isi tidak pernah mepet tepi.
        */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        @page { size: A4 portrait; margin: 0; }

        body {
            font-family: 'DejaVu Sans', sans-serif;
            background: #ffffff;
            color: #111111;
            font-size: 8.5px;
            line-height: 1.38;
        }
        .page { padding: 15mm 19mm; }

        .muted { color: #8a8a8a; }
        .label { font-size: 6.5px; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1.4px; }

        /* ── KEPALA SURAT ── */
        .head-table { width: 100%; border-collapse: collapse; }
        .brand      { font-size: 15px; font-weight: bold; letter-spacing: 3.5px; color: #111111; }
        .logo       { width: 34px; height: 34px; }
        .brand span { color: #991b1b; }
        .brand-sub  { font-size: 6.5px; color: #9a9a9a; letter-spacing: 2.2px; text-transform: uppercase; margin-top: 4px; }
        .head-meta  { font-size: 7px; color: #9a9a9a; text-align: right; line-height: 2; }
        .accent-rule { height: 2px; background: #991b1b; width: 34px; margin-top: 11px; }

        /* ── JUDUL ── */
        .doc-title { font-size: 12.5px; font-weight: bold; margin-top: 13px; }
        .doc-sub   { font-size: 8.5px; color: #8a8a8a; margin-top: 3px; }

        /* ── KARTU NILAI ── */
        .hero-card  { background: #fafafa; padding: 13px 17px; margin-top: 13px; }
        .hero-table { width: 100%; border-collapse: collapse; }
        .hero-table td { vertical-align: top; padding: 0; }
        .hero-num   { font-size: 40px; font-weight: bold; letter-spacing: -1.5px; line-height: 0.95; color: #991b1b; margin-top: 3px; }
        .hero-of    { font-size: 8px; color: #9a9a9a; margin-top: 4px; }
        .hero-side  { font-size: 13px; font-weight: bold; margin-top: 4px; }
        .hero-note  { font-size: 7.5px; color: #8a8a8a; margin-top: 2px; }

        .meter-wrap { margin-top: 9px; }
        .meter-bg   { background: #e8e8e8; height: 6px; width: 100%; }
        .meter-fl   { background: #991b1b; height: 6px; }
        .meter-tick { width: 100%; border-collapse: collapse; margin-top: 5px; }
        .meter-tick td { font-size: 6.5px; color: #9a9a9a; padding: 0; }

        /* ── BARIS KETERANGAN ── */
        .meta-table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        .meta-table td { padding: 7px 14px 7px 0; vertical-align: top; border-top: 1px solid #ececec; }
        .meta-val { font-size: 9.5px; font-weight: bold; margin-top: 4px; }

        /* ── SEKSI ── */
        .sec { margin-top: 10px; }
        .sec-title { font-size: 7px; text-transform: uppercase; letter-spacing: 2px; color: #111111; font-weight: bold; }

        /* ── TABEL RINCIAN ── */
        .detail-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .detail-table th {
            font-size: 6.5px; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1.4px;
            font-weight: normal; text-align: right; padding: 0 8px 5px 8px; border-bottom: 1px solid #111111;
        }
        .detail-table th.l { text-align: left; padding-left: 0; }
        .detail-table td { padding: 3.8px 8px; font-size: 8.5px; text-align: right; border-bottom: 1px solid #f2f2f2; }
        .detail-table td.l { text-align: left; padding-left: 0; }
        .detail-table td.num { font-weight: bold; font-size: 10px; }

        .grp td {
            font-size: 6.5px; color: #9a9a9a; text-transform: uppercase; letter-spacing: 1.4px;
            padding: 10px 0 3px 0; border-bottom: none; text-align: left;
        }
        .sub td { font-size: 7.5px; color: #6a6a6a; border-bottom: 1px solid #f2f2f2; padding: 4px 8px; }
        .sub td.num { font-weight: bold; font-size: 9px; color: #111111; }

        .total td { border-top: 1.5px solid #111111; border-bottom: none; padding: 8px; font-weight: bold; }
        .total td.l { font-size: 9px; letter-spacing: 1.4px; text-transform: uppercase; }
        .total td.num { font-size: 14px; color: #991b1b; }

        .bar-bg { background: #ececec; height: 4px; width: 100%; }
        .bar-fl { background: #6a6a6a; height: 4px; }

        /* ── PERHITUNGAN ── */
        .calc-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .calc-table td { font-size: 8px; padding: 2.5px 0; color: #4a4a4a; border-bottom: 1px solid #f7f7f7; }
        .calc-table tr:last-child td { border-bottom: none; }
        .calc-table td.k { width: 120px; color: #9a9a9a; }

        /* ── SKALA GRADE ── */
        .grade-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        .grade-table td { text-align: center; padding: 7px 4px; font-size: 7px; color: #b8b8b8; }
        .grade-table td .g { font-size: 11px; font-weight: bold; color: #cfcfcf; margin-bottom: 1px; }
        .grade-table td.on { color: #5a5a5a; background: #fafafa; }
        .grade-table td.on .g { color: #991b1b; }

        /* ── CATATAN & KAKI ── */
        .note { font-size: 7.5px; color: #8a8a8a; line-height: 1.6; margin-top: 9px; }
        .foot-table { width: 100%; border-collapse: collapse; margin-top: 6px; border-top: 1px solid #ececec; }
        .foot-table td { font-size: 6.5px; color: #adadad; line-height: 1.9; vertical-align: top; padding-top: 6px; }
    </style>
</head>
<body>
<div class="page">

@php
    $grade       = $result->grade ?? 'E';
    $isLulus     = $result->is_lulus ?? false;
    $gender      = $result->gender ?? 'pria';
    $pullupLabel = $gender === 'wanita' ? 'Chin Up' : 'Pull Up';
    $pullupUnit  = $gender === 'wanita' ? 'dtk'     : 'reps';
    $gradeLabel  = $result->grade_label ?? match($grade) {
        'A' => 'Sangat Baik', 'B' => 'Baik', 'C' => 'Cukup', 'D' => 'Kurang', default => 'Sangat Kurang'
    };
    $tokenShort  = strtoupper(substr($result->token, 0, 8));
    $statusColor = $isLulus ? '#15803d' : '#b91c1c';
    $final       = (float) ($result->score_final ?? 0);
    $bar         = fn ($s) => max(2, min(100, (float) $s));
@endphp

{{-- ══ KEPALA SURAT ══ --}}
<table class="head-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 44px; vertical-align: middle;">
            <img src="{{ public_path('pict/logo-removebg.png') }}" class="logo" alt="Logo Star Jasmani" />
        </td>
        <td style="width: 51%; vertical-align: middle;">
            <div class="brand">STAR <span>JASMANI</span></div>
            <div class="brand-sub">Digital Assessment System</div>
        </td>
        <td style="width: 45%; vertical-align: middle;">
            <div class="head-meta">
                {{ now()->format('d M Y, H:i') }} WIB<br>
                Ref {{ $tokenShort }}
            </div>
        </td>
    </tr>
</table>
<div class="accent-rule"></div>

{{-- ══ JUDUL ══ --}}
<div class="doc-title">Laporan Kalkulator POLRI Samapta B</div>
<div class="doc-sub">Estimasi nilai jasmani berdasarkan tabel konversi POLRI</div>

{{-- ══ KARTU NILAI ══ --}}
<div class="hero-card">
    <table class="hero-table" cellpadding="0" cellspacing="0">
        <tr>
            <td style="width: 44%;">
                <div class="label">Nilai Akhir</div>
                <div class="hero-num">{{ number_format($final, 1) }}</div>
                <div class="hero-of">dari 100 poin</div>
            </td>
            <td style="width: 28%; padding-top: 4px;">
                <div class="label">Grade</div>
                <div class="hero-side">{{ $grade }}</div>
                <div class="hero-note">{{ $gradeLabel }}</div>
            </td>
            <td style="width: 28%; padding-top: 4px;">
                <div class="label">Status</div>
                <div class="hero-side" style="color: {{ $statusColor }};">{{ $isLulus ? 'Lulus' : 'Belum Lulus' }}</div>
                <div class="hero-note">Batas lulus 70</div>
            </td>
        </tr>
    </table>

    <div class="meter-wrap">
        <div class="meter-bg"><div class="meter-fl" style="width: {{ $bar($final) }}%;"></div></div>
        <table class="meter-tick" cellpadding="0" cellspacing="0">
            <tr>
                <td style="width: 70%; text-align: left;">0</td>
                <td style="width: 30%; text-align: left;">70 &middot; batas lulus</td>
            </tr>
        </table>
    </div>
</div>

{{-- ══ KETERANGAN ══ --}}
<table class="meta-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 24%;">
            <div class="label">Gender</div>
            <div class="meta-val">{{ $gender === 'pria' ? 'Pria' : 'Wanita' }}</div>
        </td>
        <td style="width: 24%;">
            <div class="label">Tanggal Hitung</div>
            <div class="meta-val">{{ $result->created_at?->format('d M Y') ?? '—' }}</div>
        </td>
        <td style="width: 30%;">
            <div class="label">Formula</div>
            <div class="meta-val">80% UKG + 20% Renang</div>
        </td>
        <td style="width: 22%;">
            <div class="label">Berlaku Hingga</div>
            <div class="meta-val">{{ $result->expires_at?->format('d M Y') ?? '—' }}</div>
        </td>
    </tr>
</table>

{{-- ══ RINCIAN TES ══ --}}
<div class="sec">
    <div class="sec-title">Rincian Hasil Tes</div>
    <table class="detail-table" cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th class="l" style="width: 26%;">Jenis Tes</th>
                <th style="width: 13%;">Hasil</th>
                <th style="width: 11%;">Satuan</th>
                <th style="width: 36%;"></th>
                <th style="width: 14%;">Nilai</th>
            </tr>
        </thead>
        <tbody>

            <tr class="grp"><td colspan="5">Jasmani A — Lari</td></tr>
            <tr>
                <td class="l">Lari 12 Menit</td>
                <td>{{ number_format($result->raw_lari_meter) }}</td>
                <td class="muted">meter</td>
                <td><div class="bar-bg"><div class="bar-fl" style="width: {{ $bar($result->score_lari ?? 0) }}%;"></div></div></td>
                <td class="num">{{ number_format($result->score_lari ?? 0, 1) }}</td>
            </tr>
            <tr class="sub">
                <td colspan="4" style="text-align: right;">Nilai Jasmani A</td>
                <td class="num">{{ number_format($result->score_lari ?? 0, 1) }}</td>
            </tr>

            <tr class="grp"><td colspan="5">Jasmani B — Kekuatan &amp; Kelincahan</td></tr>
            <tr>
                <td class="l">{{ $pullupLabel }}</td>
                <td>{{ $result->raw_pullup_reps }}</td>
                <td class="muted">{{ $pullupUnit }}</td>
                <td><div class="bar-bg"><div class="bar-fl" style="width: {{ $bar($result->score_pullup ?? 0) }}%;"></div></div></td>
                <td class="num">{{ number_format($result->score_pullup ?? 0, 1) }}</td>
            </tr>
            <tr>
                <td class="l">Sit Up</td>
                <td>{{ $result->raw_situp_reps }}</td>
                <td class="muted">reps</td>
                <td><div class="bar-bg"><div class="bar-fl" style="width: {{ $bar($result->score_situp ?? 0) }}%;"></div></div></td>
                <td class="num">{{ number_format($result->score_situp ?? 0, 1) }}</td>
            </tr>
            <tr>
                <td class="l">Push Up</td>
                <td>{{ $result->raw_pushup_reps }}</td>
                <td class="muted">reps</td>
                <td><div class="bar-bg"><div class="bar-fl" style="width: {{ $bar($result->score_pushup ?? 0) }}%;"></div></div></td>
                <td class="num">{{ number_format($result->score_pushup ?? 0, 1) }}</td>
            </tr>
            <tr>
                <td class="l">Shuttle Run</td>
                <td>{{ $result->raw_shuttle_seconds }}</td>
                <td class="muted">detik</td>
                <td><div class="bar-bg"><div class="bar-fl" style="width: {{ $bar($result->score_shuttle ?? 0) }}%;"></div></div></td>
                <td class="num">{{ number_format($result->score_shuttle ?? 0, 1) }}</td>
            </tr>
            <tr class="sub">
                <td colspan="4" style="text-align: right;">Nilai Jasmani B — rata-rata empat komponen</td>
                <td class="num">{{ number_format($result->score_jasmani_b ?? 0, 1) }}</td>
            </tr>

            <tr class="grp"><td colspan="5">Renang — Bobot 20%</td></tr>
            <tr>
                <td class="l">Renang 50 Meter</td>
                <td>{{ $result->raw_renang_seconds }}</td>
                <td class="muted">detik</td>
                <td><div class="bar-bg"><div class="bar-fl" style="width: {{ $bar($result->score_renang ?? 0) }}%;"></div></div></td>
                <td class="num">{{ number_format($result->score_renang ?? 0, 1) }}</td>
            </tr>

            <tr class="total">
                <td class="l" colspan="4">Nilai Akhir</td>
                <td class="num">{{ number_format($final, 1) }}</td>
            </tr>

        </tbody>
    </table>
</div>

{{-- ══ PERHITUNGAN ══ --}}
<div class="sec">
    <div class="sec-title">Cara Perhitungan</div>
    <table class="calc-table" cellpadding="0" cellspacing="0">
        <tr>
            <td class="k">Jasmani A</td>
            <td>Nilai lari 12 menit &nbsp;=&nbsp; <strong>{{ number_format($result->score_lari ?? 0, 1) }}</strong></td>
        </tr>
        <tr>
            <td class="k">Jasmani B</td>
            <td>Rata-rata {{ strtolower($pullupLabel) }}, sit up, push up, shuttle run &nbsp;=&nbsp; <strong>{{ number_format($result->score_jasmani_b ?? 0, 1) }}</strong></td>
        </tr>
        <tr>
            <td class="k">Nilai UKG</td>
            <td>(Jasmani A + Jasmani B) &#247; 2 &nbsp;=&nbsp; <strong>{{ number_format($result->score_ukg_avg ?? 0, 1) }}</strong></td>
        </tr>
        <tr>
            <td class="k">Nilai Renang</td>
            <td><strong>{{ number_format($result->score_renang ?? 0, 1) }}</strong></td>
        </tr>
        <tr>
            <td class="k" style="color: #111111;">Nilai Akhir</td>
            <td>
                ({{ number_format($result->score_ukg_avg ?? 0, 1) }} &#215; 80%) + ({{ number_format($result->score_renang ?? 0, 1) }} &#215; 20%)
                &nbsp;=&nbsp; <strong style="color: #991b1b; font-size: 10px;">{{ number_format($final, 1) }}</strong>
            </td>
        </tr>
    </table>
</div>

{{-- ══ SKALA GRADE ══ --}}
<div class="sec">
    <div class="sec-title">Skala Grade</div>
    <table class="grade-table" cellpadding="0" cellspacing="0" style="border-top: 1px solid #ececec; border-bottom: 1px solid #ececec;">
        <tr>
            @foreach([['A', '≥ 80', 'Sangat Baik'], ['B', '70–79', 'Baik'], ['C', '60–69', 'Cukup'], ['D', '50–59', 'Kurang'], ['E', '< 50', 'Sangat Kurang']] as $g)
                <td class="{{ $grade === $g[0] ? 'on' : '' }}">
                    <div class="g">{{ $g[0] }}</div>
                    <div>{{ $g[1] }} &middot; {{ $g[2] }}</div>
                </td>
            @endforeach
        </tr>
    </table>
</div>

{{-- ══ CATATAN ══ --}}
<div class="note">
    <strong style="color: #5a5a5a;">Hasil estimasi, bukan dokumen resmi seleksi.</strong>
    Laporan ini dihitung memakai tabel konversi POLRI Samapta B dan hanya bersifat referensi pribadi.
    Nilai resmi ditentukan panitia seleksi POLRI pada saat tes berlangsung, dan laporan ini tidak dapat
    digunakan sebagai bukti kelulusan.
</div>

{{-- ══ KAKI ══ --}}
<table class="foot-table" cellpadding="0" cellspacing="0">
    <tr>
        <td style="width: 58%;">
            STAR JASMANI · Digital Assessment System<br>
            &#169; {{ date('Y') }} Star Jasmani
        </td>
        <td style="width: 42%; text-align: right;">
            Ref {{ $tokenShort }} · Berlaku hingga {{ $result->expires_at?->format('d M Y') ?? '—' }}<br>
            Dicetak {{ now()->format('d M Y, H:i') }} WIB
        </td>
    </tr>
</table>

</div>
</body>
</html>
