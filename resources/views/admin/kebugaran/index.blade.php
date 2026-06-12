@extends('layouts.app')
@section('title', 'Data Kebugaran')
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Kebugaran</p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">Data <span class="text-red-800">Kebugaran</span></h1>
            <p class="text-gray-500 text-sm mt-1">Semua periode latihan kebugaran member</p>
        </div>
        <a href="{{ route('admin.kebugaran.period.create') }}"
            class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all self-start">
            <i class="fa-solid fa-plus"></i> Buat Periode
        </a>
    </div>

    {{-- Filter --}}
    <form method="GET" class="flex gap-3 mb-6">
        <select name="athlete_id" onchange="this.form.submit()"
            class="bg-zinc-900 border border-zinc-700 text-white text-sm rounded-xl px-4 py-2.5 focus:ring-2 focus:ring-red-800 focus:outline-none">
            <option value="">Semua Atlet</option>
            @foreach($athletes as $a)
                <option value="{{ $a->id }}" {{ request('athlete_id') == $a->id ? 'selected' : '' }}>
                    {{ $a->user->name }}
                </option>
            @endforeach
        </select>
    </form>

    {{-- Table --}}
    @if($periods->count())
    <div class="bg-zinc-950 border border-zinc-800 rounded-2xl overflow-hidden">
        <table class="w-full text-sm">
            <thead>
                <tr class="border-b border-zinc-800">
                    <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500">Atlet</th>
                    <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500">Periode</th>
                    <th class="px-5 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500 hidden md:table-cell">Tanggal</th>
                    <th class="px-5 py-3 text-center text-[10px] uppercase tracking-widest text-zinc-500">Sesi</th>
                    <th class="px-5 py-3 text-right text-[10px] uppercase tracking-widest text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @foreach($periods as $period)
                <tr class="hover:bg-zinc-900/40 transition-colors">
                    <td class="px-5 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-red-800/20 flex items-center justify-center text-red-500 font-black text-xs flex-shrink-0">
                                {{ strtoupper(substr($period->athlete->user->name, 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-bold text-white text-sm">{{ $period->athlete->user->name }}</p>
                                <p class="text-zinc-500 text-xs capitalize">{{ $period->athlete->gender }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="px-5 py-4">
                        <p class="font-semibold text-white">{{ $period->name }}</p>
                        @if($period->notes)
                            <p class="text-zinc-500 text-xs truncate max-w-[180px]">{{ $period->notes }}</p>
                        @endif
                    </td>
                    <td class="px-5 py-4 text-zinc-400 text-xs hidden md:table-cell">
                        {{ $period->start_date->format('d M Y') }}
                        @if($period->end_date) — {{ $period->end_date->format('d M Y') }} @endif
                    </td>
                    <td class="px-5 py-4 text-center">
                        <span class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-zinc-800 text-white font-bold text-xs">
                            {{ $period->sessions->count() }}
                        </span>
                    </td>
                    <td class="px-5 py-4 text-right">
                        <a href="{{ route('admin.kebugaran.period.show', $period) }}"
                            class="inline-flex items-center gap-1.5 text-xs font-bold text-red-500 hover:text-white bg-red-900/20 hover:bg-red-800 px-3 py-1.5 rounded-lg transition-all">
                            <i class="fa-solid fa-eye"></i> Detail
                        </a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="mt-4">{{ $periods->links() }}</div>
    @else
    <div class="text-center py-20 text-zinc-600">
        <i class="fa-solid fa-heart-pulse text-4xl mb-4 block"></i>
        <p class="font-bold">Belum ada data kebugaran</p>
        <p class="text-sm mt-1">Buat periode pertama untuk mulai input data</p>
        <a href="{{ route('admin.kebugaran.period.create') }}"
            class="inline-flex items-center gap-2 mt-4 bg-red-800 hover:bg-red-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all">
            <i class="fa-solid fa-plus"></i> Buat Periode
        </a>
    </div>
    @endif

</div>
@endsection
