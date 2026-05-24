@extends('layouts.app')
@section('title', 'Edit Angkatan')

@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-8 max-w-2xl mx-auto">

    <div class="mb-8">
        <a href="{{ route('admin.batches.show', $batch) }}" class="text-gray-500 hover:text-white text-sm flex items-center gap-2 mb-4 transition-colors">
            <i class="fa-solid fa-arrow-left"></i> Kembali ke {{ $batch->name }}
        </a>
        <h1 class="text-2xl font-extrabold tracking-tighter">Edit <span class="text-red-500">{{ $batch->name }}</span></h1>
    </div>

    @if($errors->any())
    <div class="bg-red-900/30 border border-red-800 rounded-xl p-4 mb-6">
        <ul class="list-disc list-inside text-red-400 text-sm space-y-1">
            @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
        </ul>
    </div>
    @endif

    <form action="{{ route('admin.batches.update', $batch) }}" method="POST" class="bg-gray-950 border border-gray-800 rounded-2xl p-6 space-y-5">
        @csrf @method('PUT')

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Nama Angkatan *</label>
                <input type="text" name="name" value="{{ old('name', $batch->name) }}"
                    class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
            </div>
            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Tahun *</label>
                <input type="number" name="year" value="{{ old('year', $batch->year) }}" min="2020" max="2099"
                    class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Instansi Target *</label>
                <select name="institution_code"
                    class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    @foreach(['POLRI','TNI-AD','TNI-AL','TNI-AU'] as $inst)
                    <option value="{{ $inst }}" {{ old('institution_code', $batch->institution_code) === $inst ? 'selected' : '' }}>{{ $inst }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Maks. Parameter Sesi *</label>
                <select name="max_parameters"
                    class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
                    @foreach([2,3,4] as $n)
                    <option value="{{ $n }}" {{ old('max_parameters', $batch->max_parameters) == $n ? 'selected' : '' }}>{{ $n }} Parameter</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Tanggal Mulai</label>
                <input type="date" name="started_at" value="{{ old('started_at', $batch->started_at?->format('Y-m-d')) }}"
                    class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
            </div>
            <div>
                <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Tanggal Selesai</label>
                <input type="date" name="ended_at" value="{{ old('ended_at', $batch->ended_at?->format('Y-m-d')) }}"
                    class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors">
            </div>
        </div>

        <div>
            <label class="block text-gray-400 text-xs uppercase tracking-widest font-bold mb-2">Deskripsi</label>
            <textarea name="description" rows="3"
                class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-xl px-4 py-3 text-white text-sm outline-none transition-colors resize-none">{{ old('description', $batch->description) }}</textarea>
        </div>

        <div class="flex gap-3 pt-2">
            <button type="submit"
                class="flex-1 bg-red-800 hover:bg-red-700 text-white font-black py-3 rounded-xl transition-all text-sm uppercase tracking-wider">
                <i class="fa-solid fa-floppy-disk mr-2"></i> Simpan Perubahan
            </button>
            <a href="{{ route('admin.batches.show', $batch) }}"
                class="px-6 py-3 bg-gray-800 hover:bg-gray-700 text-gray-300 font-bold rounded-xl transition-all text-sm">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
