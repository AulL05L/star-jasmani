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
                    @foreach($parameters as $key => $param)
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
                            placeholder="{{ $key === 'bmi' ? ($bmiSuggested ?? '0') : '0' }}"
                            step="{{ in_array($key, ['bmi','komposisi_otot','komposisi_lemak']) ? '0.1' : '1' }}"
                            min="0"
                            class="w-full bg-zinc-900 border border-zinc-700 text-white rounded-lg px-3 py-2.5 text-sm focus:ring-2 focus:ring-red-800 focus:outline-none"
                        >
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
@endsection
