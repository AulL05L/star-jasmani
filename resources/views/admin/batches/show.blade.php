@extends('layouts.app')
@section('title', $batch->name . ' — Detail Angkatan')

@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-8">

    {{-- Header --}}
    <div class="flex items-start justify-between mb-8">
        <div>
            <a href="{{ route('admin.batches.index') }}" class="text-gray-500 hover:text-white text-sm flex items-center gap-2 mb-3 transition-colors">
                <i class="fa-solid fa-arrow-left"></i> Semua Angkatan
            </a>
            <div class="flex items-center gap-3">
                <h1 class="text-2xl font-extrabold tracking-tighter">{{ $batch->name }}</h1>
                <span class="px-3 py-1 bg-red-900/40 text-red-400 text-xs font-black rounded-full">{{ $batch->institution_code }}</span>
                <span class="px-3 py-1 bg-gray-800 text-gray-400 text-xs font-black rounded-full">{{ $batch->year }}</span>
            </div>
            @if($batch->description)
                <p class="text-gray-500 text-sm mt-1">{{ $batch->description }}</p>
            @endif
        </div>
        <a href="{{ route('admin.batches.edit', $batch) }}"
            class="flex items-center gap-2 bg-gray-800 hover:bg-gray-700 text-white font-bold px-4 py-2.5 rounded-xl transition-all text-sm">
            <i class="fa-solid fa-pen"></i> Edit
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- LEFT: Parameter Management --}}
        <div class="lg:col-span-1 space-y-4">

            {{-- Parameter List --}}
            <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
                <div class="px-5 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-white font-bold text-sm uppercase tracking-widest">Parameter Sesi</h2>
                        <p class="text-gray-600 text-xs mt-0.5">{{ $batch->parameters->count() }} / {{ $batch->max_parameters }} sesi</p>
                    </div>
                </div>

                @if($batch->parameters->isEmpty())
                <div class="text-center py-8">
                    <i class="fa-solid fa-list-check text-gray-700 text-3xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada parameter</p>
                </div>
                @else
                <div class="divide-y divide-gray-900">
                    @foreach($batch->parameters as $param)
                    <div class="px-5 py-4 flex items-center justify-between group">
                        <div>
                            <div class="flex items-center gap-2">
                                <span class="w-6 h-6 bg-red-900/40 text-red-400 text-xs font-black rounded-full flex items-center justify-center">
                                    {{ $param->parameter_number }}
                                </span>
                                <p class="text-white font-bold text-sm">{{ $param->label }}</p>
                            </div>
                            <div class="flex items-center gap-3 mt-1 ml-8">
                                @if($param->test_date)
                                <span class="text-gray-600 text-xs">{{ $param->test_date->format('d M Y') }}</span>
                                @endif
                                <span class="text-gray-600 text-xs">{{ $param->samaptaScores->count() }} penilaian</span>
                            </div>
                        </div>
                        <form action="{{ route('admin.batches.parameters.destroy', [$batch, $param]) }}" method="POST"
                              onsubmit="return confirm('Hapus {{ $param->label }}?')">
                            @csrf @method('DELETE')
                            <button class="opacity-0 group-hover:opacity-100 px-2 py-1 text-red-700 hover:text-red-400 transition-all text-xs">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @endif

                {{-- Add Parameter Form --}}
                @if($batch->parameters->count() < $batch->max_parameters)
                <div class="border-t border-gray-800 p-5">
                    <p class="text-gray-500 text-xs uppercase tracking-widest font-bold mb-3">Tambah Parameter</p>
                    <form action="{{ route('admin.batches.parameters.store', $batch) }}" method="POST" class="space-y-3">
                        @csrf
                        <input type="hidden" name="parameter_number" value="{{ $batch->parameters->count() + 1 }}">

                        <input type="text" name="label" placeholder="Parameter {{ $batch->parameters->count() + 1 }}"
                            value="{{ old('label', 'Parameter ' . ($batch->parameters->count() + 1)) }}"
                            class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-lg px-3 py-2 text-white text-sm outline-none transition-colors">

                        <input type="date" name="test_date"
                            class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-lg px-3 py-2 text-white text-sm outline-none transition-colors">

                        <textarea name="description" rows="2" placeholder="Deskripsi (opsional)"
                            class="w-full bg-gray-900 border border-gray-700 focus:border-red-700 rounded-lg px-3 py-2 text-white text-sm outline-none transition-colors resize-none"></textarea>

                        <button type="submit"
                            class="w-full bg-red-900/30 hover:bg-red-800 border border-red-900 text-red-400 hover:text-white font-bold py-2 rounded-lg text-sm transition-all">
                            <i class="fa-solid fa-plus mr-1"></i> Tambah Parameter
                        </button>
                    </form>
                </div>
                @else
                <div class="border-t border-gray-800 px-5 py-3 text-center">
                    <p class="text-gray-600 text-xs">Maks. {{ $batch->max_parameters }} parameter tercapai</p>
                </div>
                @endif
            </div>
        </div>

        {{-- RIGHT: Athletes in Batch --}}
        <div class="lg:col-span-2">
            <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                    <div>
                        <h2 class="text-white font-bold text-sm uppercase tracking-widest">Atlet di Angkatan Ini</h2>
                        <p class="text-gray-600 text-xs mt-0.5">{{ $batch->athletes->count() }} atlet terdaftar</p>
                    </div>
                    <a href="{{ route('admin.athletes.create') }}"
                        class="text-xs text-red-500 hover:text-red-400 font-bold flex items-center gap-1 transition-colors">
                        <i class="fa-solid fa-plus"></i> Tambah Atlet
                    </a>
                </div>

                @if($batch->athletes->isEmpty())
                <div class="text-center py-12">
                    <i class="fa-solid fa-users-slash text-gray-700 text-4xl mb-3"></i>
                    <p class="text-gray-500 text-sm">Belum ada atlet di angkatan ini</p>
                    <p class="text-gray-600 text-xs mt-1">Assign batch_id ke atlet saat membuat/edit profil atlet.</p>
                </div>
                @else
                <div class="overflow-x-auto">
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                                <th class="text-left px-6 py-3">Atlet</th>
                                <th class="text-center px-4 py-3">Gender</th>
                                <th class="text-center px-4 py-3">Sesi</th>
                                <th class="text-center px-4 py-3">Nilai Terbaru</th>
                                <th class="text-center px-4 py-3">Grade</th>
                                <th class="px-4 py-3"></th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-900">
                            @foreach($batch->athletes as $athlete)
                            @php
                                $latestScore = $athlete->samaptaScores()->first();
                            @endphp
                            <tr class="hover:bg-gray-900/50 transition-colors">
                                <td class="px-6 py-3">
                                    <p class="text-white font-bold">{{ $athlete->user->name }}</p>
                                    <p class="text-gray-600 text-xs">{{ $athlete->user->email }}</p>
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2 py-0.5 rounded-full text-xs font-bold
                                        {{ $athlete->gender === 'pria' ? 'bg-blue-900/40 text-blue-400' : 'bg-pink-900/40 text-pink-400' }}">
                                        {{ Str::ucfirst($athlete->gender) }}
                                    </span>
                                </td>
                                <td class="px-4 py-3 text-center text-gray-400 text-xs">
                                    {{ $athlete->samaptaScores()->count() }}
                                </td>
                                <td class="px-4 py-3 text-center font-black text-white">
                                    {{ $latestScore ? number_format($latestScore->score_final, 1) : '—' }}
                                </td>
                                <td class="px-4 py-3 text-center">
                                    @if($latestScore)
                                    <span class="px-2 py-1 rounded-full text-xs font-black
                                        {{ $latestScore->grade === 'A' ? 'bg-green-900/50 text-green-400' :
                                          ($latestScore->grade === 'B' ? 'bg-blue-900/50 text-blue-400' :
                                          ($latestScore->grade === 'C' ? 'bg-yellow-900/50 text-yellow-400' :
                                          'bg-red-900/50 text-red-400')) }}">
                                        {{ $latestScore->grade }}
                                    </span>
                                    @else
                                    <span class="text-gray-700 text-xs">—</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-right">
                                    <a href="{{ route('admin.athletes.show', $athlete) }}"
                                        class="text-gray-500 hover:text-white text-xs transition-colors">
                                        Detail <i class="fa-solid fa-arrow-right ml-1"></i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
