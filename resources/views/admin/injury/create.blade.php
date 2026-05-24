@extends('layouts.app')
@section('title', 'Input Riwayat Cedera')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Cedera</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">
                INPUT <span class="text-red-800">RIWAYAT CEDERA</span>
            </h1>
            <p class="text-gray-500 text-sm mt-1">{{ $athlete->user->name }} · Informasi saja, tidak mempengaruhi nilai</p>
        </div>
        <a href="{{ route('admin.athletes.show', $athlete) }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-900/30 border border-red-800 rounded-xl p-4 mb-6">
            @foreach($errors->all() as $error)
                <p class="text-red-400 text-sm">• {{ $error }}</p>
            @endforeach
        </div>
    @endif

    <form action="{{ route('admin.injury.store', $athlete) }}" method="POST" class="space-y-5 max-w-2xl">
        @csrf

        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs flex items-center gap-2">
                <i class="fa-solid fa-bone text-red-800"></i> Detail Cedera
            </h2>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Bagian Tubuh <span class="text-red-500">*</span>
                    </label>
                    <input type="text" name="bagian_tubuh" value="{{ old('bagian_tubuh') }}"
                        placeholder="Contoh: Hamstring Kanan, Lutut Kiri"
                        class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Status <span class="text-red-500">*</span>
                    </label>
                    <select name="status"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all appearance-none">
                        <option value="aktif" {{ old('status') === 'aktif' ? 'selected' : '' }}>Aktif / Sedang Cedera</option>
                        <option value="monitoring" {{ old('status') === 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                        <option value="pulih" {{ old('status') === 'pulih' ? 'selected' : '' }}>Pulih</option>
                    </select>
                </div>
            </div>

            <div>
                <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                    Deskripsi Cedera <span class="text-red-500">*</span>
                </label>
                <textarea name="deskripsi_cedera" rows="3" maxlength="1000"
                    placeholder="Deskripsikan kondisi cedera secara detail..."
                    class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all resize-none">{{ old('deskripsi_cedera') }}</textarea>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Tanggal Cedera <span class="text-red-500">*</span>
                    </label>
                    <input type="date" name="tanggal_cedera"
                        value="{{ old('tanggal_cedera', today()->format('Y-m-d')) }}"
                        max="{{ today()->format('Y-m-d') }}"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                </div>

                <div>
                    <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                        Tanggal Sembuh
                    </label>
                    <input type="date" name="tanggal_sembuh"
                        value="{{ old('tanggal_sembuh') }}"
                        class="w-full bg-black border border-gray-800 text-white rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all" />
                    <p class="text-gray-600 text-xs mt-1">Kosongkan jika masih dalam pemulihan</p>
                </div>
            </div>

            <div>
                <label class="block text-gray-400 text-[11px] uppercase tracking-widest font-bold mb-2">
                    Catatan Medis
                </label>
                <textarea name="catatan_medis" rows="2" maxlength="1000"
                    placeholder="Catatan tambahan, referensi fisioterapis, dll..."
                    class="w-full bg-black border border-gray-800 text-white placeholder-gray-700 rounded-xl py-3 px-4 text-sm focus:outline-none focus:border-red-800 transition-all resize-none">{{ old('catatan_medis') }}</textarea>
            </div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 pb-10">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl transition-all flex items-center justify-center gap-2">
                <i class="fa-solid fa-floppy-disk"></i> Simpan Riwayat Cedera
            </button>
            <a href="{{ route('admin.athletes.show', $athlete) }}"
                class="flex items-center justify-center gap-2 py-4 px-8 rounded-2xl border border-gray-800 text-gray-500 hover:text-white text-sm font-bold uppercase tracking-wider transition-all">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection