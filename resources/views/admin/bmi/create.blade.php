@extends('layouts.app')
@section('title', 'Input BMI')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · BMI</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                INPUT <span class="text-red-800">BMI</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ $athlete->user->name }} · {{ Str::ucfirst($athlete->gender) }}</p>
        </div>
        <a href="{{ route('admin.athletes.show', $athlete) }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    <div class="max-w-2xl space-y-6">

        {{-- Form Input --}}
        <form action="{{ route('admin.bmi.store', $athlete) }}" method="POST" class="space-y-6">
            @csrf

            <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
                <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                    <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">1</span>
                    Data Antropometri
                </h2>

                <div class="grid grid-cols-2 gap-6">
                    {{-- Tinggi --}}
                    <div class="bg-black rounded-xl border border-gray-800 p-4">
                        <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                            <i class="fa-solid fa-ruler-vertical text-red-800"></i> Tinggi Badan
                        </label>
                        <input type="number" name="height_cm"
                            value="{{ old('height_cm', $athlete->height_cm) }}"
                            min="100" max="250" step="0.1" placeholder="170"
                            class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                        <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">cm</span>
                        @error('height_cm')<p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>@enderror
                    </div>

                    {{-- Berat --}}
                    <div class="bg-black rounded-xl border border-gray-800 p-4">
                        <label class="flex items-center gap-2 text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-3">
                            <i class="fa-solid fa-weight-scale text-red-800"></i> Berat Badan
                        </label>
                        <input type="number" name="weight_kg"
                            value="{{ old('weight_kg', $athlete->weight_kg) }}"
                            min="30" max="200" step="0.1" placeholder="65"
                            class="w-full bg-transparent border-b-2 border-gray-700 focus:border-red-800 text-white text-3xl font-black py-2 text-center focus:outline-none transition-colors" />
                        <span class="block text-center text-gray-600 text-xs mt-1 uppercase tracking-widest">kg</span>
                        @error('weight_kg')<p class="text-red-500 text-xs mt-1 text-center">{{ $message }}</p>@enderror
                    </div>
                </div>

                {{-- Preview BMI (JavaScript) --}}
                <div id="bmi-preview" class="hidden bg-black rounded-xl border border-gray-800 p-4 text-center">
                    <p class="text-gray-500 text-xs uppercase tracking-widest mb-1">Preview BMI</p>
                    <p id="bmi-value" class="text-4xl font-black text-white mb-1">—</p>
                    <span id="bmi-status" class="inline-block px-4 py-1 rounded-full text-xs font-black">—</span>
                    <p class="text-gray-600 text-xs mt-2">BMI = Berat (kg) ÷ Tinggi² (m)</p>
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Tanggal Pengukuran <span class="text-red-500">*</span></label>
                    <input type="date" name="recorded_date"
                        value="{{ old('recorded_date', today()->format('Y-m-d')) }}"
                        max="{{ today()->format('Y-m-d') }}" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Catatan</label>
                    <textarea name="notes" rows="2" maxlength="500"
                        placeholder="Catatan kondisi peserta..."
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all resize-none">{{ old('notes') }}</textarea>
                </div>
            </div>

            {{-- Kategori BMI --}}
            <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
                <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4">Kategori BMI (WHO)</h2>
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 text-center text-xs">
                    <div class="bg-blue-900/20 border border-blue-800/30 rounded-xl p-3">
                        <p class="text-blue-400 font-black text-lg">< 17.0</p>
                        <p class="text-blue-400 font-bold mt-1">Kurang</p>
                    </div>
                    <div class="bg-green-900/20 border border-green-800/30 rounded-xl p-3">
                        <p class="text-green-400 font-black text-lg">17–24.9</p>
                        <p class="text-green-400 font-bold mt-1">Normal</p>
                    </div>
                    <div class="bg-yellow-900/20 border border-yellow-800/30 rounded-xl p-3">
                        <p class="text-yellow-400 font-black text-lg">25–29.9</p>
                        <p class="text-yellow-400 font-bold mt-1">Gemuk</p>
                    </div>
                    <div class="bg-red-900/20 border border-red-800/30 rounded-xl p-3">
                        <p class="text-red-400 font-black text-lg">≥ 30</p>
                        <p class="text-red-400 font-bold mt-1">Obesitas</p>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row gap-3 pb-10">
                <button type="submit"
                    class="flex-1 bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all flex items-center justify-center gap-3">
                    <i class="fa-solid fa-calculator"></i> Simpan & Kalkulasi BMI
                </button>
                <a href="{{ route('admin.athletes.show', $athlete) }}"
                    class="flex items-center justify-center gap-2 py-5 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white hover:border-gray-600 text-sm font-bold uppercase tracking-wider transition-all">
                    Batal
                </a>
            </div>
        </form>

        {{-- Riwayat BMI --}}
        @if($athlete->bmiRecords->isNotEmpty())
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clock-rotate-left text-red-800"></i> Riwayat BMI
            </h2>
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left pb-3">Tanggal</th>
                            <th class="text-center pb-3">Tinggi</th>
                            <th class="text-center pb-3">Berat</th>
                            <th class="text-center pb-3">BMI</th>
                            <th class="text-center pb-3">Status</th>
                            <th class="text-center pb-3">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($athlete->bmiRecords as $record)
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="py-3 text-gray-400">{{ $record->recorded_date->format('d M Y') }}</td>
                            <td class="py-3 text-center text-white">{{ $record->height_cm }} cm</td>
                            <td class="py-3 text-center text-white">{{ $record->weight_kg }} kg</td>
                            <td class="py-3 text-center text-white font-black">{{ $record->bmi_value }}</td>
                            <td class="py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-black
                                    {{ $record->bmi_status === 'Normal' ? 'bg-green-900/50 text-green-400' :
                                      ($record->bmi_status === 'Kurang' ? 'bg-blue-900/50 text-blue-400' :
                                      ($record->bmi_status === 'Gemuk' ? 'bg-yellow-900/50 text-yellow-400' :
                                      'bg-red-900/50 text-red-400')) }}">
                                    {{ $record->bmi_status }}
                                </span>
                            </td>
                            <td class="py-3 text-center">
                                <form action="{{ route('admin.bmi.destroy', $record) }}" method="POST"
                                    onsubmit="return confirm('Hapus data BMI ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-7 h-7 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 inline-flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script>
    const heightInput = document.querySelector('[name="height_cm"]');
    const weightInput = document.querySelector('[name="weight_kg"]');
    const preview     = document.getElementById('bmi-preview');
    const bmiValue    = document.getElementById('bmi-value');
    const bmiStatus   = document.getElementById('bmi-status');

    function calcBMI() {
        const h = parseFloat(heightInput.value);
        const w = parseFloat(weightInput.value);
        if (!h || !w || h < 100 || w < 30) { preview.classList.add('hidden'); return; }

        const bmi = (w / ((h / 100) ** 2)).toFixed(2);
        let status, color;

        if (bmi < 17)       { status = 'Kurang';   color = 'bg-blue-900/50 text-blue-400'; }
        else if (bmi < 25)  { status = 'Normal';   color = 'bg-green-900/50 text-green-400'; }
        else if (bmi < 30)  { status = 'Gemuk';    color = 'bg-yellow-900/50 text-yellow-400'; }
        else                { status = 'Obesitas'; color = 'bg-red-900/50 text-red-400'; }

        bmiValue.textContent = bmi;
        bmiStatus.textContent = status;
        bmiStatus.className = `inline-block px-4 py-1 rounded-full text-xs font-black ${color}`;
        preview.classList.remove('hidden');
    }

    heightInput.addEventListener('input', calcBMI);
    weightInput.addEventListener('input', calcBMI);
    calcBMI(); // trigger on load jika ada nilai awal
</script>
@endpush