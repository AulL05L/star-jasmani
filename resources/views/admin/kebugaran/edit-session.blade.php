@extends('layouts.app')
@section('title', 'Edit Sesi #' . $session->session_number)
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    <div class="max-w-2xl">
        <a href="{{ route('admin.kebugaran.period.show', $session->period_id) }}"
            class="inline-flex items-center gap-2 text-zinc-500 hover:text-white text-sm mb-6 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> {{ $session->period->name }}
        </a>

        <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">
            {{ $session->period->athlete->user->name }} · Edit Sesi
        </p>
        <h1 class="text-2xl font-extrabold tracking-tighter mb-6">
            Sesi <span class="text-red-800">#{{ $session->session_number }}</span>
        </h1>

        <form action="{{ route('admin.kebugaran.session.update', $session) }}" method="POST" class="space-y-6">
            @csrf @method('PUT')

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Tanggal Sesi *</label>
                    <input type="date" name="date" value="{{ old('date', $session->date->format('Y-m-d')) }}" required
                        class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Catatan</label>
                    <input type="text" name="notes" value="{{ old('notes', $session->notes) }}" placeholder="Opsional"
                        class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none">
                </div>
            </div>

            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">Nilai Parameter</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                    {{-- BMI: hitung dari BB + TB --}}
                    @php $existingBmi = $session->scoreFor('bmi'); @endphp
                    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4 sm:col-span-2">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="w-8 h-8 rounded-lg bg-red-800/20 flex items-center justify-center">
                                <i class="fa-solid fa-weight-scale text-red-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">BMI</p>
                                <p class="text-zinc-500 text-xs">dihitung otomatis dari berat & tinggi badan</p>
                            </div>
                        </div>
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Berat Badan (kg)</label>
                                <input type="number" id="bmi_bb" step="0.1" min="1" max="300"
                                    value="{{ old('bmi_bb', $athlete->weight_kg) }}"
                                    placeholder="{{ $athlete->weight_kg ?? '70' }}"
                                    oninput="recalcBmi()"
                                    class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-800 focus:outline-none">
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold uppercase tracking-widest text-zinc-500 mb-1.5">Tinggi Badan (cm)</label>
                                <input type="number" id="bmi_tb" step="0.1" min="50" max="250"
                                    value="{{ old('bmi_tb', $athlete->height_cm) }}"
                                    placeholder="{{ $athlete->height_cm ?? '170' }}"
                                    oninput="recalcBmi()"
                                    class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-800 focus:outline-none">
                            </div>
                        </div>
                        <div class="flex items-center justify-between bg-zinc-900 rounded-lg px-4 py-3">
                            <span class="text-zinc-500 text-xs font-bold uppercase tracking-widest">Hasil BMI</span>
                            <div class="flex items-center gap-3">
                                <span id="bmi_result" class="font-black text-2xl text-white">
                                    {{ $existingBmi ? number_format($existingBmi->value, 1) : ($bmiSuggested ? number_format($bmiSuggested, 1) : '—') }}
                                </span>
                                @php $initBmi = $existingBmi?->value ?? $bmiSuggested; @endphp
                                <span id="bmi_category" class="text-xs font-bold px-2 py-0.5 rounded-full bg-zinc-800 text-zinc-400">
                                    @if($initBmi)
                                        {{ $initBmi >= 18.5 && $initBmi <= 24.9 ? 'Normal' : ($initBmi < 18.5 ? 'Kurang' : 'Berlebih') }}
                                    @else — @endif
                                </span>
                            </div>
                        </div>
                        <input type="hidden" name="scores[bmi]" id="bmi_hidden"
                            value="{{ old('scores.bmi', $existingBmi?->value ?? $bmiSuggested) }}">
                    </div>

                    {{-- Parameter lainnya (semua kecuali BMI) --}}
                    @php
                    $hints = [
                        'komposisi_otot'  => 'Baca dari alat BIA / InBody — nilai % massa otot rangka',
                        'komposisi_lemak' => 'Baca dari alat BIA / InBody — nilai % lemak tubuh total',
                        'push_up'         => 'Jumlah repetisi maksimal tanpa henti',
                        'sit_up'          => 'Jumlah repetisi dalam 1 menit',
                        'squat'           => 'Jumlah repetisi maksimal tanpa henti',
                        'sit_and_reach'   => 'Jarak jangkauan (cm) dari posisi duduk kaki lurus',
                        'balke'           => 'VO₂max hasil tes lari 15 menit — rumus: (jarak meter − 133) × 0.172 + 33.3',
                    ];
                    @endphp
                    @foreach($parameters as $key => $param)
                    @if($key === 'bmi') @continue @endif
                    @php $existing = $session->scoreFor($key); @endphp
                    <div class="bg-zinc-950 border border-zinc-800 rounded-xl p-4">
                        <div class="flex items-center gap-3 mb-3">
                            <div class="w-8 h-8 rounded-lg bg-red-800/20 flex items-center justify-center">
                                <i class="fa-solid {{ $param['icon'] }} text-red-500 text-xs"></i>
                            </div>
                            <div>
                                <p class="text-white font-bold text-sm">{{ $param['label'] }}</p>
                                @if($param['unit'])
                                    <p class="text-zinc-500 text-xs">satuan: {{ $param['unit'] }}</p>
                                @endif
                            </div>
                        </div>
                        <input
                            type="number"
                            name="scores[{{ $key }}]"
                            value="{{ old("scores.{$key}", $existing?->value) }}"
                            placeholder="0"
                            step="{{ in_array($key, ['komposisi_otot','komposisi_lemak','sit_and_reach','balke']) ? '0.1' : '1' }}"
                            min="0"
                            class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-800 focus:outline-none"
                        >
                        @if(isset($hints[$key]))
                        <p class="text-zinc-600 text-[11px] mt-2 leading-snug">
                            <i class="fa-solid fa-circle-info text-zinc-700 mr-1"></i>{{ $hints[$key] }}
                        </p>
                        @endif
                    </div>
                    @endforeach

                </div>
            </div>

            <button type="submit"
                class="w-full bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-sm py-3 rounded-xl transition-all">
                Simpan Perubahan
            </button>
        </form>
    </div>

</div>

@push('scripts')
<script>
function recalcBmi() {
    const bb = parseFloat(document.getElementById('bmi_bb').value);
    const tb = parseFloat(document.getElementById('bmi_tb').value);
    const resultEl   = document.getElementById('bmi_result');
    const categoryEl = document.getElementById('bmi_category');
    const hiddenEl   = document.getElementById('bmi_hidden');

    if (bb > 0 && tb > 0) {
        const tbM = tb / 100;
        const bmi = bb / (tbM * tbM);
        const bmiRounded = Math.round(bmi * 10) / 10;

        resultEl.textContent = bmiRounded.toFixed(1);
        hiddenEl.value = bmiRounded.toFixed(1);

        let cat, cls;
        if (bmi < 17)        { cat = 'Sangat Kurang'; cls = 'bg-red-900/50 text-red-400'; }
        else if (bmi < 18.5) { cat = 'Kurang';        cls = 'bg-orange-900/50 text-orange-400'; }
        else if (bmi <= 24.9){ cat = 'Normal';         cls = 'bg-emerald-900/50 text-emerald-400'; }
        else if (bmi <= 27.4){ cat = 'Berlebih';       cls = 'bg-amber-900/50 text-amber-400'; }
        else                 { cat = 'Obesitas';       cls = 'bg-red-900/50 text-red-400'; }

        categoryEl.textContent = cat;
        categoryEl.className = `text-xs font-bold px-2 py-0.5 rounded-full ${cls}`;
        resultEl.className = 'font-black text-2xl ' + (bmi >= 18.5 && bmi <= 24.9 ? 'text-emerald-400' : bmi <= 27.4 ? 'text-amber-400' : 'text-red-400');
    } else {
        resultEl.textContent = '—';
        resultEl.className = 'font-black text-2xl text-white';
        categoryEl.textContent = '—';
        categoryEl.className = 'text-xs font-bold px-2 py-0.5 rounded-full bg-zinc-800 text-zinc-400';
        hiddenEl.value = '';
    }
}
document.addEventListener('DOMContentLoaded', recalcBmi);
</script>
@endpush
@endsection
