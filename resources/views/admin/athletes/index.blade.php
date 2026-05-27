@extends('layouts.app')
@section('title', 'Manajemen Peserta')
@section('content')
<div class="min-h-screen bg-black text-white p-4 lg:p-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between mb-6">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Peserta</p>
            <h1 class="text-2xl lg:text-3xl font-extrabold tracking-tighter">Manajemen <span class="text-red-800">Peserta</span></h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data member dan atlet</p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('admin.athletes.import') }}"
                class="flex items-center gap-2 border border-gray-800 hover:border-red-800 text-gray-400 hover:text-white font-bold uppercase tracking-widest text-xs px-3 py-2.5 rounded-xl transition-all">
                <i class="fa-solid fa-file-arrow-up text-xs"></i>
                <span class="hidden sm:inline">Import</span>
            </a>
            <a href="{{ route('admin.athletes.create') }}"
                class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all">
                <i class="fa-solid fa-plus"></i>
                <span class="hidden sm:inline">Tambah Peserta</span>
                <span class="sm:hidden">Tambah</span>
            </a>
        </div>
    </div>

    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
        @if($athletes->isEmpty())
            <div class="text-center py-16 px-4">
                <i class="fa-solid fa-users text-gray-700 text-4xl mb-4"></i>
                <p class="text-gray-500 text-sm mb-4">Belum ada peserta terdaftar.</p>
                <div class="flex flex-col sm:flex-row items-center justify-center gap-3">
                    <a href="{{ route('admin.athletes.create') }}"
                        class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all">
                        <i class="fa-solid fa-plus"></i> Tambah Peserta
                    </a>
                    <a href="{{ route('admin.athletes.import') }}"
                        class="flex items-center gap-2 border border-gray-800 hover:border-red-800 text-gray-400 hover:text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all">
                        <i class="fa-solid fa-file-arrow-up"></i> Import Excel
                    </a>
                </div>
            </div>
        @else

            {{-- ═══ CARD LAYOUT (mobile + tablet < lg) ═══ --}}
            <div class="lg:hidden divide-y divide-gray-900">
                @foreach($athletes as $athlete)
                <div class="p-4">
                    <div class="flex items-center justify-between gap-3">
                        {{-- Kiri: avatar + info --}}
                        <div class="flex items-center gap-3 min-w-0">
                            <div class="w-11 h-11 rounded-full bg-red-800 flex items-center justify-center text-white font-black text-sm shrink-0">
                                {{ strtoupper(substr($athlete->user->name, 0, 1)) }}
                            </div>
                            <div class="min-w-0">
                                <p class="text-white font-bold text-sm truncate">{{ $athlete->user->name }}</p>
                                <p class="text-gray-500 text-[11px] truncate">{{ $athlete->user->email }}</p>
                                <div class="flex items-center gap-2 mt-1 flex-wrap">
                                    <span class="text-gray-600 text-[10px]">{{ Str::ucfirst($athlete->gender) }}</span>
                                    @if($athlete->target_institution)
                                        <span class="text-[10px] px-1.5 py-0.5 rounded bg-gray-800 text-gray-400">{{ $athlete->target_institution }}</span>
                                    @endif
                                    @if($athlete->batch)
                                        <span class="text-gray-600 text-[10px]">{{ $athlete->batch }}</span>
                                    @endif
                                    <span class="text-gray-600 text-[10px]">{{ $athlete->samaptaScores->count() }} sesi</span>
                                </div>
                            </div>
                        </div>

                        {{-- Kanan: status + aksi --}}
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold
                                {{ $athlete->user->is_active ? 'bg-green-900/50 text-green-400' : 'bg-gray-800 text-gray-500' }}">
                                {{ $athlete->user->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                            <div class="flex items-center gap-1.5">
                                <a href="{{ route('admin.athletes.show', $athlete) }}"
                                    class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-eye text-xs"></i>
                                </a>
                                <a href="{{ route('admin.athletes.edit', $athlete) }}"
                                    class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-pen text-xs"></i>
                                </a>
                                <form action="{{ route('admin.athletes.destroy', $athlete) }}" method="POST"
                                    onsubmit="return confirm('Hapus peserta ini?')">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- ═══ TABLE LAYOUT (desktop ≥ lg) ═══ --}}
            <div class="hidden lg:block overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-6 py-4">Nama</th>
                            <th class="text-left px-6 py-4">Gender</th>
                            <th class="text-left px-6 py-4">Institusi</th>
                            <th class="text-left px-6 py-4">Batch</th>
                            <th class="text-center px-6 py-4">Total Sesi</th>
                            <th class="text-center px-6 py-4">Status</th>
                            <th class="text-center px-6 py-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($athletes as $athlete)
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-4">
                                <p class="text-white font-bold">{{ $athlete->user->name }}</p>
                                <p class="text-gray-600 text-xs">{{ $athlete->user->email }}</p>
                            </td>
                            <td class="px-6 py-4 text-gray-400">{{ Str::ucfirst($athlete->gender) }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $athlete->target_institution ?? '—' }}</td>
                            <td class="px-6 py-4 text-gray-400">{{ $athlete->batch ?? '—' }}</td>
                            <td class="px-6 py-4 text-center text-white font-bold">{{ $athlete->samaptaScores->count() }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="px-3 py-1 rounded-full text-xs font-bold
                                    {{ $athlete->user->is_active ? 'bg-green-900/50 text-green-400' : 'bg-gray-800 text-gray-500' }}">
                                    {{ $athlete->user->is_active ? 'Aktif' : 'Nonaktif' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.athletes.show', $athlete) }}"
                                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.athletes.edit', $athlete) }}"
                                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-gray-700 text-gray-400 hover:text-white flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-pen text-xs"></i>
                                    </a>
                                    <form action="{{ route('admin.athletes.destroy', $athlete) }}" method="POST"
                                        onsubmit="return confirm('Hapus peserta ini? Semua data terkait akan ikut terhapus.')">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                            class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 flex items-center justify-center transition-all">
                                            <i class="fa-solid fa-trash text-xs"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($athletes->hasPages())
                <div class="px-6 py-4 border-t border-gray-800">{{ $athletes->links() }}</div>
            @endif
        @endif
    </div>
</div>
@endsection