@extends('layouts.app')
@section('title', 'Edit Peserta')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Edit Peserta</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">EDIT <span class="text-red-800">PESERTA</span></h1>
            <p class="text-gray-500 text-sm mt-1">{{ $athlete->user->name }}</p>
        </div>
        <a href="{{ route('admin.athletes.show', $athlete) }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    <form action="{{ route('admin.athletes.update', $athlete) }}" method="POST" class="space-y-6 max-w-3xl">
        @csrf
        @method('PUT')

        {{-- Akun --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">1</span>
                Data Akun
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', $athlete->user->name) }}" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Email <span class="text-red-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email', $athlete->user->email) }}" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Password Baru <span class="text-gray-600 text-[10px] normal-case">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all"
                        placeholder="Min. 8 karakter" />
                    @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $athlete->phone) }}"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all"
                        placeholder="08xxxxxxxxxx" />
                </div>
            </div>
        </div>

        {{-- Data Fisik --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">2</span>
                Data Fisik & Demografis
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Gender <span class="text-red-500">*</span></label>
                    <select name="gender" required
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none">
                        <option value="pria" {{ old('gender', $athlete->gender) == 'pria' ? 'selected' : '' }}>Pria</option>
                        <option value="wanita" {{ old('gender', $athlete->gender) == 'wanita' ? 'selected' : '' }}>Wanita</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Tanggal Lahir</label>
                    <input type="date" name="birth_date" value="{{ old('birth_date', $athlete->birth_date?->format('Y-m-d')) }}"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Tinggi Badan (cm)</label>
                    <input type="number" name="height_cm" value="{{ old('height_cm', $athlete->height_cm) }}"
                        min="100" max="250" step="0.1"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Berat Badan (kg)</label>
                    <input type="number" name="weight_kg" value="{{ old('weight_kg', $athlete->weight_kg) }}"
                        min="30" max="200" step="0.1"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">NIK</label>
                    <input type="text" name="nik" value="{{ old('nik', $athlete->nik) }}"
                        maxlength="20"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    @error('nik')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>
            </div>
        </div>

        {{-- Kedinasan --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <span class="w-5 h-5 bg-red-800 rounded-full flex items-center justify-center text-[9px]">3</span>
                Target Kedinasan
            </h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Target Institusi</label>
                    <select name="target_institution"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none">
                        <option value="">— Pilih Institusi —</option>
                        <option value="POLRI" {{ old('target_institution', $athlete->target_institution) == 'POLRI' ? 'selected' : '' }}>POLRI</option>
                        <option value="TNI-AD" {{ old('target_institution', $athlete->target_institution) == 'TNI-AD' ? 'selected' : '' }}>TNI-AD</option>
                        <option value="TNI-AL" {{ old('target_institution', $athlete->target_institution) == 'TNI-AL' ? 'selected' : '' }}>TNI-AL</option>
                        <option value="TNI-AU" {{ old('target_institution', $athlete->target_institution) == 'TNI-AU' ? 'selected' : '' }}>TNI-AU</option>
                    </select>
                </div>
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">Batch</label>
                    <input type="text" name="batch" value="{{ old('batch', $athlete->batch) }}"
                        placeholder="Contoh: 2025-Batch-01"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3.5 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div class="flex flex-col sm:flex-row gap-3 pb-10">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 active:scale-95 text-white font-black uppercase tracking-widest text-sm py-5 rounded-2xl transition-all flex items-center justify-center gap-3">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.athletes.show', $athlete) }}"
                class="flex items-center justify-center gap-2 py-5 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white hover:border-gray-600 text-sm font-bold uppercase tracking-wider transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection