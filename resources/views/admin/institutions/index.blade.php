@extends('layouts.app')
@section('title', 'Pengaturan Instansi')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8">
        <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Pengaturan</p>
        <h1 class="text-3xl font-extrabold tracking-tighter">Instansi & <span class="text-red-800">Bobot Penilaian</span></h1>
        <p class="text-gray-500 text-sm mt-1">Kelola bobot formula penilaian per instansi kedinasan</p>
    </div>

    @if(session('success'))
        <div class="bg-green-900/30 border border-green-800 rounded-xl p-4 mb-6 flex items-center gap-3">
            <i class="fa-solid fa-circle-check text-green-400"></i>
            <p class="text-green-400 text-sm font-bold">{{ session('success') }}</p>
        </div>
    @endif

    {{-- Info Formula --}}
    <div class="bg-gray-950 border border-red-800/30 rounded-2xl p-5 mb-6">
        <div class="flex items-start gap-3">
            <div class="w-8 h-8 bg-red-800/20 rounded-lg flex items-center justify-center flex-shrink-0 mt-0.5">
                <i class="fa-solid fa-circle-info text-red-500 text-xs"></i>
            </div>
            <div>
                <p class="text-white font-bold text-sm mb-1">Formula Nilai Akhir</p>
                <p class="text-gray-400 text-xs leading-relaxed">
                    <span class="text-white font-bold">Nilai Akhir</span> =
                    (Nilai Jasmani × <span class="text-red-400">Bobot UKG%</span>) +
                    (Nilai Renang × <span class="text-blue-400">Bobot Renang%</span>)
                    <br>
                    Ubah bobot di sini untuk menyesuaikan formula per instansi tanpa perlu mengubah kode program.
                </p>
            </div>
        </div>
    </div>

    {{-- Grid Instansi --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
        @foreach($institutions as $inst)
        <div class="bg-gray-950 border {{ $inst->is_active ? 'border-gray-800' : 'border-gray-900' }} rounded-2xl overflow-hidden
            {{ !$inst->is_active ? 'opacity-60' : '' }}">

            {{-- Header --}}
            <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-red-800/20 rounded-xl flex items-center justify-center">
                        <span class="text-red-500 font-black text-xs">{{ $inst->code }}</span>
                    </div>
                    <div>
                        <p class="text-white font-black text-sm">{{ $inst->name }}</p>
                        <p class="text-gray-500 text-xs">{{ $inst->athletes_count ?? 0 }} atlet · {{ $inst->samapta_scores_count ?? 0 }} penilaian</p>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                        {{ $inst->is_active ? 'bg-green-900/50 text-green-400' : 'bg-gray-800 text-gray-500' }}">
                        {{ $inst->is_active ? 'Aktif' : 'Nonaktif' }}
                    </span>
                    <a href="{{ route('admin.institutions.edit', $inst) }}"
                        class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white text-xs font-bold uppercase tracking-wider transition-all">
                        <i class="fa-solid fa-pen text-[10px]"></i> Edit
                    </a>
                </div>
            </div>

            {{-- Bobot --}}
            <div class="p-6">
                <div class="grid grid-cols-3 gap-4 mb-4">
                    <div class="bg-black rounded-xl p-4 text-center">
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Bobot UKG</p>
                        <p class="text-red-500 font-black text-2xl">{{ $inst->ukg_weight }}%</p>
                        <p class="text-gray-600 text-[10px]">Jasmani</p>
                    </div>
                    <div class="bg-black rounded-xl p-4 text-center">
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Bobot Renang</p>
                        <p class="text-blue-400 font-black text-2xl">{{ $inst->renang_weight }}%</p>
                        <p class="text-gray-600 text-[10px]">Renang</p>
                    </div>
                    <div class="bg-black rounded-xl p-4 text-center">
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Passing Grade</p>
                        <p class="text-white font-black text-2xl">{{ $inst->passing_grade }}</p>
                        <p class="text-gray-600 text-[10px]">Nilai min. lulus</p>
                    </div>
                </div>

                {{-- Visual bar bobot --}}
                <div>
                    <div class="flex justify-between text-[10px] text-gray-500 mb-1">
                        <span class="text-red-500 font-bold">UKG {{ $inst->ukg_weight }}%</span>
                        <span class="text-blue-400 font-bold">Renang {{ $inst->renang_weight }}%</span>
                    </div>
                    <div class="h-3 bg-gray-900 rounded-full overflow-hidden flex">
                        <div class="bg-red-800 h-full transition-all" style="width: {{ $inst->ukg_weight }}%"></div>
                        <div class="bg-blue-700 h-full transition-all" style="width: {{ $inst->renang_weight }}%"></div>
                    </div>
                </div>

                {{-- Formula --}}
                <div class="mt-3 bg-gray-900 rounded-xl px-4 py-2.5 text-xs text-gray-500">
                    Nilai Akhir = (Jasmani × {{ $inst->ukg_weight }}%) + (Renang × {{ $inst->renang_weight }}%)
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>
@endsection