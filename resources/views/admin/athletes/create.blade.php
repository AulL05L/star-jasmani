@extends('layouts.app')
@section('title', 'Tambah Peserta')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Peserta</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">TAMBAH <span class="text-red-800">PESERTA</span></h1>
        </div>
        <a href="{{ route('admin.athletes.index') }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-900/30 border border-red-800 rounded-xl p-4 mb-6 max-w-3xl">
            @foreach($errors->all() as $error)
                <p class="text-red-400 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.athletes.store') }}" method="POST" class="space-y-6 max-w-3xl">
        @csrf

        {{-- Kartu 1: Data Akun --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">1</span>
                Data Akun
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="name" value="{{ old('name') }}" required
                        placeholder="Nama lengkap peserta"
                        class="w-full bg-black border {{ $errors->has('name') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Email <span class="text-red-500">*</span>
                    </label>
                    <input type="email" name="email" value="{{ old('email') }}" required
                        placeholder="email@example.com"
                        class="w-full bg-black border {{ $errors->has('email') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Password <span class="text-red-500">*</span>
                    </label>
                    <input type="password" name="password" required
                        placeholder="Min. 8 karakter"
                        class="w-full bg-black border {{ $errors->has('password') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone') }}"
                        placeholder="08xxxxxxxxxx"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>
            </div>
        </div>

        {{-- Kartu 2: Data Fisik --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">2</span>
                Data Fisik & Demografis
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Gender <span class="text-red-500">*</span>
                    </label>
                    <select name="gender" required
                        class="w-full bg-black border {{ $errors->has('gender') ? 'border-red-600' : 'border-gray-800' }} text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none cursor-pointer">
                        <option value="">— Pilih Gender —</option>
                        <option value="pria" {{ old('gender') == 'pria' ? 'selected' : '' }}>Pria</option>
                        <option value="wanita" {{ old('gender') == 'wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                    @error('gender')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date') }}"
                        max="{{ now()->format('Y-m-d') }}"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all
                        [color-scheme:dark]" />
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Tinggi Badan (cm)</label>
                    <input type="number" name="height_cm" value="{{ old('height_cm') }}"
                        min="100" max="250" step="0.1" placeholder="170"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Berat Badan (kg)</label>
                    <input type="number" name="weight_kg" value="{{ old('weight_kg') }}"
                        min="30" max="200" step="0.1" placeholder="65"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik') }}"
                        maxlength="20" placeholder="16 digit NIK"
                        class="w-full bg-black border {{ $errors->has('nik') ? 'border-red-600' : 'border-gray-800' }} text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Kartu 3: Target Kedinasan --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">3</span>
                Target Kedinasan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Target Institusi</label>
                    <select name="target_institution" id="target_institution"
                        onchange="syncInstitution(this.value)"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none cursor-pointer">
                        <option value="">— Pilih Institusi —</option>
                        @foreach($institutions as $inst)
                            <option value="{{ $inst->code }}"
                                data-id="{{ $inst->id }}"
                                {{ old('target_institution') == $inst->code ? 'selected' : '' }}>
                                {{ $inst->name }} ({{ $inst->code }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Batch / Angkatan</label>
                    <input type="text" name="batch" value="{{ old('batch') }}"
                        placeholder="Contoh: 2025-Batch-01"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    <p class="text-gray-600 text-[10px] mt-1">Kelompok angkatan latihan</p>
                </div>

            </div>

            {{-- Hidden field institution_id --}}
            <input type="hidden" name="institution_id" id="institution_id" value="{{ old('institution_id') }}" />
        </div>

        {{-- Submit --}}
        <div class="flex flex-col sm:flex-row gap-3 pb-10">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all flex items-center justify-center gap-3 shadow-lg shadow-red-900/30">
                <i class="fa-solid fa-user-plus"></i> Tambah Peserta
            </button>
            <a href="{{ route('admin.athletes.index') }}"
                class="flex items-center justify-center gap-2 py-5 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white hover:border-gray-600 text-sm font-bold uppercase tracking-wider transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
    const institutionMap = @json($institutions->pluck('id', 'code'));

    function syncInstitution(code) {
        document.getElementById('institution_id').value = institutionMap[code] ?? '';
    }

    // Sync on page load kalau ada old value
    document.addEventListener('DOMContentLoaded', function() {
        const oldTarget = document.getElementById('target_institution').value;
        if (oldTarget) syncInstitution(oldTarget);
    });
</script>
@endpush