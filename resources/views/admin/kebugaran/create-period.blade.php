@extends('layouts.app')
@section('title', 'Buat Periode Kebugaran')
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    <div class="max-w-xl">
        <a href="{{ route('admin.kebugaran.index') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-white text-sm mb-6 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>

        <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Kebugaran</p>
        <h1 class="text-2xl font-extrabold tracking-tighter mb-6">Buat <span class="text-red-800">Periode Baru</span></h1>

        <form action="{{ route('admin.kebugaran.period.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Atlet *</label>
                <select name="athlete_id" required
                    class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none @error('athlete_id') border-red-500 @enderror">
                    <option value="">— Pilih Atlet —</option>
                    @foreach($athletes as $a)
                        <option value="{{ $a->id }}" {{ old('athlete_id') == $a->id ? 'selected' : '' }}>
                            {{ $a->user->name }} ({{ ucfirst($a->gender) }})
                        </option>
                    @endforeach
                </select>
                @error('athlete_id')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Nama Periode *</label>
                <input type="text" name="name" value="{{ old('name') }}" placeholder="cth: Periode 1, Januari 2025" required
                    class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none @error('name') border-red-500 @enderror">
                @error('name')<p class="text-red-400 text-xs mt-1">{{ $message }}</p>@enderror
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Tanggal Mulai *</label>
                    <input type="date" name="start_date" value="{{ old('start_date') }}" required
                        class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Tanggal Selesai</label>
                    <input type="date" name="end_date" value="{{ old('end_date') }}"
                        class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none">
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Catatan</label>
                <textarea name="notes" rows="3" placeholder="Opsional..."
                    class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none resize-none">{{ old('notes') }}</textarea>
            </div>

            <button type="submit"
                class="w-full bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-sm py-3 rounded-xl transition-all">
                Buat Periode
            </button>
        </form>
    </div>

</div>
@endsection
