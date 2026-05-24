@extends('layouts.app')
@section('title', 'Detail Peserta')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    {{-- Header --}}
    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Peserta</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">{{ $athlete->user->name }}</h1>
            <p class="text-gray-500 text-sm mt-1">{{ $athlete->target_institution ?? '—' }} · {{ $athlete->batch ?? '—' }}</p>
        </div>
        <a href="{{ route('admin.athletes.index') }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    {{-- Action Buttons --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 mb-8">
        <a href="{{ route('admin.samapta.create', ['athlete_id' => $athlete->id]) }}"
            class="bg-red-800 hover:bg-red-700 text-white font-bold py-4 px-4 rounded-2xl transition-all flex items-center gap-3 group">
            <div class="w-10 h-10 bg-red-700 rounded-xl flex items-center justify-center group-hover:bg-red-600 transition-all flex-shrink-0">
                <i class="fa-solid fa-plus"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-wider">Input Nilai</p>
                <p class="text-red-300 text-[10px] mt-0.5">Samapta baru</p>
            </div>
        </a>

        <a href="{{ route('admin.bmi.create', $athlete) }}"
            class="bg-gray-950 hover:bg-gray-900 border border-gray-800 text-white font-bold py-4 px-4 rounded-2xl transition-all flex items-center gap-3 group">
            <div class="w-10 h-10 bg-blue-900/40 rounded-xl flex items-center justify-center group-hover:bg-blue-900/60 transition-all flex-shrink-0">
                <i class="fa-solid fa-weight-scale text-blue-400"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-wider">Input BMI</p>
                <p class="text-gray-500 text-[10px] mt-0.5">Ukur tubuh</p>
            </div>
        </a>

        <a href="{{ route('admin.athletes.performance', $athlete) }}"
            class="bg-gray-950 hover:bg-gray-900 border border-gray-800 text-white font-bold py-4 px-4 rounded-2xl transition-all flex items-center gap-3 group">
            <div class="w-10 h-10 bg-red-900/20 rounded-xl flex items-center justify-center group-hover:bg-red-900/40 transition-all flex-shrink-0">
                <i class="fa-solid fa-chart-line text-red-500"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-wider">Performa</p>
                <p class="text-gray-500 text-[10px] mt-0.5">Grafik perkembangan</p>
            </div>
        </a>

        <a href="{{ route('admin.athletes.edit', $athlete) }}"
            class="bg-gray-950 hover:bg-gray-900 border border-gray-800 text-white font-bold py-4 px-4 rounded-2xl transition-all flex items-center gap-3 group">
            <div class="w-10 h-10 bg-gray-800 rounded-xl flex items-center justify-center group-hover:bg-gray-700 transition-all flex-shrink-0">
                <i class="fa-solid fa-pen text-gray-400"></i>
            </div>
            <div>
                <p class="text-xs font-black uppercase tracking-wider">Edit Data</p>
                <p class="text-gray-500 text-[10px] mt-0.5">Ubah profil</p>
            </div>
        </a>

        <form action="{{ route('admin.athletes.destroy', $athlete) }}" method="POST"
            onsubmit="return confirm('Hapus peserta ini? Semua data terkait akan ikut terhapus.')">
            @csrf
            @method('DELETE')
            <button type="submit"
                class="w-full bg-gray-950 hover:bg-red-900/20 border border-gray-800 hover:border-red-900 text-white font-bold py-4 px-4 rounded-2xl transition-all flex items-center gap-3 group">
                <div class="w-10 h-10 bg-red-900/20 rounded-xl flex items-center justify-center group-hover:bg-red-900/40 transition-all flex-shrink-0">
                    <i class="fa-solid fa-trash text-red-500"></i>
                </div>
                <div class="text-left">
                    <p class="text-xs font-black uppercase tracking-wider">Hapus</p>
                    <p class="text-gray-500 text-[10px] mt-0.5">Hapus peserta</p>
                </div>
            </button>
        </form>
    </div>

    {{-- Info Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">

        {{-- Data Pribadi --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-red-800/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-user text-red-500 text-xs"></i>
                </div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Data Pribadi</h2>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Email</p>
                    <p class="text-white font-bold text-sm truncate">{{ $athlete->user->email }}</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Gender</p>
                        <p class="text-white font-bold text-sm">{{ Str::ucfirst($athlete->gender) }}</p>
                    </div>
                    <div>
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Status</p>
                        <span class="px-2 py-1 rounded-full text-xs font-bold {{ $athlete->user->is_active ? 'bg-green-900/50 text-green-400' : 'bg-gray-800 text-gray-500' }}">
                            {{ $athlete->user->is_active ? 'Aktif' : 'Nonaktif' }}
                        </span>
                    </div>
                </div>
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Tanggal Lahir</p>
                    <p class="text-white font-bold text-sm">{{ $athlete->birth_date?->format('d M Y') ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Telepon</p>
                    <p class="text-white font-bold text-sm">{{ $athlete->phone ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">NIK</p>
                    <p class="text-white font-bold text-sm">{{ $athlete->nik ?? '—' }}</p>
                </div>
            </div>
        </div>

        {{-- Data Fisik & Kedinasan --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-red-800/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-dumbbell text-red-500 text-xs"></i>
                </div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Fisik & Kedinasan</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="grid grid-cols-2 gap-4">
                    <div class="bg-black rounded-xl p-3 text-center">
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Tinggi</p>
                        <p class="text-white font-black text-xl">{{ $athlete->height_cm ?? '—' }}</p>
                        <p class="text-gray-600 text-[10px]">cm</p>
                    </div>
                    <div class="bg-black rounded-xl p-3 text-center">
                        <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Berat</p>
                        <p class="text-white font-black text-xl">{{ $athlete->weight_kg ?? '—' }}</p>
                        <p class="text-gray-600 text-[10px]">kg</p>
                    </div>
                </div>
                @if($athlete->height_cm && $athlete->weight_kg)
                @php
                    $bmi = round($athlete->weight_kg / (($athlete->height_cm / 100) ** 2), 1);
                    $bmiStatus = $bmi < 17 ? 'Kurang' : ($bmi < 25 ? 'Normal' : ($bmi < 30 ? 'Gemuk' : 'Obesitas'));
                    $bmiColor = match($bmiStatus) {
                        'Normal' => 'text-green-400', 'Kurang' => 'text-blue-400',
                        'Gemuk' => 'text-yellow-400', default => 'text-red-400'
                    };
                @endphp
                <div class="bg-black rounded-xl p-3 text-center">
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">BMI</p>
                    <p class="font-black text-2xl {{ $bmiColor }}">{{ $bmi }}</p>
                    <p class="text-[10px] {{ $bmiColor }} font-bold uppercase tracking-widest">{{ $bmiStatus }}</p>
                </div>
                @endif
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Target Institusi</p>
                    <p class="text-white font-bold text-sm">{{ $athlete->target_institution ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Batch</p>
                    <p class="text-white font-bold text-sm">{{ $athlete->batch ?? '—' }}</p>
                </div>
                <div>
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Tes Kekuatan Atas</p>
                    <p class="text-white font-bold text-sm">{{ $athlete->upper_body_test }}</p>
                </div>
            </div>
        </div>

        {{-- Statistik --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-800 flex items-center gap-2">
                <div class="w-8 h-8 bg-red-800/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-line text-red-500 text-xs"></i>
                </div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Statistik</h2>
            </div>
            <div class="p-6 space-y-4">
                <div class="bg-black rounded-xl p-4 text-center">
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Total Sesi</p>
                    <p class="text-white font-black text-4xl">{{ $athlete->samaptaScores->count() }}</p>
                    <p class="text-gray-600 text-[10px]">penilaian samapta</p>
                </div>

                @if($athlete->samaptaScores->isNotEmpty())
                @php $latest = $athlete->samaptaScores->first(); @endphp
                <div class="bg-black rounded-xl p-4 text-center">
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Nilai Terakhir</p>
                    <p class="text-red-500 font-black text-3xl">{{ number_format($latest->score_final, 1) }}</p>
                    <span class="inline-block px-3 py-1 rounded-full text-[10px] font-black mt-1
                        {{ $latest->grade === 'A' ? 'bg-green-900/50 text-green-400' :
                          ($latest->grade === 'B' ? 'bg-blue-900/50 text-blue-400' :
                          ($latest->grade === 'C' ? 'bg-yellow-900/50 text-yellow-400' :
                          'bg-red-900/50 text-red-400')) }}">
                        Grade {{ $latest->grade }}
                    </span>
                </div>

                <div class="bg-black rounded-xl p-4 text-center">
                    <p class="text-gray-600 text-[10px] uppercase tracking-widest mb-1">Total BMI Records</p>
                    <p class="text-white font-black text-4xl">{{ $athlete->bmiRecords->count() }}</p>
                    <p class="text-gray-600 text-[10px]">pengukuran</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Riwayat Samapta --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-red-800/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-chart-bar text-red-500 text-xs"></i>
                </div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Riwayat Samapta</h2>
            </div>
            <a href="{{ route('admin.samapta.create', ['athlete_id' => $athlete->id]) }}"
                class="flex items-center gap-1 text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest transition-colors">
                <i class="fa-solid fa-plus text-[10px]"></i> Input Nilai
            </a>
        </div>

        @if($athlete->samaptaScores->isEmpty())
            <div class="text-center py-12">
                <i class="fa-solid fa-clipboard-list text-gray-700 text-3xl mb-3"></i>
                <p class="text-gray-500 text-sm">Belum ada sesi penilaian.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Sesi</th>
                            <th class="text-left px-6 py-3">Tanggal</th>
                            <th class="text-center px-6 py-3">UKG</th>
                            <th class="text-center px-6 py-3">Nilai Akhir</th>
                            <th class="text-center px-6 py-3">Grade</th>
                            <th class="text-center px-6 py-3">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($athlete->samaptaScores as $score)
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-3 text-white font-medium">{{ $score->session_label ?? '—' }}</td>
                            <td class="px-6 py-3 text-gray-400">{{ $score->assessment_date->format('d M Y') }}</td>
                            <td class="px-6 py-3 text-center text-white font-bold">{{ number_format($score->score_ukg_avg, 1) }}</td>
                            <td class="px-6 py-3 text-center text-white font-black text-base">{{ number_format($score->score_final, 1) }}</td>
                            <td class="px-6 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-black
                                    {{ $score->grade === 'A' ? 'bg-green-900/50 text-green-400' :
                                      ($score->grade === 'B' ? 'bg-blue-900/50 text-blue-400' :
                                      ($score->grade === 'C' ? 'bg-yellow-900/50 text-yellow-400' :
                                      'bg-red-900/50 text-red-400')) }}">
                                    {{ $score->grade }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <div class="flex items-center justify-center gap-2">
                                    <a href="{{ route('admin.samapta.show', $score) }}"
                                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-800 text-gray-400 hover:text-white inline-flex items-center justify-center transition-all"
                                        title="Detail">
                                        <i class="fa-solid fa-eye text-xs"></i>
                                    </a>
                                    <a href="{{ route('admin.reports.samapta.pdf', $score) }}"
                                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 inline-flex items-center justify-center transition-all"
                                        title="Download PDF">
                                        <i class="fa-solid fa-file-pdf text-xs"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

    {{-- Riwayat Cedera --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-red-900/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-bone text-red-500 text-xs"></i>
                </div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Riwayat Cedera</h2>
                <span class="text-[10px] text-gray-600 normal-case font-normal">— Informasi saja, tidak mempengaruhi nilai</span>
            </div>
            <a href="{{ route('admin.injury.create', $athlete) }}"
                class="flex items-center gap-1 text-red-500 hover:text-red-400 text-xs font-bold uppercase tracking-widest transition-colors">
                <i class="fa-solid fa-plus text-[10px]"></i> Catat Cedera
            </a>
        </div>

        @if($athlete->injuryTracks->isEmpty())
            <div class="text-center py-10">
                <i class="fa-solid fa-shield-heart text-gray-700 text-3xl mb-3"></i>
                <p class="text-gray-500 text-sm">Tidak ada riwayat cedera.</p>
            </div>
        @else
            <div class="divide-y divide-gray-900">
                @foreach($athlete->injuryTracks as $injury)
                <div class="px-6 py-4 hover:bg-gray-900/30 transition-colors">
                    <div class="flex items-start justify-between gap-4">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-white font-bold text-sm">{{ $injury->bagian_tubuh }}</span>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black
                                    {{ $injury->status === 'aktif' ? 'bg-red-900/50 text-red-400' :
                                    ($injury->status === 'monitoring' ? 'bg-yellow-900/50 text-yellow-400' :
                                    'bg-green-900/50 text-green-400') }}">
                                    {{ Str::ucfirst($injury->status) }}
                                </span>
                            </div>
                            <p class="text-gray-400 text-xs mb-2">{{ $injury->deskripsi_cedera }}</p>
                            <div class="flex items-center gap-4 text-[10px] text-gray-600">
                                <span><i class="fa-solid fa-calendar-xmark mr-1"></i>{{ $injury->tanggal_cedera->format('d M Y') }}</span>
                                @if($injury->tanggal_sembuh)
                                    <span><i class="fa-solid fa-calendar-check mr-1 text-green-600"></i>{{ $injury->tanggal_sembuh->format('d M Y') }}</span>
                                @endif
                                <span><i class="fa-solid fa-user-doctor mr-1"></i>{{ $injury->recordedBy->name }}</span>
                            </div>
                            @if($injury->catatan_medis)
                                <p class="text-gray-600 text-xs mt-1 italic">{{ $injury->catatan_medis }}</p>
                            @endif
                        </div>
                        <div class="flex items-center gap-2 flex-shrink-0">
                            {{-- Update Status --}}
                            <form action="{{ route('admin.injury.update', $injury) }}" method="POST" class="flex items-center gap-1">
                                @csrf
                                @method('PATCH')
                                <select name="status" onchange="this.form.submit()"
                                    class="bg-gray-900 border border-gray-800 text-white text-xs rounded-lg px-2 py-1.5 focus:outline-none focus:border-red-800 appearance-none cursor-pointer">
                                    <option value="aktif" {{ $injury->status === 'aktif' ? 'selected' : '' }}>Aktif</option>
                                    <option value="monitoring" {{ $injury->status === 'monitoring' ? 'selected' : '' }}>Monitoring</option>
                                    <option value="pulih" {{ $injury->status === 'pulih' ? 'selected' : '' }}>Pulih</option>
                                </select>
                            </form>
                            {{-- Hapus --}}
                            <form action="{{ route('admin.injury.destroy', $injury) }}" method="POST"
                                onsubmit="return confirm('Hapus catatan cedera ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    class="w-7 h-7 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 inline-flex items-center justify-center transition-all">
                                    <i class="fa-solid fa-trash text-xs"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Riwayat BMI --}}
    <div class="bg-gray-950 border border-gray-800 rounded-2xl overflow-hidden mb-10">
        <div class="px-6 py-4 border-b border-gray-800 flex items-center justify-between">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-blue-900/20 rounded-lg flex items-center justify-center">
                    <i class="fa-solid fa-weight-scale text-blue-400 text-xs"></i>
                </div>
                <h2 class="text-white font-bold uppercase tracking-widest text-xs">Riwayat BMI</h2>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('admin.bmi.create', $athlete) }}"
                    class="flex items-center gap-1 text-blue-500 hover:text-blue-400 text-xs font-bold uppercase tracking-widest transition-colors">
                    <i class="fa-solid fa-plus text-[10px]"></i> Input BMI
                </a>
                <a href="{{ route('admin.reports.bmi.pdf', $athlete) }}"
                    class="flex items-center gap-1 text-gray-400 hover:text-white text-xs font-bold uppercase tracking-widest transition-colors">
                    <i class="fa-solid fa-file-pdf text-[10px]"></i> Download PDF
                </a>
            </div>
        </div>

        @if($athlete->bmiRecords->isEmpty())
            <div class="text-center py-12">
                <i class="fa-solid fa-weight-scale text-gray-700 text-3xl mb-3"></i>
                <p class="text-gray-500 text-sm">Belum ada data BMI.</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead>
                        <tr class="border-b border-gray-800 text-gray-600 text-[11px] uppercase tracking-widest">
                            <th class="text-left px-6 py-3">Tanggal</th>
                            <th class="text-center px-6 py-3">Tinggi</th>
                            <th class="text-center px-6 py-3">Berat</th>
                            <th class="text-center px-6 py-3">BMI</th>
                            <th class="text-center px-6 py-3">Status</th>
                            <th class="text-center px-6 py-3">Hapus</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @foreach($athlete->bmiRecords as $record)
                        <tr class="hover:bg-gray-900/50 transition-colors">
                            <td class="px-6 py-3 text-gray-400">{{ $record->recorded_date->format('d M Y') }}</td>
                            <td class="px-6 py-3 text-center text-white">{{ $record->height_cm }} cm</td>
                            <td class="px-6 py-3 text-center text-white">{{ $record->weight_kg }} kg</td>
                            <td class="px-6 py-3 text-center text-white font-black text-base">{{ $record->bmi_value }}</td>
                            <td class="px-6 py-3 text-center">
                                <span class="px-2 py-1 rounded-full text-xs font-black
                                    {{ $record->bmi_status === 'Normal'   ? 'bg-green-900/50 text-green-400' :
                                      ($record->bmi_status === 'Kurang'   ? 'bg-blue-900/50 text-blue-400' :
                                      ($record->bmi_status === 'Gemuk'    ? 'bg-yellow-900/50 text-yellow-400' :
                                      'bg-red-900/50 text-red-400')) }}">
                                    {{ $record->bmi_status }}
                                </span>
                            </td>
                            <td class="px-6 py-3 text-center">
                                <form action="{{ route('admin.bmi.destroy', $record) }}" method="POST"
                                    onsubmit="return confirm('Hapus data BMI ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="w-8 h-8 rounded-lg bg-gray-800 hover:bg-red-900 text-gray-400 hover:text-red-400 inline-flex items-center justify-center transition-all">
                                        <i class="fa-solid fa-trash text-xs"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>

</div>
@endsection