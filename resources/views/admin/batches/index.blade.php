@extends('layouts.app')
@section('title', 'Manajemen Angkatan')

@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-8">

    <div class="flex items-center justify-between mb-8">
        <div>
            <p class="text-gray-600 text-xs uppercase tracking-widest font-bold mb-1">Admin Panel</p>
            <h1 class="text-2xl font-extrabold tracking-tighter">Manajemen <span class="text-red-500">Angkatan</span></h1>
            <p class="text-gray-500 text-sm mt-1">Kelola batch dan parameter sesi tes</p>
        </div>
        <a href="{{ route('admin.batches.create') }}"
            class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold px-5 py-2.5 rounded-xl transition-all text-sm">
            <i class="fa-solid fa-plus"></i> Buat Angkatan
        </a>
    </div>

    <div class="grid grid-cols-1 gap-4">
        @forelse($batches as $batch)
        <div class="bg-gray-950 border border-gray-800 hover:border-red-900 rounded-2xl p-6 transition-all">
            <div class="flex items-start justify-between gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3 mb-1">
                        <h2 class="text-white font-black text-lg">{{ $batch->name }}</h2>
                        <span class="px-2.5 py-0.5 bg-red-900/40 text-red-400 text-xs font-bold rounded-full">{{ $batch->institution_code }}</span>
                        <span class="px-2.5 py-0.5 bg-gray-800 text-gray-400 text-xs font-bold rounded-full">{{ $batch->year }}</span>
                    </div>
                    @if($batch->description)
                        <p class="text-gray-500 text-sm mb-3">{{ $batch->description }}</p>
                    @endif

                    {{-- Stats Row --}}
                    <div class="flex items-center gap-6 text-sm">
                        <div class="flex items-center gap-2 text-gray-400">
                            <i class="fa-solid fa-users text-red-700 text-xs"></i>
                            <span><strong class="text-white">{{ $batch->athletes_count }}</strong> atlet</span>
                        </div>
                        <div class="flex items-center gap-2 text-gray-400">
                            <i class="fa-solid fa-list-check text-orange-700 text-xs"></i>
                            <span><strong class="text-white">{{ $batch->parameters->count() }}</strong> / {{ $batch->max_parameters }} parameter</span>
                        </div>
                        @if($batch->started_at)
                        <div class="flex items-center gap-2 text-gray-400">
                            <i class="fa-solid fa-calendar text-blue-700 text-xs"></i>
                            <span>{{ $batch->started_at->format('d M Y') }}
                                @if($batch->ended_at) – {{ $batch->ended_at->format('d M Y') }} @endif
                            </span>
                        </div>
                        @endif
                    </div>

                    {{-- Parameter badges --}}
                    @if($batch->parameters->isNotEmpty())
                    <div class="flex flex-wrap gap-2 mt-3">
                        @foreach($batch->parameters as $param)
                        <span class="px-3 py-1 bg-gray-900 border border-gray-700 text-gray-300 text-xs rounded-lg font-medium">
                            <i class="fa-solid fa-circle-dot text-red-700 text-[8px] mr-1"></i>
                            {{ $param->label }}
                            @if($param->test_date)
                                <span class="text-gray-600 ml-1">{{ $param->test_date->format('d/m/Y') }}</span>
                            @endif
                        </span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="flex items-center gap-2 flex-shrink-0">
                    <a href="{{ route('admin.batches.show', $batch) }}"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold rounded-xl transition-all">
                        <i class="fa-solid fa-eye mr-1"></i> Detail
                    </a>
                    <a href="{{ route('admin.batches.edit', $batch) }}"
                        class="px-4 py-2 bg-gray-800 hover:bg-gray-700 text-white text-xs font-bold rounded-xl transition-all">
                        <i class="fa-solid fa-pen"></i>
                    </a>
                    <form action="{{ route('admin.batches.destroy', $batch) }}" method="POST"
                          onsubmit="return confirm('Hapus angkatan {{ $batch->name }}?')">
                        @csrf @method('DELETE')
                        <button class="px-4 py-2 bg-red-900/30 hover:bg-red-800 text-red-400 hover:text-white text-xs font-bold rounded-xl transition-all">
                            <i class="fa-solid fa-trash"></i>
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="text-center py-20 bg-gray-950 border border-gray-800 rounded-2xl">
            <i class="fa-solid fa-layer-group text-gray-700 text-5xl mb-4"></i>
            <p class="text-gray-400 font-bold text-lg mb-2">Belum ada angkatan</p>
            <p class="text-gray-600 text-sm mb-6">Buat angkatan pertama untuk mulai mengelola atlet dan sesi tes.</p>
            <a href="{{ route('admin.batches.create') }}"
                class="inline-flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold px-6 py-3 rounded-xl transition-all text-sm">
                <i class="fa-solid fa-plus"></i> Buat Angkatan Pertama
            </a>
        </div>
        @endforelse
    </div>

    <div class="mt-6">{{ $batches->links() }}</div>
</div>
@endsection
