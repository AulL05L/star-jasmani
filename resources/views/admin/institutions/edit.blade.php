@extends('layouts.app')
@section('title', 'Edit Instansi — ' . $institution->code)
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Pengaturan Instansi</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                Edit <span class="text-red-800">{{ $institution->code }}</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ $institution->name }}</p>
        </div>
        <a href="{{ route('admin.institutions.index') }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-900/30 border border-red-800 rounded-xl p-4 mb-6 max-w-2xl">
            @foreach($errors->all() as $error)
                <p class="text-red-400 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.institutions.update', $institution) }}" method="POST" class="max-w-2xl space-y-5">
        @csrf
        @method('PATCH')

        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fa-solid fa-building text-red-800"></i> Informasi Instansi
            </h2>

            <div>
                <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Kode Instansi</label>
                <input type="text" value="{{ $institution->code }}" disabled
                    class="w-full bg-gray-900 border border-gray-800 text-gray-500 rounded-xl py-3.5 px-4 text-sm cursor-not-allowed" />
                <p class="text-gray-600 text-[10px] mt-1">Kode tidak bisa diubah</p>
            </div>

            <div>
                <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                    Nama Instansi <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name', $institution->name) }}" required
                    class="w-full bg-black border {{ $errors->has('name') ? 'border-red-600' : 'border-gray-800' }} text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
            </div>
        </div>

        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fa-solid fa-scale-balanced text-red-800"></i> Bobot Penilaian
            </h2>
            <p class="text-gray-500 text-xs">Total bobot UKG + Renang harus = 100%</p>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Bobot UKG (Jasmani) % <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="ukg_weight"
                        value="{{ old('ukg_weight', $institution->ukg_weight) }}"
                        min="0" max="100" step="1" required
                        oninput="updateRenang(this.value)"
                        class="w-full bg-black border {{ $errors->has('ukg_weight') ? 'border-red-600' : 'border-gray-800' }} text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('ukg_weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Bobot Renang % <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="renang_weight" id="renang_weight"
                        value="{{ old('renang_weight', $institution->renang_weight) }}"
                        min="0" max="100" step="1" required
                        class="w-full bg-black border {{ $errors->has('renang_weight') ? 'border-red-600' : 'border-gray-800' }} text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('renang_weight')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>

            {{-- Preview bar --}}
            <div>
                <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                    <span class="text-red-500 font-bold" id="label-ukg">UKG {{ old('ukg_weight', $institution->ukg_weight) }}%</span>
                    <span class="text-blue-400 font-bold" id="label-renang">Renang {{ old('renang_weight', $institution->renang_weight) }}%</span>
                </div>
                <div class="h-4 bg-gray-900 rounded-full overflow-hidden flex">
                    <div id="bar-ukg" class="bg-red-800 h-full transition-all" style="width: {{ old('ukg_weight', $institution->ukg_weight) }}%"></div>
                    <div id="bar-renang" class="bg-blue-700 h-full transition-all" style="width: {{ old('renang_weight', $institution->renang_weight) }}%"></div>
                </div>
                <p id="total-warning" class="text-[10px] mt-1 hidden text-red-400 font-bold">
                    ⚠ Total harus 100%
                </p>
            </div>

            {{-- Formula preview --}}
            <div class="bg-gray-900 rounded-xl px-4 py-3 text-sm">
                <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-1">Preview Formula</p>
                <p class="text-white font-bold">
                    Nilai Akhir = (Jasmani × <span id="prev-ukg" class="text-red-400">{{ $institution->ukg_weight }}%</span>)
                    + (Renang × <span id="prev-renang" class="text-blue-400">{{ $institution->renang_weight }}%</span>)
                </p>
            </div>
        </div>

        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fa-solid fa-flag-checkered text-red-800"></i> Passing Grade
            </h2>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Nilai Minimum Kelulusan <span class="text-red-500">*</span>
                    </label>
                    <input type="number" name="passing_grade"
                        value="{{ old('passing_grade', $institution->passing_grade) }}"
                        min="0" max="100" step="0.1" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    <p class="text-gray-600 text-[10px] mt-1">Ditampilkan sebagai garis batas di grafik trend kelulusan</p>
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Status</label>
                    <div class="flex items-center gap-3 mt-3">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="is_active" value="1"
                                {{ old('is_active', $institution->is_active) ? 'checked' : '' }}
                                class="w-4 h-4 rounded border-gray-700 bg-black text-red-800 focus:ring-red-800 focus:ring-offset-black" />
                            <span class="text-white text-sm font-bold">Instansi Aktif</span>
                        </label>
                    </div>
                    <p class="text-gray-600 text-[10px] mt-1">Nonaktifkan jika instansi tidak digunakan</p>
                </div>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pb-10">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.institutions.index') }}"
                class="flex items-center justify-center gap-2 py-5 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white text-sm font-bold uppercase tracking-wider transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    function updateRenang(ukgVal) {
        const ukg    = parseFloat(ukgVal) || 0;
        const renang = 100 - ukg;

        document.getElementById('renang_weight').value = renang;
        document.getElementById('bar-ukg').style.width    = ukg + '%';
        document.getElementById('bar-renang').style.width = renang + '%';
        document.getElementById('label-ukg').textContent    = 'UKG ' + ukg + '%';
        document.getElementById('label-renang').textContent = 'Renang ' + renang + '%';
        document.getElementById('prev-ukg').textContent    = ukg + '%';
        document.getElementById('prev-renang').textContent = renang + '%';

        const warning = document.getElementById('total-warning');
        if (ukg < 0 || ukg > 100) {
            warning.classList.remove('hidden');
        } else {
            warning.classList.add('hidden');
        }
    }
</script>
@endpush