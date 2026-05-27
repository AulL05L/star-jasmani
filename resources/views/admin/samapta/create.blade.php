@extends('layouts.app')
@section('title', 'Input Nilai Samapta')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-8">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Penilaian</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                Input Nilai <span class="text-red-800">Samapta</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">Kalkulasi otomatis · Bobot sesuai instansi</p>
        </div>
        <a href="{{ route('admin.dashboard') }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Dashboard
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-900/20 border border-red-800/50 rounded-2xl p-4 mb-6 max-w-4xl">
            @foreach($errors->all() as $error)
                <p class="text-red-400 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.samapta.store') }}" method="POST" class="max-w-4xl space-y-4">
        @csrf

        {{-- ── IDENTITAS ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-6 h-6 bg-red-800 rounded-full flex items-center justify-center text-[10px] font-black">1</div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Identitas Penilaian</h2>
            </div>

            {{-- Peserta --}}
            <div class="mb-4">
                <label class="block text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-2">
                    Peserta <span class="text-red-500">*</span>
                </label>
                <select name="athlete_id" id="athlete_id" required
                    onchange="onAthleteChange(this.value)"
                    class="w-full bg-black border {{ $errors->has('athlete_id') ? 'border-red-600' : 'border-gray-800' }} text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none cursor-pointer">
                    <option value="">— Pilih Peserta —</option>
                    @foreach($athletes as $athlete)
                        <option value="{{ $athlete->id }}"
                            data-gender="{{ $athlete->gender }}"
                            {{ old('athlete_id', $selectedAthlete?->id) == $athlete->id ? 'selected' : '' }}>
                            {{ $athlete->user->name }}
                            ({{ $athlete->gender === 'pria' ? 'Putra' : 'Putri' }})
                            @if($athlete->batch) · {{ $athlete->batch }} @endif
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tanggal, Parameter, Label --}}
            <div class="grid grid-cols-3 gap-4 mb-4">
                <div>
                    <label class="block text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-2">Tanggal <span class="text-red-500">*</span></label>
                    <input type="date" name="assessment_date"
                        value="{{ old('assessment_date', today()->format('Y-m-d')) }}"
                        max="{{ today()->format('Y-m-d') }}" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all [color-scheme:dark]" />
                </div>
                <div>
                    <label class="block text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-2">Parameter <span class="text-red-500">*</span></label>
                    <select name="parameter_ke" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none cursor-pointer">
                        <option value="1" {{ old('parameter_ke', 1) == 1 ? 'selected' : '' }}>Parameter 1 — Tes Awal</option>
                        <option value="2" {{ old('parameter_ke') == 2 ? 'selected' : '' }}>Parameter 2</option>
                        <option value="3" {{ old('parameter_ke') == 3 ? 'selected' : '' }}>Parameter 3</option>
                        <option value="4" {{ old('parameter_ke') == 4 ? 'selected' : '' }}>Parameter 4 — Tes Akhir</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-2">Label Sesi</label>
                    <input type="text" name="session_label" value="{{ old('session_label') }}"
                        placeholder="Pre-Test / Mid-Test / Final"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>
            </div>

            {{-- Instansi — auto-fill dari atlet, bisa di-override --}}
            <div id="institution-section" style="{{ old('athlete_id', $selectedAthlete?->id) ? '' : 'display:none' }}">
                <label class="block text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-2">
                    Instansi <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <select name="institution" id="institution_select"
                        onchange="updateInstansiInfo(this)"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none cursor-pointer">
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->code }}"
                                data-ukg="{{ $inst->ukg_weight }}"
                                data-renang="{{ $inst->renang_weight }}"
                                data-passing="{{ $inst->passing_grade }}"
                                {{ old('institution', $selectedAthlete?->institution?->code ?? 'POLRI') == $inst->code ? 'selected' : '' }}>
                                {{ $inst->code }} — Jasmani {{ $inst->ukg_weight }}% · Renang {{ $inst->renang_weight }}%
                            </option>
                        @endforeach
                    </select>
                    <div class="bg-black border border-gray-800 rounded-xl px-4 py-3 flex items-center justify-around">
                        <div class="text-center">
                            <p class="text-gray-600 text-[10px] uppercase tracking-widest">Bobot Jasmani</p>
                            <p id="info-ukg" class="text-red-400 font-black text-xl">80%</p>
                        </div>
                        <span class="text-gray-700 font-black text-xl">+</span>
                        <div class="text-center">
                            <p class="text-gray-600 text-[10px] uppercase tracking-widest">Bobot Renang</p>
                            <p id="info-renang" class="text-blue-400 font-black text-xl">20%</p>
                        </div>
                        <span class="text-gray-700 font-black text-xl">=</span>
                        <div class="text-center">
                            <p class="text-gray-600 text-[10px] uppercase tracking-widest">Total</p>
                            <p class="text-white font-black text-xl">100%</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── JASMANI A: LARI ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-red-800 rounded-full flex items-center justify-center text-[10px] font-black">2</div>
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs">Jasmani A — Lari 12 Menit</h2>
                </div>
                <span class="text-gray-600 text-[10px] uppercase tracking-widest">Semakin jauh = skor lebih tinggi</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-black rounded-xl border border-gray-800 p-5">
                    <label class="block text-gray-500 text-[10px] uppercase tracking-widest mb-3">Jarak Tempuh</label>
                    <input type="number" name="raw_lari_meter" id="raw_lari_meter"
                        value="{{ old('raw_lari_meter') }}"
                        min="0" max="6000" placeholder="0"
                        oninput="previewSkorLari(this.value)"
                        class="w-full bg-transparent border-b-2 border-gray-800 focus:border-red-800 text-white text-4xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-2 uppercase tracking-widest">Meter</span>
                </div>
                <div class="bg-black rounded-xl border border-gray-800 p-5 text-center flex flex-col justify-center">
                    <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">Skor Jasmani A</p>
                    <p id="preview-skor-lari" class="text-5xl font-black text-gray-700">—</p>
                    <p id="preview-label-lari" class="text-gray-600 text-[10px] uppercase tracking-widest mt-2">Masukkan jarak</p>
                    <div class="mt-3 h-1.5 bg-gray-900 rounded-full overflow-hidden">
                        <div id="preview-bar-lari" class="h-full bg-red-700 rounded-full transition-all" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── JASMANI B ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-red-800 rounded-full flex items-center justify-center text-[10px] font-black">3</div>
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs">Jasmani B — Kekuatan & Kelincahan</h2>
                </div>
                <span class="text-gray-600 text-[10px] uppercase tracking-widest">Push-up · Sit-up · Pull-up/Chin-up · Shuttle</span>
            </div>

            <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 mb-3">
                {{-- Push-Up --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4 text-center">
                    <div class="flex items-center justify-center gap-1.5 mb-3">
                        <i class="fa-solid fa-dumbbell text-red-800 text-xs"></i>
                        <span class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Push-Up</span>
                    </div>
                    <input type="number" name="raw_pushup_reps" id="raw_pushup_reps"
                        value="{{ old('raw_pushup_reps') }}" min="0" max="200" placeholder="0"
                        oninput="previewSkorReps('pushup', this.value)"
                        class="w-full bg-transparent border-b-2 border-gray-800 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-gray-600 text-[10px] mt-1 uppercase tracking-widest">Reps</span>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span id="preview-skor-pushup" class="text-sm font-black text-gray-600">—</span>
                        <div class="flex-1 h-1 bg-gray-900 rounded-full overflow-hidden">
                            <div id="preview-bar-pushup" class="h-full bg-red-700 rounded-full" style="width:0%"></div>
                        </div>
                    </div>
                </div>

                {{-- Sit-Up --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4 text-center">
                    <div class="flex items-center justify-center gap-1.5 mb-3">
                        <i class="fa-solid fa-child-reaching text-red-800 text-xs"></i>
                        <span class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Sit-Up</span>
                    </div>
                    <input type="number" name="raw_situp_reps" id="raw_situp_reps"
                        value="{{ old('raw_situp_reps') }}" min="0" max="200" placeholder="0"
                        oninput="previewSkorReps('situp', this.value)"
                        class="w-full bg-transparent border-b-2 border-gray-800 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-gray-600 text-[10px] mt-1 uppercase tracking-widest">Reps</span>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span id="preview-skor-situp" class="text-sm font-black text-gray-600">—</span>
                        <div class="flex-1 h-1 bg-gray-900 rounded-full overflow-hidden">
                            <div id="preview-bar-situp" class="h-full bg-red-700 rounded-full" style="width:0%"></div>
                        </div>
                    </div>
                </div>

                {{-- Pull-Up / Chin-Up --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4 text-center">
                    <div class="flex items-center justify-center gap-1.5 mb-3">
                        <i class="fa-solid fa-arrow-up-from-bracket text-red-800 text-xs"></i>
                        <span id="pullup-label" class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Pull-Up</span>
                    </div>
                    <input type="number" name="raw_pullup_reps" id="raw_pullup_reps"
                        value="{{ old('raw_pullup_reps') }}" min="0" max="200" placeholder="0"
                        oninput="previewSkorReps('pullup', this.value)"
                        class="w-full bg-transparent border-b-2 border-gray-800 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-gray-600 text-[10px] mt-1 uppercase tracking-widest">Reps</span>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span id="preview-skor-pullup" class="text-sm font-black text-gray-600">—</span>
                        <div class="flex-1 h-1 bg-gray-900 rounded-full overflow-hidden">
                            <div id="preview-bar-pullup" class="h-full bg-red-700 rounded-full" style="width:0%"></div>
                        </div>
                    </div>
                </div>

                {{-- Shuttle Run --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4 text-center">
                    <div class="flex items-center justify-center gap-1.5 mb-3">
                        <i class="fa-solid fa-shuffle text-red-800 text-xs"></i>
                        <span class="text-gray-500 text-[10px] uppercase tracking-widest font-bold">Shuttle Run</span>
                    </div>
                    <input type="number" name="raw_shuttle_seconds" id="raw_shuttle_seconds"
                        value="{{ old('raw_shuttle_seconds') }}" min="5" max="60" step="0.01" placeholder="0.00"
                        oninput="previewSkoreShuttle(this.value)"
                        class="w-full bg-transparent border-b-2 border-gray-800 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-gray-600 text-[10px] mt-1 uppercase tracking-widest">Detik</span>
                    <div class="mt-2 flex items-center justify-center gap-2">
                        <span id="preview-skor-shuttle" class="text-sm font-black text-gray-600">—</span>
                        <div class="flex-1 h-1 bg-gray-900 rounded-full overflow-hidden">
                            <div id="preview-bar-shuttle" class="h-full bg-red-700 rounded-full" style="width:0%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Jasmani B Preview --}}
            <div class="bg-red-900/10 border border-red-900/30 rounded-xl px-4 py-3 flex items-center justify-between">
                <span class="text-gray-500 text-xs uppercase tracking-widest">Jasmani B (Rata-rata 4 komponen)</span>
                <span id="preview-jasmani-b" class="text-white font-black text-lg">—</span>
            </div>
        </div>

        {{-- ── RENANG ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <div class="flex items-center justify-between mb-5">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 bg-blue-800 rounded-full flex items-center justify-center text-[10px] font-black">4</div>
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs">Renang</h2>
                    <span class="px-2 py-0.5 rounded-full bg-blue-900/30 text-blue-400 text-[10px] font-bold" id="badge-renang-bobot">Bobot 20%</span>
                </div>
                <span class="text-gray-600 text-[10px] uppercase tracking-widest">Semakin cepat = skor lebih tinggi</span>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div class="bg-black rounded-xl border border-gray-800 p-5">
                    <label class="block text-gray-500 text-[10px] uppercase tracking-widest mb-3">Waktu Renang</label>
                    <input type="number" name="raw_renang_seconds" id="raw_renang_seconds"
                        value="{{ old('raw_renang_seconds') }}"
                        min="10" max="999" step="0.1" placeholder="—"
                        oninput="previewSkoreRenang(this.value)"
                        class="w-full bg-transparent border-b-2 border-gray-800 focus:border-blue-700 text-white text-4xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-2 uppercase tracking-widest">Detik · Kosongkan jika belum diukur</span>
                </div>
                <div class="bg-black rounded-xl border border-gray-800 p-5 text-center flex flex-col justify-center">
                    <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">Skor Renang</p>
                    <p id="preview-skor-renang" class="text-5xl font-black text-gray-700">—</p>
                    <p id="preview-label-renang" class="text-gray-600 text-[10px] uppercase tracking-widest mt-2">Masukkan waktu</p>
                    <div class="mt-3 h-1.5 bg-gray-900 rounded-full overflow-hidden">
                        <div id="preview-bar-fill" class="h-full bg-blue-700 rounded-full transition-all" style="width:0%"></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ── CATATAN ── --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <label class="block text-gray-500 text-[10px] uppercase tracking-widest font-bold mb-3">Catatan Coach (Opsional)</label>
            <textarea name="notes" rows="2" maxlength="1000"
                placeholder="Catatan observasi lapangan, kondisi peserta, kendala, dll..."
                class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all resize-none">{{ old('notes') }}</textarea>
        </div>

        {{-- ── SUBMIT ── --}}
        <div class="flex flex-col sm:flex-row gap-3 pb-10">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg shadow-red-900/30">
                <i class="fa-solid fa-calculator"></i> Simpan & Kalkulasi Nilai
            </button>
            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center justify-center gap-2 py-4 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white text-sm font-bold uppercase tracking-wider transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
const athleteGenders       = @json($athletes->mapWithKeys(fn($a) => [$a->id => $a->gender]));
const athleteInstitutions  = @json($athletes->mapWithKeys(fn($a) => [$a->id => $a->institution?->code ?? 'POLRI']));
let currentGender = 'pria';

function onAthleteChange(id) {
    currentGender = athleteGenders[id] ?? 'pria';
    document.getElementById('pullup-label').textContent = currentGender === 'pria' ? 'Pull-Up' : 'Chin-Up';
    if (id) {
        const instCode   = athleteInstitutions[id] ?? 'POLRI';
        const instSelect = document.getElementById('institution_select');
        if (instSelect) { instSelect.value = instCode; updateInstansiInfo(instSelect); }
        document.getElementById('institution-section').style.display = '';
    }
    previewSkorLari(document.getElementById('raw_lari_meter').value);
    previewSkorReps('pushup', document.getElementById('raw_pushup_reps').value);
    previewSkorReps('situp',  document.getElementById('raw_situp_reps').value);
    previewSkorReps('pullup', document.getElementById('raw_pullup_reps').value);
    previewSkoreShuttle(document.getElementById('raw_shuttle_seconds').value);
    previewSkoreRenang(document.getElementById('raw_renang_seconds').value);
}

function updateInstansiInfo(selectEl) {
    const opt = selectEl.options[selectEl.selectedIndex];
    const ukg    = opt.dataset.ukg    ?? 80;
    const renang = opt.dataset.renang ?? 20;
    document.getElementById('info-ukg').textContent    = ukg + '%';
    document.getElementById('info-renang').textContent = renang + '%';
    document.getElementById('badge-renang-bobot').textContent = 'Bobot ' + renang + '%';
}

// ── Tabel Konversi — identik dengan SamaptaScore.php backend ──
const lariTablePutra = [
    [3444,100],[3422,99],[3401,98],[3380,97],[3369,96],[3338,95],[3317,94],
    [3253,91],[3232,90],[3211,89],[3190,88],[3169,87],[3148,86],[3126,85],
    [3105,84],[3084,83],[3062,82],[3041,81],[3021,80],[2999,79],[2978,78],
    [2957,77],[2936,76],[2914,75],[2893,74],[2872,73],[2851,72],[2830,71],
    [2809,70],[2788,69],[2767,68],[2746,67],[2725,66],[2703,65],[2682,64],
    [2661,63],[2639,62],[2618,61],[2597,60],[2576,59],[2555,58],[2534,57],
    [2513,56],[2491,55],[2470,54],[2449,53],[2428,52],[2407,51],[2385,50],
    [2364,49],[2343,48],[2322,47],[2301,46],[2280,45],[2259,44],[2237,43],
    [2216,42],[2195,41],[2174,40],[2153,39],[2132,38],[2111,37],[2090,36],
    [2069,35],[2048,34],[2026,33],[2005,32],[1984,31],[1962,30],[1941,29],
    [1920,28],[1899,27],[1878,26],[1857,25],[1836,24],[1814,23],[1793,22],
    [1772,21],[1750,20],[1729,19],[1708,18],[1687,17],[1666,16],[1645,15],
    [1624,14],[1603,13],[1582,12],[1561,11],[1539,10],[1518,9],[1497,8],
    [1476,7],[1455,6],[1434,5],[1413,4],[1392,3],[1371,2],[1349,1],[0,1],
];
const lariTablePutri = [
    [3095,100],[3084,99],[3062,98],[3041,97],[3020,96],[2999,95],[2978,94],
    [2957,93],[2936,92],[2914,91],[2893,90],[2872,89],[2851,88],[2830,87],
    [2809,86],[2788,85],[2767,84],[2746,83],[2725,82],[2703,81],[2682,80],
    [2661,79],[2639,78],[2618,77],[2597,76],[2576,75],[2555,74],[2534,73],
    [2513,72],[2491,71],[2470,70],[2449,69],[2428,68],[2407,67],[2385,66],
    [2364,65],[2343,64],[2322,63],[2301,62],[2280,61],[2259,60],[2237,59],
    [2216,58],[2195,57],[2174,56],[2153,55],[2132,54],[2111,53],[2090,52],
    [2069,51],[2048,50],[2026,49],[2005,48],[1984,47],[1962,46],[1941,45],
    [1920,44],[1899,43],[1878,42],[1857,41],[1836,40],[1814,39],[1793,38],
    [1772,37],[1750,36],[1729,35],[1708,34],[1687,33],[1666,32],[1645,31],
    [1624,30],[1603,29],[1582,28],[1561,27],[1539,26],[1518,25],[1497,24],
    [1476,23],[1455,22],[1434,21],[1412,20],[1391,19],[1370,18],[1349,17],
    [1328,16],[1307,15],[1286,14],[1265,13],[1244,12],[1223,11],[1202,10],
    [1181,9],[1160,8],[1139,7],[1118,6],[1097,5],[1076,4],[1055,3],[1034,2],[1013,1],[0,1],
];

const repsTablePutra = {
    pushup: [
        [42,100],[41,97],[40,94],[39,91],[38,88],[37,85],[36,82],[35,79],
        [34,76],[33,73],[32,70],[31,67],[30,64],[29,61],[28,58],[27,55],
        [26,52],[25,50],[24,48],[23,46],[22,44],[21,42],[20,40],[19,38],
        [18,36],[17,34],[16,32],[15,29],[14,26],[13,23],[12,21],[11,19],
        [10,17],[9,15],[8,13],[7,11],[6,9],[5,7],[4,6],[3,5],[2,4],[1,3],[0,3],
    ],
    situp: [
        [40,100],[39,96],[38,92],[37,88],[36,84],[35,80],[34,76],[33,72],
        [32,68],[31,64],[30,60],[29,56],[28,52],[27,48],[26,44],[25,41],
        [24,38],[23,35],[22,32],[21,30],[20,28],[19,26],[18,24],[17,22],
        [16,20],[15,18],[14,16],[13,14],[12,12],[11,10],[10,8],[9,6],[8,4],[7,2],[6,1],
    ],
    pullup: [
        [17,100],[16,94],[15,88],[14,82],[13,76],[12,70],[11,64],[10,58],
        [9,52],[8,46],[7,39],[6,32],[5,26],[4,20],[3,14],[2,8],[1,4],[0,4],
    ],
};
const repsTablePutri = {
    // Push-Up Putri — max 37 reps
    pushup: [
        [37,100],[36,97],[35,93],[34,90],[33,86],[32,83],[31,79],[30,76],
        [29,72],[28,69],[27,65],[26,62],[25,58],[24,55],[23,51],[22,48],
        [21,44],[20,41],[19,37],[18,34],[17,30],[16,27],[15,23],[14,20],
        [13,16],[12,13],[11,9],[10,6],[9,2],[1,1],
    ],
    situp: [
        [50,100],[49,96],[48,93],[47,91],[46,87],[45,84],[44,82],[43,78],
        [42,75],[41,73],[40,69],[39,66],[38,64],[37,60],[36,57],[35,55],
        [34,51],[33,48],[32,46],[31,42],[30,39],[29,37],[28,33],[27,29],
        [26,26],[25,24],[24,21],[23,19],[22,15],[21,12],[20,10],[19,6],[18,3],[17,1],
    ],
    // Chin-Up Putri — detik hang
    pullup: [
        [72,100],[71,97],[70,95],[69,92],[68,90],[67,87],[66,85],[65,82],
        [64,80],[63,77],[62,75],[61,72],[60,70],[59,67],[58,65],[57,62],
        [56,60],[55,57],[54,55],[53,52],[52,50],[51,47],[50,45],[49,42],
        [48,40],[47,37],[46,35],[45,32],[44,30],[43,27],[42,25],[41,22],
        [40,20],[39,17],[38,15],[37,12],[36,10],[35,7],[34,5],[33,2],[0,2],
    ],
};

const shuttleTablePutra = [
    [16.2,100],[16.3,99],[16.4,98],[16.5,97],[16.6,96],[16.7,95],
    [16.8,94],[16.9,92],[17.0,90],[17.1,88],[17.2,86],[17.3,84],
    [17.4,82],[17.5,80],[17.6,78],[17.7,76],[17.8,74],[17.9,72],
    [18.0,70],[18.1,68],[18.2,66],[18.3,64],[18.4,62],[18.5,60],
    [18.6,58],[18.7,56],[18.8,54],[18.9,52],[19.0,51],[19.1,49],
    [19.2,47],[19.3,45],[19.4,43],[19.5,41],[19.6,40],[19.7,38],
    [19.8,36],[19.9,34],[20.0,32],[20.1,30],[20.2,28],[20.3,26],
    [20.4,24],[20.5,22],[20.6,21],[20.7,19],[20.8,17],[20.9,15],
    [21.0,13],[21.1,11],[21.2,10],[21.3,8],[21.4,6],[21.5,4],
    [21.6,2],[21.7,1],[999,1],
];
const shuttleTablePutri = [
    [17.6,100],[17.7,99],[17.8,98],[17.9,97],[18.0,96],[18.1,95],
    [18.2,94],[18.3,93],[18.4,92],[18.5,91],[18.6,90],[18.7,89],
    [18.8,88],[18.9,87],[19.0,86],[19.1,85],[19.2,84],[19.3,83],
    [19.4,82],[19.5,81],[19.6,80],[19.7,79],[19.8,78],[19.9,77],
    [20.0,76],[20.1,75],[20.2,74],[20.3,73],[20.4,72],[20.5,71],
    [20.6,70],[20.7,69],[20.8,68],[20.9,67],[21.0,66],[21.1,65],
    [21.2,64],[21.3,63],[21.4,62],[21.5,61],[21.6,60],[21.7,59],
    [21.8,58],[21.9,57],[22.0,56],[22.1,55],[22.2,54],[22.3,53],
    [22.4,52],[22.5,51],[22.6,50],[22.7,49],[22.8,48],[22.9,47],
    [23.0,46],[23.1,45],[23.2,44],[23.3,43],[23.4,42],[23.5,41],
    [23.6,40],[23.7,39],[23.8,38],[23.9,37],[24.0,36],[24.1,35],
    [24.2,34],[24.3,33],[24.4,32],[24.5,31],[24.6,30],[24.7,29],[24.8,28],
    [24.9,27],[25.0,26],[25.1,25],[25.2,24],[25.3,23],[25.4,22],
    [25.5,21],[25.6,20],[25.7,19],[25.8,18],[25.9,17],[26.0,16],
    [26.1,15],[26.2,14],[26.3,13],[26.4,12],[26.5,11],[26.6,10],
    [26.7,9],[26.8,8],[26.9,7],[27.0,6],[27.1,5],[27.2,4],[27.3,3],
    [27.4,2],[27.5,1],[999,1],
];
const renangTablePutra = [
    [14.0,100],[14.7,99],[15.4,98],[16.1,97],[16.8,96],[17.5,95],
    [18.2,94],[18.9,93],[19.6,92],[20.3,91],[21.0,90],[21.7,89],
    [22.4,88],[23.1,87],[23.8,86],[24.5,85],[25.2,84],[25.9,83],
    [26.6,82],[27.3,81],[28.0,80],[28.7,79],[29.4,78],[30.1,77],
    [30.8,76],[31.5,75],[32.2,74],[32.9,73],[33.6,72],[34.3,71],
    [35.0,70],[35.7,69],[36.4,68],[37.1,67],[37.8,66],[38.5,65],
    [39.2,64],[39.9,63],[40.6,62],[41.3,61],[42.0,60],[42.7,59],
    [43.4,58],[44.1,57],[44.8,56],[45.5,55],[46.2,54],[46.9,53],
    [47.6,52],[48.3,51],[49.0,50],[49.7,49],[50.4,48],[51.1,47],
    [51.8,46],[52.5,45],[53.2,44],[53.9,43],[54.6,42],[55.0,41],[999,41],
];
const renangTablePutri = [
    [20.0,100],[20.7,99],[21.3,98],[22.0,97],[22.7,96],[23.4,95],
    [24.0,94],[24.7,93],[25.4,92],[26.0,91],[26.7,90],[27.4,89],
    [28.0,88],[28.7,87],[29.4,86],[30.1,85],[30.7,84],[31.4,83],
    [32.1,82],[32.7,81],[33.4,80],[34.1,79],[34.7,78],[35.4,77],
    [36.1,76],[36.8,75],[37.4,74],[38.1,73],[38.8,72],[39.4,71],
    [40.1,70],[40.8,69],[41.4,68],[42.1,67],[42.8,66],[43.5,65],
    [44.1,64],[44.8,63],[45.5,62],[46.1,61],[46.8,60],[47.5,59],
    [48.1,58],[48.8,57],[49.5,56],[50.2,54],[50.5,53],[51.2,52],
    [52.8,51],[53.5,50],[54.2,49],[54.8,48],[55.5,47],[56.2,46],
    [56.9,45],[57.5,44],[58.2,43],[58.9,42],[60.0,41],[999,41],
];

// ── Helpers ──
function calcLookup(val, tablePutra, tablePutri) {
    const table = currentGender === 'pria' ? tablePutra : tablePutri;
    for (const [threshold, poin] of table) {
        if (val >= threshold) return poin;
    }
    return 1;
}
function calcShuttleRenang(val, tablePutra, tablePutri) {
    const table = currentGender === 'pria' ? tablePutra : tablePutri;
    for (const [threshold, poin] of table) {
        if (val <= threshold) return poin;
    }
    return 0;
}
function calcReps(type, val) {
    const table = currentGender === 'pria' ? repsTablePutra[type] : repsTablePutri[type];
    for (const [threshold, poin] of table) {
        if (val >= threshold) return poin;
    }
    return 1;
}

function updatePreview(elId, barId, labelId, skor) {
    const el  = document.getElementById(elId);
    const bar = document.getElementById(barId);
    const lbl = labelId ? document.getElementById(labelId) : null;
    if (!el) return;
    if (skor === null) {
        el.textContent = '—';
        el.className = el.className.replace(/text-(green|blue|yellow|red|gray)-\d+/g, 'text-gray-700');
        if (bar) bar.style.width = '0%';
        if (lbl) lbl.textContent = 'Masukkan nilai';
        return;
    }
    el.textContent = skor;
    if (bar) bar.style.width = Math.min(skor, 100) + '%';
    const color = skor >= 80 ? 'text-green-400' : skor >= 70 ? 'text-blue-400' : skor >= 60 ? 'text-yellow-400' : 'text-red-400';
    el.className = el.className.replace(/text-(green|blue|yellow|red|gray)-\d+/g, color);
    if (lbl) lbl.textContent = skor >= 80 ? 'Sangat Baik' : skor >= 70 ? 'Baik' : skor >= 60 ? 'Cukup' : 'Kurang';
}

function previewSkorLari(val) {
    if (!val || isNaN(val)) { updatePreview('preview-skor-lari','preview-bar-lari','preview-label-lari',null); return; }
    updatePreview('preview-skor-lari','preview-bar-lari','preview-label-lari', calcLookup(parseFloat(val), lariTablePutra, lariTablePutri));
}
function previewSkorReps(type, val) {
    const skor = (!val || isNaN(val)) ? null : calcReps(type, parseFloat(val));
    updatePreview(`preview-skor-${type}`, `preview-bar-${type}`, null, skor);
    updateJasmaniB();
}
function updateJasmaniB() {
    const vals = ['pushup','situp','pullup'].map(t => {
        const raw = document.getElementById(`raw_${t}_reps`).value;
        return raw ? calcReps(t, parseFloat(raw)) : null;
    });
    const rawShuttle  = document.getElementById('raw_shuttle_seconds').value;
    vals.push(rawShuttle ? calcShuttleRenang(parseFloat(rawShuttle), shuttleTablePutra, shuttleTablePutri) : null);
    const filled = vals.filter(v => v !== null);
    const el = document.getElementById('preview-jasmani-b');
    el.textContent = filled.length === 0 ? '—' : (filled.reduce((a,b) => a+b, 0) / filled.length).toFixed(1);
}
function previewSkoreShuttle(val) {
    if (!val || isNaN(val)) { updatePreview('preview-skor-shuttle','preview-bar-shuttle',null,null); updateJasmaniB(); return; }
    updatePreview('preview-skor-shuttle','preview-bar-shuttle',null, calcShuttleRenang(parseFloat(val), shuttleTablePutra, shuttleTablePutri));
    updateJasmaniB();
}
function previewSkoreRenang(val) {
    if (!val || isNaN(val)) { updatePreview('preview-skor-renang','preview-bar-fill','preview-label-renang',null); return; }
    updatePreview('preview-skor-renang','preview-bar-fill','preview-label-renang', calcShuttleRenang(parseFloat(val), renangTablePutra, renangTablePutri));
}

document.addEventListener('DOMContentLoaded', () => {
    const athleteId  = document.getElementById('athlete_id').value;
    const instSelect = document.getElementById('institution_select');
    if (athleteId) onAthleteChange(athleteId);
    else if (instSelect) updateInstansiInfo(instSelect);
});
</script>
@endpush