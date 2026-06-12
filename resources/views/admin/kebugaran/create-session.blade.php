@extends('layouts.app')
@section('title', 'Tambah Sesi — ' . $period->name)
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    <div class="max-w-2xl">
        <a href="{{ route('admin.kebugaran.period.show', $period) }}"
            class="inline-flex items-center gap-2 text-zinc-500 hover:text-white text-sm mb-6 transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> {{ $period->name }}
        </a>

        <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">
            {{ $period->athlete->user->name }} · {{ $period->name }}
        </p>
        <h1 class="text-2xl font-extrabold tracking-tighter mb-6">
            Tambah <span class="text-red-800">Sesi #{{ $nextNumber }}</span>
        </h1>

        <form action="{{ route('admin.kebugaran.session.store', $period) }}" method="POST" class="space-y-6">
            @csrf

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Tanggal Sesi *</label>
                    <input type="date" name="date" value="{{ old('date', now()->format('Y-m-d')) }}" required
                        class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none">
                </div>
                <div>
                    <label class="block text-xs font-bold uppercase tracking-widest text-zinc-400 mb-2">Catatan Sesi</label>
                    <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Opsional"
                        class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-xl px-4 py-3 focus:ring-2 focus:ring-red-800 focus:outline-none">
                </div>
            </div>

            {{-- Parameter Inputs --}}
            <div>
                <p class="text-xs font-bold uppercase tracking-widest text-zinc-400 mb-3">Nilai Parameter</p>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($parameters as $key => $param)
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
                            value="{{ old("scores.{$key}", $key === 'bmi' ? $bmiSuggested : '') }}"
                            placeholder="{{ $key === 'bmi' ? ($bmiSuggested ?? 'Hitung dari BB/TB') : '0' }}"
                            step="{{ in_array($key, ['bmi','komposisi_otot','komposisi_lemak']) ? '0.1' : '1' }}"
                            min="0"
                            class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-800 focus:outline-none"
                        >
                        @if($key === 'bmi' && $bmiSuggested)
                            <p class="text-zinc-600 text-xs mt-1">
                                <i class="fa-solid fa-circle-info"></i>
                                Auto dari profil: BB {{ $athlete->weight_kg }}kg / TB {{ $athlete->height_cm }}cm
                            </p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>

            <button type="submit"
                class="w-full bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-sm py-3 rounded-xl transition-all">
                Simpan Sesi
            </button>
        </form>
    </div>

</div>
@endsection
