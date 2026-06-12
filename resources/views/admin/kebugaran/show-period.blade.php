@extends('layouts.app')
@section('title', 'Detail Periode — ' . $period->name)
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <a href="{{ route('admin.kebugaran.index') }}" class="inline-flex items-center gap-2 text-zinc-500 hover:text-white text-xs mb-3 transition-colors">
                <i class="fa-solid fa-arrow-left text-xs"></i> Semua Periode
            </a>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">
                {{ $period->athlete->user->name }} · {{ ucfirst($period->athlete->gender) }}
            </p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">{{ $period->name }}</h1>
            <p class="text-zinc-500 text-sm mt-1">
                {{ $period->start_date->format('d M Y') }}
                @if($period->end_date) — {{ $period->end_date->format('d M Y') }} @endif
                · {{ $period->sessions->count() }} sesi
            </p>
        </div>
        <div class="flex gap-2 self-start">
            <a href="{{ route('admin.kebugaran.session.create', $period) }}"
                class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all">
                <i class="fa-solid fa-plus"></i> Tambah Sesi
            </a>
            <form action="{{ route('admin.kebugaran.period.destroy', $period) }}" method="POST"
                onsubmit="return confirm('Hapus periode ini beserta semua sesi?')">
                @csrf @method('DELETE')
                <button type="submit" class="flex items-center gap-2 bg-zinc-800 hover:bg-red-900 text-zinc-400 hover:text-white font-bold text-xs px-3 py-2.5 rounded-xl transition-all">
                    <i class="fa-solid fa-trash"></i>
                </button>
            </form>
        </div>
    </div>

    @if(count($rows))
    {{-- Tabel sesi × parameter --}}
    <div class="bg-zinc-950 border border-zinc-800 rounded-2xl overflow-x-auto mb-6">
        <table class="w-full text-sm min-w-[700px]">
            <thead>
                <tr class="border-b border-zinc-800">
                    <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500 w-28">Sesi</th>
                    <th class="px-4 py-3 text-left text-[10px] uppercase tracking-widest text-zinc-500 w-28">Tanggal</th>
                    @foreach($parameters as $key => $param)
                        <th class="px-3 py-3 text-center text-[10px] uppercase tracking-widest text-zinc-500">
                            {{ $param['label'] }}
                        </th>
                    @endforeach
                    <th class="px-4 py-3 text-right text-[10px] uppercase tracking-widest text-zinc-500">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-zinc-800/50">
                @foreach($rows as $row)
                <tr class="hover:bg-zinc-900/40 transition-colors">
                    <td class="px-4 py-3">
                        <span class="font-black text-white">Sesi {{ $row['session']->session_number }}</span>
                    </td>
                    <td class="px-4 py-3 text-zinc-400 text-xs">
                        {{ $row['session']->date->format('d M Y') }}
                    </td>
                    @foreach($parameters as $key => $param)
                        <td class="px-3 py-3 text-center">
                            @if($row['scores'][$key])
                                @php $s = $row['scores'][$key]; @endphp
                                <span class="font-bold text-white text-sm">
                                    {{ $s['value'] }}{{ $param['unit'] ? ' '.$param['unit'] : '' }}
                                </span>
                                <br>
                                <span class="text-[10px] font-bold
                                    @if($s['color'] === 'emerald' || $s['color'] === 'green') text-green-400
                                    @elseif($s['color'] === 'amber') text-amber-400
                                    @else text-red-400 @endif">
                                    {{ $s['label'] }}
                                </span>
                            @else
                                <span class="text-zinc-700">—</span>
                            @endif
                        </td>
                    @endforeach
                    <td class="px-4 py-3 text-right">
                        <a href="{{ route('admin.kebugaran.session.edit', $row['session']) }}"
                            class="text-xs text-zinc-400 hover:text-white transition-colors font-bold mr-2">
                            <i class="fa-solid fa-pen"></i>
                        </a>
                        <form action="{{ route('admin.kebugaran.session.destroy', $row['session']) }}"
                            method="POST" class="inline"
                            onsubmit="return confirm('Hapus sesi ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-zinc-600 hover:text-red-400 transition-colors">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @else
    <div class="text-center py-16 text-zinc-600 bg-zinc-950 border border-zinc-800 rounded-2xl">
        <i class="fa-solid fa-clipboard-list text-3xl mb-3 block"></i>
        <p class="font-bold">Belum ada sesi</p>
        <a href="{{ route('admin.kebugaran.session.create', $period) }}"
            class="inline-flex items-center gap-2 mt-4 bg-red-800 hover:bg-red-700 text-white font-bold text-xs px-4 py-2.5 rounded-xl transition-all">
            <i class="fa-solid fa-plus"></i> Tambah Sesi Pertama
        </a>
    </div>
    @endif

</div>
@endsection
