@extends('layouts.app')

@section('title', 'Edit Nilai Samapta')

@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Edit Penilaian</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                EDIT NILAI <span class="text-red-800">SAMAPTA</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ $samaptaScore->athlete->user->name }} · {{ $samaptaScore->assessment_date->format('d M Y') }}</p>
        </div>
        <a href="{{ route('admin.samapta.show', $samaptaScore) }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.samapta.update', $samaptaScore) }}" method="POST" class="space-y-6 max-w-3xl">
        @csrf
        @method('PUT')

        {{-- Kartu 1: Identitas --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">1</span>
                Identitas Penilaian
            </h2>

            {{-- Pilih Peserta --}}
            <div>
                <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                    Peserta <span class="text-red-500">*</span>
                </label>
                <select name="athlete_id" required
                    class="w-full bg-black border {{ $errors->has('athlete_id') ? 'border-red-600' : 'border-gray-800' }} text-white rounded-xl py-4 px-4 text-sm focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/25 transition-all appearance-none cursor-pointer">
                    <option value="">— Pilih Peserta —</option>
                    @foreach($athletes as $athlete)
                        <option value="{{ $athlete->id }}"
                            data-gender="{{ $athlete->gender }}"
                            {{ $samaptaScore->athlete_id == $athlete->id ? 'selected' : '' }}>
                            {{ $athlete->user->name }} ({{ Str::ucfirst($athlete->gender) }})
                            @if($athlete->batch) · {{ $athlete->batch }} @endif
                        </option>
                    @endforeach
                </select>
                @error('athlete_id')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Tanggal --}}
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Tanggal Penilaian <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="assessment_date"
                        value="{{ old('assessment_date', $samaptaScore->assessment_date->format('Y-m-d')) }}"
                        max="{{ today()->format('Y-m-d') }}" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-4 px-4 text-sm focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/25 transition-all" />
                </div>

                {{-- Label Sesi --}}
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Label Sesi</label>
                    <input type="text" name="session_label"
                        value="{{ old('session_label', $samaptaScore->session_label) }}"
                        placeholder="Contoh: Pre-Test, Mid-Test, Final"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-4 px-4 text-sm focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/25 transition-all" />
                </div>
            </div>

            <input type="hidden" name="institution" value="POLRI" />
        </div>

        {{-- Kartu 2: Komponen UKG --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">2</span>
                Komponen UKG
                <span class="text-gray-600 text-[10px] normal-case tracking-normal font-normal ml-auto">Bobot 80%</span>
            </h2>

            <div class="grid grid-cols-2 gap-4">

                {{-- Lari --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4">
                    <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                        <i class="fa-solid fa-person-running text-red-800"></i> Lari 12 Menit
                    </label>
                    <input type="number" name="raw_lari_meter"
                        value="{{ old('raw_lari_meter', $samaptaScore->raw_lari_meter) }}"
                        min="0" max="6000" placeholder="0"
                        class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">Meter</span>
                </div>

                {{-- Push-Up --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4">
                    <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                        <i class="fa-solid fa-dumbbell text-red-800"></i> Push-Up
                    </label>
                    <input type="number" name="raw_pushup_reps"
                        value="{{ old('raw_pushup_reps', $samaptaScore->raw_pushup_reps) }}"
                        min="0" max="200" placeholder="0"
                        class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">Repetisi</span>
                </div>

                {{-- Sit-Up --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4">
                    <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                        <i class="fa-solid fa-child-reaching text-red-800"></i> Sit-Up
                    </label>
                    <input type="number" name="raw_situp_reps"
                        value="{{ old('raw_situp_reps', $samaptaScore->raw_situp_reps) }}"
                        min="0" max="200" placeholder="0"
                        class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">Repetisi</span>
                </div>

                {{-- Pull-Up / Chin-Up --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4">
                    <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                        <i class="fa-solid fa-arrow-up-from-bracket text-red-800"></i>
                        <span id="pullup-label">
                            {{ $samaptaScore->athlete->gender === 'pria' ? 'Pull-Up' : 'Chin-Up' }}
                        </span>
                    </label>
                    <input type="number" name="raw_pullup_reps"
                        value="{{ old('raw_pullup_reps', $samaptaScore->raw_pullup_reps) }}"
                        min="0" max="100" placeholder="0"
                        class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">Repetisi</span>
                </div>

                {{-- Shuttle Run --}}
                <div class="bg-black rounded-xl border border-gray-800 p-4 col-span-2">
                    <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                        <i class="fa-solid fa-shuffle text-red-800"></i> Shuttle Run
                    </label>
                    <input type="number" name="raw_shuttle_seconds"
                        value="{{ old('raw_shuttle_seconds', $samaptaScore->raw_shuttle_seconds) }}"
                        min="5" max="60" step="0.01" placeholder="0.00"
                        class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                    <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">Detik</span>
                </div>
            </div>
        </div>

        {{-- Kartu 3: Renang --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-4">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-gray-700 rounded-full flex items-center justify-center text-[9px]">3</span>
                Renang
                <span class="text-gray-600 text-[10px] normal-case tracking-normal font-normal ml-auto">Bobot 20%</span>
            </h2>
            <div class="bg-black rounded-xl border border-gray-800 p-4 max-w-xs mx-auto">
                <label class="flex items-center justify-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                    <i class="fa-solid fa-water text-blue-600"></i> Nilai Renang (0–100)
                </label>
                <input type="number" name="raw_renang_seconds"
                    value="{{ old('raw_renang_seconds', $samaptaScore->raw_renang_seconds) }}"
                    min="0" max="100" step="0.01" placeholder="—"
                    class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">Skor (0-100)</span>
            </div>
        </div>

        {{-- Catatan --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Catatan Coach</label>
            <textarea name="notes" rows="3" maxlength="1000"
                placeholder="Catatan observasi lapangan..."
                class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 focus:ring-2 focus:ring-red-800/25 transition-all resize-none">{{ old('notes', $samaptaScore->notes) }}</textarea>
        </div>

        {{-- Submit --}}
        <div class="flex flex-col sm:flex-row gap-3 pb-10">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-floppy-disk"></i>
                Simpan Perubahan & Kalkulasi Ulang
            </button>
            <a href="{{ route('admin.samapta.show', $samaptaScore) }}"
                class="flex items-center justify-center gap-2 py-5 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white hover:border-gray-600 text-sm font-bold uppercase tracking-wider transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const athleteGenders = @json($athletes->mapWithKeys(fn($a) => [$a->id => $a->gender]));

    document.querySelector('[name="athlete_id"]').addEventListener('change', function() {
        const gender = athleteGenders[this.value];
        const label  = document.getElementById('pullup-label');
        if (gender === 'pria')        label.textContent = 'Pull-Up';
        else if (gender === 'wanita') label.textContent = 'Chin-Up';
        else                          label.textContent = 'Pull-Up / Chin-Up';
    });
</script>
@endpush