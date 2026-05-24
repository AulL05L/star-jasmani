@extends('layouts.app')
@section('title', 'Manajemen Peserta')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">MANAJEMEN <span class="text-red-800">PESERTA</span></h1>
            <p class="text-gray-500 text-sm mt-1">Kelola data member dan atlet.</p>
        </div>
        <a href="{{ route('admin.athletes.create') }}"
            class="flex items-center gap-2 bg-red-800 hover:bg-red-700 text-white font-bold uppercase tracking-widest text-xs px-5 py-3 rounded-xl transition-all">
            <i class="fa-solid fa-plus"></i> Tambah Peserta
        </a>
    </div>

    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
        @if($athletes->isEmpty())
            <div class="text-center py-16">
                <i class="fa-solid fa-users text-gray-700 text-4xl mb-4"></i>
                <p class="text-gray-500 text-sm">Belum ada peserta terdaftar.</p>
                <a href="{{ route('admin.athletes.create') }}"
                    class="inline-block mt-4 text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest">
                    + Tambah peserta pertama
                </a>
                <a href="{{ route('admin.athletes.import') }}"
                    class="flex items-center gap-2 border border-gray-800 hover:border-red-800 text-gray-400 hover:text-white font-bold uppercase tracking-widest text-xs px-4 py-2.5 rounded-xl transition-all">
                    <i class="fa-solid fa-file-arrow-up"></i> Import Excel
                </a>
            </div>
        @else
            <div class="overflow-x-auto">
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
                            <td class="px-6 py-4 text-center text-white font-bold">
                                {{ $athlete->samaptaScores->count() }}
                            </td>
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
                                        @csrf
                                        @method('DELETE')
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