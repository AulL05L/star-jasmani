@extends('layouts.app')

@section('title', 'Dashboard — ' . $user->name)

@push('styles')
<style>
  .score-card { background: linear-gradient(135deg, #111 0%, #1a1a1a 100%); border: 1px solid #27272a; transition: all .3s; }
  .score-card:hover { border-color: #991b1b; transform: translateY(-2px); box-shadow: 0 8px 30px rgba(153,27,27,.15); }
  .bar-fill { transition: width 1.2s cubic-bezier(.4,0,.2,1); }
  .ket-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 8px; border-radius: 999px; font-size: 10px; font-weight: 700; }
  .tab-btn { padding: 6px 16px; border-radius: 8px; font-size: 12px; font-weight: 700; transition: all .2s; cursor: pointer; border: 1px solid #374151; color: #6b7280; background: transparent; }
  .tab-btn.active { background: #991b1b; border-color: #991b1b; color: #fff; }
  .tab-btn:hover:not(.active) { border-color: #4b5563; color: #d1d5db; }
  .section-card { background: #030712; border: 1px solid #1f2937; border-radius: 16px; }
</style>
@endpush

@section('content')
<div class="min-h-screen bg-black text-white">

  @if(!$athlete)
  {{-- ── NO PROFILE ─────────────────────────────────────── --}}
  <div class="p-6 lg:p-8">
    <div class="section-card p-16 text-center">
      <i class="fa-solid fa-user-slash text-gray-700 text-5xl mb-4"></i>
      <p class="text-white font-black text-xl mb-2">Profil Atlet Belum Dibuat</p>
      <p class="text-gray-500 text-sm mb-6">Hubungi Coach untuk melengkapi data profil Anda.</p>
      <a href="https://wa.me/6285603875675" class="inline-flex items-center gap-2 bg-green-800 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl text-sm transition-all">
        <i class="fa-brands fa-whatsapp"></i> Hubungi Coach
      </a>
    </div>
  </div>
  @else

  {{-- ══════════════════════════════════════════════════════ --}}
  {{--  HEADER BANNER                                        --}}
  {{-- ══════════════════════════════════════════════════════ --}}
  <div class="bg-gradient-to-r from-black via-red-950/30 to-black border-b border-red-900/30 px-6 lg:px-10 py-5">
    <div class="flex items-center justify-between gap-4 flex-wrap">
      <div class="flex items-center gap-4">
        {{-- Logo / Avatar --}}
        <div class="w-12 h-12 rounded-xl bg-red-900 flex items-center justify-center font-black text-xl text-white flex-shrink-0">
          {{ strtoupper(substr($user->name, 0, 1)) }}
        </div>
        <div>
          <p class="text-red-400 text-[10px] uppercase tracking-widest font-bold">Hasil Test Jasmani</p>
          <h1 class="text-white font-black text-lg lg:text-xl tracking-tight leading-tight">{{ strtoupper($user->name) }}</h1>
          <p class="text-gray-500 text-xs mt-0.5">
            {{ $athlete->target_institution ?? 'Star Jasmani' }}
            @if($athlete->batchGroup) · {{ $athlete->batchGroup->name }} @elseif($athlete->batch) · {{ $athlete->batch }} @endif
          </p>
        </div>
      </div>

      <div class="flex items-center gap-6 text-sm">
        @if($selectedScore)
        <div class="flex items-center gap-2 text-gray-400">
          <i class="fa-regular fa-calendar text-red-600 text-sm"></i>
          <div>
            <p class="text-[10px] text-gray-600 uppercase tracking-widest">Tanggal Test</p>
            <p class="text-white font-bold text-sm">{{ $selectedScore->assessment_date->format('d M Y') }}</p>
          </div>
        </div>
        @endif
        <div class="flex items-center gap-2 text-gray-400">
          <i class="fa-solid fa-bullseye text-red-600 text-sm"></i>
          <div>
            <p class="text-[10px] text-gray-600 uppercase tracking-widest">Tujuan Instansi</p>
            <p class="text-white font-bold text-sm">{{ $athlete->target_institution ?? 'TNI / POLRI' }}</p>
          </div>
        </div>
        @if($selectedScore)
        <a href="{{ route('member.reports.pdf', $selectedScore) }}"
          class="flex items-center gap-2 px-4 py-2 rounded-xl border border-red-800 text-red-400 hover:bg-red-800 hover:text-white text-xs font-black uppercase tracking-wider transition-all">
          <i class="fa-solid fa-file-pdf"></i> Download PDF
        </a>
        @endif
      </div>
    </div>
  </div>

  <div class="p-4 lg:p-6 space-y-5">

    {{-- ── PARAMETER SELECTOR ────────────────────────────── --}}
    @if($allScores->count() > 1)
    <div class="flex items-center gap-2 overflow-x-auto pb-1">
      <span class="text-gray-600 text-xs font-bold uppercase tracking-widest flex-shrink-0 mr-1">Sesi:</span>
      @foreach($allScores as $s)
      <a href="{{ route('member.dashboard', ['score_id' => $s->id]) }}"
        class="tab-btn flex-shrink-0 {{ ($selectedScore && $selectedScore->id === $s->id) ? 'active' : '' }}">
        {{ $s->parameter?->label ?? ($s->session_label ?? 'Sesi ' . ($loop->index + 1)) }}
      </a>
      @endforeach
    </div>
    @endif

    @if($selectedScore)
    @php
      $s         = $selectedScore;
      $gender    = $athlete->gender;
      $upperTest = $gender === 'pria' ? 'Pull-Up' : 'Chin-Up';

      // Category label helper
      $cat = fn($v) => match(true) {
        $v === null  => ['—', 'text-gray-600', '#374151'],
        $v >= 90     => ['Sangat Baik', 'text-green-400', '#14532d'],
        $v >= 75     => ['Baik', 'text-blue-400', '#1e3a5f'],
        $v >= 60     => ['Cukup', 'text-yellow-400', '#451a03'],
        $v >= 50     => ['Rendah', 'text-orange-400', '#431407'],
        default      => ['Kurang', 'text-red-400', '#450a0a'],
      };

      // Top card category (scale of final/ukg)
      $catTop = fn($v) => match(true) {
        $v === null => ['—', '#374151'],
        $v >= 80    => ['Tinggi', '#14532d'],
        $v >= 65    => ['Sedang', '#1e3a5f'],
        default     => ['Rendah', '#450a0a'],
      };

      [$finalCatLabel, $finalCatBg]   = $catTop($s->score_final);
      [$aCatLabel,     $aCatBg]       = $catTop($s->score_lari);
      [$bCatLabel,     $bCatBg]       = $catTop($s->score_jasmani_b);
      [$jasCatLabel,   $jasCatBg]     = $catTop($s->score_ukg_avg);

      $jasmaniBob = $s->score_ukg_avg ? round($s->score_ukg_avg * 0.80, 1) : 0;
      $renangKont = $s->score_renang  ? round($s->score_renang  * 0.20, 1) : 0;

      // Items for table & chart
      $items = [
        ['label' => 'Lari 12 Menit', 'raw' => $s->raw_lari_meter,      'unit' => 'Meter', 'score' => $s->score_lari,    'icon' => 'fa-person-running'],
        ['label' => 'Push Up',       'raw' => $s->raw_pushup_reps,     'unit' => 'Reps',  'score' => $s->score_pushup,  'icon' => 'fa-dumbbell'],
        ['label' => 'Sit Up',        'raw' => $s->raw_situp_reps,      'unit' => 'Reps',  'score' => $s->score_situp,   'icon' => 'fa-child-reaching'],
        ['label' => $upperTest,      'raw' => $s->raw_pullup_reps,     'unit' => 'Reps',  'score' => $s->score_pullup,  'icon' => 'fa-arrow-up-from-bracket'],
        ['label' => 'Shuttle Run',   'raw' => $s->raw_shuttle_seconds, 'unit' => 'Detik', 'score' => $s->score_shuttle, 'icon' => 'fa-shuffle'],
        ['label' => 'Renang',        'raw' => $s->raw_renang_seconds,  'unit' => 'Detik', 'score' => $s->score_renang,  'icon' => 'fa-water'],
      ];

      // Auto-recommendations
      $recs = [];
      if ($s->score_lari   !== null && $s->score_lari   < 80) $recs[] = 'Tingkatkan daya tahan lari dengan latihan cardio rutin 3-4x seminggu.';
      if ($s->score_pushup !== null && $s->score_pushup < 80) $recs[] = 'Perbanyak latihan push-up progresif untuk kekuatan lengan & dada.';
      if ($s->score_situp  !== null && $s->score_situp  < 80) $recs[] = 'Latihan core stability & sit-up untuk memperkuat otot perut.';
      if ($s->score_pullup !== null && $s->score_pullup < 80) $recs[] = 'Latihan ' . strtolower($upperTest) . ' lebih intensif untuk kekuatan tubuh bagian atas.';
      if ($s->score_shuttle!== null && $s->score_shuttle< 80) $recs[] = 'Tingkatkan kecepatan & kelincahan dengan latihan agility ladder.';
      if ($s->score_renang !== null && $s->score_renang < 80) $recs[] = 'Perbanyak sesi latihan renang untuk meningkatkan teknik & stamina.';
      if (empty($recs)) {
        $recs = [
          'Pertahankan performa yang sudah sangat baik.',
          'Jaga konsistensi latihan rutin dan pola makan seimbang.',
          'Istirahat cukup untuk pemulihan optimal.',
        ];
      }
    @endphp

    {{-- ── TOP 4 SCORE CARDS ─────────────────────────────── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3">
      @foreach([
        ['title'=>'Nilai Akhir', 'value'=>$s->score_final, 'catLabel'=>$finalCatLabel, 'catBg'=>$finalCatBg, 'icon'=>'fa-trophy', 'big'=>true],
        ['title'=>'Jasmani A',   'value'=>$s->score_lari,   'catLabel'=>$aCatLabel,     'catBg'=>$aCatBg,     'icon'=>'fa-person-running'],
        ['title'=>'Jasmani B',   'value'=>$s->score_jasmani_b, 'catLabel'=>$bCatLabel,  'catBg'=>$bCatBg,     'icon'=>'fa-dumbbell'],
        ['title'=>'Nilai Jasmani','value'=>$s->score_ukg_avg,'catLabel'=>$jasCatLabel,  'catBg'=>$jasCatBg,   'icon'=>'fa-chart-line'],
      ] as $card)
      <div class="score-card rounded-2xl p-5 text-center">
        <div class="w-9 h-9 rounded-xl flex items-center justify-center mx-auto mb-3" style="background: rgba(153,27,27,.2)">
          <i class="fa-solid {{ $card['icon'] }} text-red-500 text-sm"></i>
        </div>
        <p class="text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-1">{{ $card['title'] }}</p>
        <p class="font-black text-white mb-2 {{ isset($card['big']) ? 'text-4xl' : 'text-3xl' }}">
          {{ $card['value'] !== null ? number_format($card['value'], 1) : '—' }}
        </p>
        <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black text-white"
          style="background: {{ $card['catBg'] }}">
          {{ strtoupper($card['catLabel']) }}
        </span>
      </div>
      @endforeach
    </div>

    {{-- ── DETAIL TABLE + BAR CHART ──────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      {{-- Detail Table (2/3) --}}
      <div class="lg:col-span-2 section-card overflow-hidden">
        <div class="px-5 py-3 border-b border-gray-800">
          <h2 class="text-white font-black text-sm uppercase tracking-widest">Detail Hasil Test Jasmani</h2>
        </div>
        <div class="overflow-x-auto">
          <table class="w-full text-sm">
            <thead>
              <tr class="border-b border-gray-800/60 text-gray-600 text-[10px] uppercase tracking-widest">
                <th class="text-left px-5 py-2.5">Jenis Tes</th>
                <th class="text-center px-3 py-2.5">Hasil</th>
                <th class="text-center px-3 py-2.5">Satuan</th>
                <th class="text-center px-4 py-2.5 min-w-[120px]">Nilai</th>
                <th class="text-center px-3 py-2.5">Ket.</th>
              </tr>
            </thead>
            <tbody class="divide-y divide-gray-900/70">
              @foreach($items as $item)
              @php [$ketLabel, $ketColor, $ketBg] = $cat($item['score']); @endphp
              <tr class="hover:bg-gray-900/40 transition-colors">
                <td class="px-5 py-3">
                  <div class="flex items-center gap-2.5">
                    <i class="fa-solid {{ $item['icon'] }} text-red-700 text-xs w-4"></i>
                    <span class="text-white font-bold text-sm">{{ strtoupper($item['label']) }}</span>
                  </div>
                </td>
                <td class="px-3 py-3 text-center">
                  <span class="text-white font-black text-base">{{ $item['raw'] ?? '—' }}</span>
                </td>
                <td class="px-3 py-3 text-center text-gray-500 text-xs font-bold uppercase">{{ $item['unit'] }}</td>
                <td class="px-4 py-3">
                  <div class="flex items-center gap-2">
                    <div class="flex-1 h-2 rounded-full overflow-hidden" style="background:#1f2937">
                      <div class="h-full rounded-full bar-fill" style="width:{{ $item['score'] ?? 0 }}%; background: linear-gradient(90deg, #991b1b, #dc2626)"></div>
                    </div>
                    <span class="text-white font-black text-sm w-8 text-right">{{ $item['score'] ?? '—' }}</span>
                  </div>
                </td>
                <td class="px-3 py-3 text-center">
                  <span class="ket-badge {{ $ketColor }}" style="background:{{ $ketBg }}20; border: 1px solid {{ $ketBg }}">
                    <i class="fa-solid fa-circle text-[6px]"></i> {{ $ketLabel }}
                  </span>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>

      {{-- Bar Chart (1/3) --}}
      <div class="section-card p-5">
        <h2 class="text-white font-black text-sm uppercase tracking-widest mb-4">Grafik Hasil Test</h2>
        <canvas id="itemChart" style="max-height:280px"></canvas>
      </div>
    </div>

    {{-- ── RINGKASAN + TOTAL + CATATAN ───────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      {{-- Ringkasan Data --}}
      <div class="section-card p-5">
        <h2 class="text-gray-400 text-xs uppercase tracking-widest font-bold mb-4">Ringkasan Data</h2>

        {{-- Jasmani 80% --}}
        <div class="mb-4">
          <div class="flex items-center justify-between mb-1.5">
            <span class="text-sm font-bold text-blue-400">Jasmani (80%)</span>
            <span class="text-white font-black text-lg">{{ $jasmaniBob }}</span>
          </div>
          <div class="h-2 rounded-full overflow-hidden" style="background:#1f2937">
            <div class="h-full rounded-full bar-fill" style="width:{{ $jasmaniBob }}%; background: linear-gradient(90deg,#1d4ed8,#3b82f6)"></div>
          </div>
        </div>

        {{-- Renang 20% --}}
        <div class="mb-5">
          <div class="flex items-center justify-between mb-1.5">
            <span class="text-sm font-bold text-green-400">Renang (20%)</span>
            <span class="text-white font-black text-lg">{{ $renangKont }}</span>
          </div>
          <div class="h-2 rounded-full overflow-hidden" style="background:#1f2937">
            <div class="h-full rounded-full bar-fill" style="width:{{ $renangKont * 5 }}%; background: linear-gradient(90deg,#065f46,#10b981)"></div>
          </div>
        </div>

        <div class="border-t border-gray-800 pt-4 flex items-center justify-between">
          <div class="text-xs text-gray-600 uppercase tracking-widest font-bold">
            <p>{{ $jasmaniBob }}</p>
            <p class="text-gray-700 text-[10px]">+ {{ $renangKont }}</p>
          </div>
          <div class="text-right">
            <p class="text-gray-600 text-[10px] uppercase tracking-widest">Total</p>
            <p class="text-red-500 font-black text-3xl">{{ number_format($s->score_final, 1) }}</p>
          </div>
        </div>
      </div>

      {{-- Total Nilai Akhir --}}
      <div class="section-card p-5 flex flex-col items-center justify-center text-center">
        <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-3">Total Nilai Akhir</p>
        <p class="font-black text-white mb-3" style="font-size: 56px; line-height:1">
          {{ number_format($s->score_final ?? 0, 1) }}
        </p>

        @php
          $stars = match(true) {
            ($s->score_final ?? 0) >= 80 => 3,
            ($s->score_final ?? 0) >= 65 => 2,
            default => 1,
          };
        @endphp
        <div class="flex gap-1 mb-3">
          @for($i = 0; $i < 3; $i++)
          <i class="fa-solid fa-star text-xl {{ $i < $stars ? 'text-yellow-400' : 'text-gray-700' }}"></i>
          @endfor
        </div>
        <span class="px-4 py-1.5 rounded-full text-sm font-black text-white"
          style="background:{{ $finalCatBg }}">
          KATEGORI {{ strtoupper($finalCatLabel) }}
        </span>
        <p class="text-gray-600 text-xs mt-3 italic">"Disiplin hari ini, prestasi di masa depan"</p>
      </div>

      {{-- Catatan & Rekomendasi --}}
      <div class="section-card p-5">
        <h2 class="text-gray-400 text-xs uppercase tracking-widest font-bold mb-4">
          <i class="fa-solid fa-clipboard-check text-red-700 mr-1.5"></i>Catatan & Rekomendasi
        </h2>
        <ul class="space-y-2.5">
          @foreach($recs as $rec)
          <li class="flex items-start gap-2.5">
            <i class="fa-solid fa-circle-check text-green-500 text-sm flex-shrink-0 mt-0.5"></i>
            <span class="text-gray-300 text-sm leading-snug">{{ $rec }}</span>
          </li>
          @endforeach
        </ul>

        {{-- Active Injuries --}}
        @if($activeInjuries->isNotEmpty())
        <div class="mt-4 pt-4 border-t border-gray-800">
          <p class="text-orange-400 text-xs font-bold uppercase tracking-widest mb-2">
            <i class="fa-solid fa-triangle-exclamation mr-1"></i> Riwayat Cedera Aktif
          </p>
          @foreach($activeInjuries as $inj)
          <div class="flex items-center gap-2 mb-1">
            <i class="fa-solid fa-circle text-orange-700 text-[6px]"></i>
            <span class="text-orange-300 text-xs">{{ $inj->injury_type }}</span>
          </div>
          @endforeach
        </div>
        @endif
      </div>
    </div>

    @else
    {{-- No scores yet --}}
    <div class="section-card p-16 text-center">
      <i class="fa-solid fa-clipboard-list text-gray-700 text-5xl mb-4"></i>
      <p class="text-gray-400 font-black text-lg mb-1">Belum Ada Penilaian</p>
      <p class="text-gray-600 text-sm">Coach akan menginput nilai setelah sesi latihan selesai.</p>
    </div>
    @endif

    {{-- ── TREND CHART (semua parameter) ─────────────────── --}}
    @if($allScores->count() > 1)
    <div class="section-card p-5">
      <div class="flex items-center justify-between mb-4">
        <div>
          <h2 class="text-white font-black text-sm uppercase tracking-widest">Tren Perkembangan</h2>
          <p class="text-gray-600 text-xs mt-0.5">Perbandingan nilai akhir antar sesi</p>
        </div>
        <div class="flex items-center gap-3 text-xs">
          <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm inline-block bg-red-700"></span><span class="text-gray-500">Nilai Akhir</span></span>
          <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm inline-block bg-blue-700"></span><span class="text-gray-500">Jasmani A</span></span>
          <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded-sm inline-block bg-orange-700"></span><span class="text-gray-500">Jasmani B</span></span>
        </div>
      </div>
      <canvas id="trendChart" height="100"></canvas>
    </div>
    @endif

    {{-- ── PROFILE + BMI SIDEBAR ──────────────────────────── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">

      {{-- Profile Card --}}
      <div class="section-card p-5">
        <h2 class="text-gray-400 text-xs uppercase tracking-widest font-bold mb-4">Profil Saya</h2>
        <div class="flex items-center gap-3 mb-4 pb-4 border-b border-gray-800">
          <div class="w-12 h-12 rounded-xl bg-red-900 flex items-center justify-center font-black text-xl text-white flex-shrink-0">
            {{ strtoupper(substr($user->name, 0, 1)) }}
          </div>
          <div>
            <p class="text-white font-black text-sm">{{ $user->name }}</p>
            <p class="text-gray-500 text-xs">{{ $user->email }}</p>
          </div>
        </div>
        <div class="space-y-2.5">
          @foreach([
            ['Tinggi Badan', ($athlete->height_cm ?? '—') . ' cm', 'fa-ruler-vertical'],
            ['Berat Badan',  ($athlete->weight_kg ?? '—') . ' kg', 'fa-weight-scale'],
            ['Gender',       Str::ucfirst($athlete->gender),        'fa-venus-mars'],
            ['Target',       $athlete->target_institution ?? '—',   'fa-bullseye'],
            ['Tes Atas',     $athlete->upper_body_test,             'fa-arrow-up'],
          ] as [$lbl, $val, $ico])
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2 text-gray-500 text-xs">
              <i class="fa-solid {{ $ico }} text-gray-700 w-3"></i> {{ $lbl }}
            </div>
            <span class="text-white font-bold text-sm">{{ $val }}</span>
          </div>
          @endforeach
        </div>
        <a href="https://wa.me/6285603875675" target="_blank"
          class="mt-4 w-full flex items-center justify-center gap-2 py-2.5 rounded-xl bg-green-900/20 border border-green-900/40 text-green-400 hover:bg-green-800 hover:text-white text-xs font-bold transition-all">
          <i class="fa-brands fa-whatsapp"></i> Hubungi Coach
        </a>
      </div>

      {{-- BMI Card --}}
      <div class="section-card p-5">
        <h2 class="text-gray-400 text-xs uppercase tracking-widest font-bold mb-4">Status Kebugaran Umum</h2>
        @if($latestBmi)
        @php
          $bmiColor = match($latestBmi->bmi_status) {
            'Normal' => ['text-green-400','#14532d'], 'Kurang' => ['text-blue-400','#1e3a5f'],
            'Gemuk' => ['text-yellow-400','#451a03'], default => ['text-red-400','#450a0a']
          };
        @endphp
        <div class="text-center py-4">
          <p class="text-5xl font-black {{ $bmiColor[0] }} mb-2">{{ $latestBmi->bmi_value }}</p>
          <span class="px-4 py-1.5 rounded-full text-sm font-black text-white" style="background:{{ $bmiColor[1] }}">
            {{ strtoupper($latestBmi->bmi_status) }}
          </span>
          <p class="text-gray-600 text-xs mt-3">BMI Index</p>
        </div>
        <div class="border-t border-gray-800 pt-4 space-y-2">
          <div class="flex justify-between"><span class="text-gray-600 text-xs">Tinggi</span><span class="text-white text-sm font-bold">{{ $latestBmi->height_cm }} cm</span></div>
          <div class="flex justify-between"><span class="text-gray-600 text-xs">Berat</span><span class="text-white text-sm font-bold">{{ $latestBmi->weight_kg }} kg</span></div>
          <div class="flex justify-between"><span class="text-gray-600 text-xs">Tanggal Ukur</span><span class="text-white text-sm font-bold">{{ $latestBmi->recorded_date->format('d M Y') }}</span></div>
        </div>
        @else
        <div class="text-center py-8">
          <i class="fa-solid fa-weight-scale text-gray-700 text-3xl mb-3"></i>
          <p class="text-gray-500 text-sm">Belum ada data BMI</p>
        </div>
        @endif
      </div>

      {{-- Riwayat Singkat --}}
      <div class="section-card overflow-hidden">
        <div class="px-5 py-4 border-b border-gray-800">
          <h2 class="text-gray-400 text-xs uppercase tracking-widest font-bold">Riwayat Sesi</h2>
        </div>
        @if($allScores->isEmpty())
        <div class="text-center py-8"><p class="text-gray-600 text-sm">Belum ada sesi</p></div>
        @else
        <div class="divide-y divide-gray-900">
          @foreach($allScores->take(5) as $sc)
          <a href="{{ route('member.dashboard', ['score_id' => $sc->id]) }}"
            class="flex items-center justify-between px-5 py-3 hover:bg-gray-900/50 transition-colors {{ ($selectedScore && $selectedScore->id === $sc->id) ? 'bg-red-900/10 border-l-2 border-red-700' : '' }}">
            <div>
              <p class="text-white text-sm font-bold">{{ $sc->parameter?->label ?? ($sc->session_label ?? 'Sesi') }}</p>
              <p class="text-gray-600 text-xs">{{ $sc->assessment_date->format('d M Y') }}</p>
            </div>
            <div class="text-right">
              <p class="text-white font-black">{{ number_format($sc->score_final, 1) }}</p>
              <span class="text-[10px] font-black
                {{ $sc->grade === 'A' ? 'text-green-400' : ($sc->grade === 'B' ? 'text-blue-400' : ($sc->grade === 'C' ? 'text-yellow-400' : 'text-red-400')) }}">
                Grade {{ $sc->grade }}
              </span>
            </div>
          </a>
          @endforeach
        </div>
        @endif
      </div>
    </div>

  </div>{{-- end padding wrapper --}}
  @endif {{-- end if athlete --}}
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.color = '#6b7280';
Chart.defaults.borderColor = '#1f2937';

@if($selectedScore && $athlete)
// ── Item Bar Chart ──────────────────────────────────────────────
const itemCtx = document.getElementById('itemChart')?.getContext('2d');
if (itemCtx) {
  const labels = @json(collect($items)->pluck('label')->map(fn($l) => strtoupper($l)));
  const scores = @json(collect($items)->pluck('score')->map(fn($v) => $v ?? 0));

  new Chart(itemCtx, {
    type: 'bar',
    data: {
      labels: labels,
      datasets: [{
        data: scores,
        backgroundColor: scores.map(v =>
          v >= 90 ? 'rgba(34,197,94,.7)' : v >= 75 ? 'rgba(59,130,246,.7)' : v >= 60 ? 'rgba(234,179,8,.7)' : 'rgba(153,27,27,.8)'
        ),
        borderColor: scores.map(v =>
          v >= 90 ? '#22c55e' : v >= 75 ? '#3b82f6' : v >= 60 ? '#eab308' : '#991b1b'
        ),
        borderWidth: 1,
        borderRadius: 6,
        borderSkipped: false,
      }]
    },
    options: {
      indexAxis: 'y',
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#111', borderColor: '#374151', borderWidth: 1,
          titleColor: '#fff', bodyColor: '#9ca3af', padding: 10,
          callbacks: { label: ctx => ` Nilai: ${ctx.parsed.x}` }
        },
        datalabels: { display: false }
      },
      scales: {
        x: { min: 0, max: 100, grid: { color: '#1f2937' }, ticks: { color: '#6b7280', font: { size: 10 } } },
        y: { grid: { display: false }, ticks: { color: '#d1d5db', font: { size: 10, weight: 'bold' } } }
      }
    }
  });
}
@endif

@if($allScores->count() > 1)
// ── Trend Chart ─────────────────────────────────────────────────
const trendCtx = document.getElementById('trendChart')?.getContext('2d');
if (trendCtx) {
  const tLabels = @json($allScores->reverse()->values()->map(fn($s) => $s->parameter?->label ?? ($s->session_label ?? 'Sesi')));
  const tFinal  = @json($allScores->reverse()->values()->map(fn($s) => $s->score_final));
  const tLari   = @json($allScores->reverse()->values()->map(fn($s) => $s->score_lari));
  const tJasB   = @json($allScores->reverse()->values()->map(fn($s) => $s->score_jasmani_b));

  new Chart(trendCtx, {
    type: 'bar',
    data: {
      labels: tLabels,
      datasets: [
        { label: 'Nilai Akhir', data: tFinal, backgroundColor: 'rgba(153,27,27,.8)',  borderColor: '#991b1b', borderWidth: 1, borderRadius: 5, borderSkipped: false },
        { label: 'Jasmani A',   data: tLari,  backgroundColor: 'rgba(29,78,216,.6)',   borderColor: '#1d4ed8', borderWidth: 1, borderRadius: 5, borderSkipped: false },
        { label: 'Jasmani B',   data: tJasB,  backgroundColor: 'rgba(194,65,12,.6)',   borderColor: '#c2410c', borderWidth: 1, borderRadius: 5, borderSkipped: false },
      ]
    },
    options: {
      responsive: true,
      maintainAspectRatio: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          backgroundColor: '#111', borderColor: '#374151', borderWidth: 1,
          titleColor: '#fff', bodyColor: '#9ca3af', padding: 10,
        }
      },
      scales: {
        x: { grid: { color: '#111827' }, ticks: { color: '#6b7280', font: { size: 11 } } },
        y: { min: 0, max: 100, grid: { color: '#111827' }, ticks: { color: '#6b7280', font: { size: 10 }, stepSize: 20 } }
      }
    }
  });
}
@endif
</script>
@endpush
