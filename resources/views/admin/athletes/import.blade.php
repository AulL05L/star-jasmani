@extends('layouts.app')
@section('title', 'Import Atlet')
@section('content')
<div class="min-h-screen bg-black text-white p-6 lg:p-10">

    <div class="mb-8 flex items-start justify-between">
        <div>
            <p class="text-red-800 font-bold uppercase tracking-[0.3em] text-[11px] mb-1">Admin Panel · Peserta</p>
            <h1 class="text-3xl font-extrabold tracking-tighter">Import <span class="text-red-800">Atlet Massal</span></h1>
            <p class="text-gray-500 text-sm mt-1">Upload file Excel atau CSV untuk menambahkan banyak atlet sekaligus</p>
        </div>
        <a href="{{ route('admin.athletes.index') }}"
            class="flex items-center gap-2 text-gray-500 hover:text-white text-xs font-bold uppercase tracking-wider transition-colors">
            <i class="fa-solid fa-arrow-left text-xs"></i> Kembali
        </a>
    </div>

    {{-- Hasil Import --}}
    @if(session('import_results'))
    @php $res = session('import_results'); @endphp
    <div class="max-w-3xl mb-6">
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                <i class="fa-solid fa-clipboard-check text-green-500"></i> Hasil Import
            </h2>
            <div class="grid grid-cols-3 gap-4 mb-5">
                <div class="bg-green-900/20 border border-green-900/50 rounded-xl p-4 text-center">
                    <p class="text-green-400 text-3xl font-black">{{ $res['success'] }}</p>
                    <p class="text-gray-500 text-xs mt-1 uppercase tracking-widest">Berhasil</p>
                </div>
                <div class="bg-red-900/20 border border-red-900/50 rounded-xl p-4 text-center">
                    <p class="text-red-400 text-3xl font-black">{{ $res['skipped'] }}</p>
                    <p class="text-gray-500 text-xs mt-1 uppercase tracking-widest">Dilewati</p>
                </div>
                <div class="bg-gray-900 border border-gray-800 rounded-xl p-4 text-center">
                    <p class="text-white text-3xl font-black">{{ $res['success'] + $res['skipped'] }}</p>
                    <p class="text-gray-500 text-xs mt-1 uppercase tracking-widest">Total Baris</p>
                </div>
            </div>

            @if(!empty($res['results']))
            <div class="mb-4">
                <p class="text-green-400 text-xs font-bold uppercase tracking-widest mb-2">✓ Berhasil Diimport</p>
                <div class="bg-black rounded-xl p-3 max-h-40 overflow-y-auto space-y-1">
                    @foreach($res['results'] as $r)
                        <p class="text-green-300 text-xs">{{ $r }}</p>
                    @endforeach
                </div>
            </div>
            @endif

            @if(!empty($res['errors']))
            <div>
                <p class="text-red-400 text-xs font-bold uppercase tracking-widest mb-2">✗ Dilewati / Error</p>
                <div class="bg-black rounded-xl p-3 max-h-40 overflow-y-auto space-y-1">
                    @foreach($res['errors'] as $e)
                        <p class="text-red-300 text-xs">{{ $e }}</p>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 max-w-5xl">

        {{-- Form Upload --}}
        <div class="space-y-4">
            <form action="{{ route('admin.athletes.import.store') }}" method="POST"
                enctype="multipart/form-data" id="import-form">
                @csrf
                <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
                    <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-5 flex items-center gap-2">
                        <i class="fa-solid fa-file-arrow-up text-red-800"></i> Upload File
                    </h2>

                    @if($errors->any())
                        <div class="bg-red-900/20 border border-red-800/50 rounded-xl p-3 mb-4">
                            @foreach($errors->all() as $error)
                                <p class="text-red-400 text-sm">• {{ $error }}</p>
                            @endforeach
                        </div>
                    @endif

                    {{-- Drop Zone --}}
                    <div id="drop-zone"
                        class="border-2 border-dashed border-gray-700 hover:border-red-800 rounded-2xl p-8 text-center transition-all cursor-pointer mb-4"
                        onclick="document.getElementById('file-input').click()">
                        <i class="fa-solid fa-file-excel text-gray-600 text-4xl mb-3"></i>
                        <p class="text-white font-bold text-sm mb-1" id="file-name">Klik atau drag file ke sini</p>
                        <p class="text-gray-600 text-xs">Format: .xlsx, .xls, .csv · Maks 5MB</p>
                        <input type="file" name="file" id="file-input" accept=".xlsx,.xls,.csv"
                            class="hidden" onchange="onFileChange(this)" />
                    </div>

                    <button type="submit" id="submit-btn"
                        class="w-full bg-red-800 hover:bg-red-700 disabled:opacity-50 disabled:cursor-not-allowed text-white font-black uppercase tracking-widest text-sm py-4 rounded-2xl transition-all flex items-center justify-center gap-3">
                        <i class="fa-solid fa-upload"></i> Proses Import
                    </button>
                </div>
            </form>
        </div>

        {{-- Panduan Format --}}
        <div class="bg-gray-950 border border-gray-800 rounded-2xl p-6">
            <h2 class="text-white font-bold uppercase tracking-widest text-xs mb-5 flex items-center gap-2">
                <i class="fa-solid fa-circle-info text-blue-500"></i> Format File
            </h2>

            <p class="text-gray-400 text-xs mb-4">Baris pertama harus berisi header kolom. Kolom yang tersedia:</p>

            <div class="bg-black rounded-xl overflow-hidden mb-4">
                <table class="w-full text-xs">
                    <thead>
                        <tr class="bg-gray-900 text-gray-500 uppercase tracking-widest text-[10px]">
                            <th class="text-left px-4 py-2">Kolom</th>
                            <th class="text-left px-4 py-2">Keterangan</th>
                            <th class="text-center px-4 py-2">Wajib</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-900">
                        @php
                            $cols = [
                                ['nama',          'Nama lengkap atlet',                      true],
                                ['email',         'Email untuk login',                       true],
                                ['gender',        'pria / wanita',                          true],
                                ['password',      'Default: member12345',                   false],
                                ['institusi',     'POLRI / TNI-AD / TNI-AL / TNI-AU',       false],
                                ['batch',         'Nama batch/angkatan',                    false],
                                ['nik',           'NIK 16 digit',                           false],
                                ['telepon',       'No. HP',                                 false],
                                ['tanggal_lahir', 'Format: YYYY-MM-DD',                     false],
                                ['tinggi',        'Tinggi badan (cm)',                      false],
                                ['berat',         'Berat badan (kg)',                       false],
                            ];
                        @endphp
                        @foreach($cols as [$col, $desc, $required])
                        <tr class="hover:bg-gray-900/50">
                            <td class="px-4 py-2 font-mono text-red-400">{{ $col }}</td>
                            <td class="px-4 py-2 text-gray-400">{{ $desc }}</td>
                            <td class="px-4 py-2 text-center">
                                @if($required)
                                    <span class="text-red-500 font-black">✓</span>
                                @else
                                    <span class="text-gray-700">—</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            {{-- Contoh --}}
            <p class="text-gray-500 text-[10px] uppercase tracking-widest mb-2">Contoh isi file:</p>
            <div class="bg-black rounded-xl p-3 overflow-x-auto">
                <pre class="text-green-400 text-[10px] leading-relaxed">nama,email,gender,institusi,batch
Budi Santoso,budi@email.com,pria,POLRI,Batch-2026
Siti Rahayu,siti@email.com,wanita,TNI-AD,Batch-2026</pre>
            </div>

            <div class="mt-4 bg-yellow-900/20 border border-yellow-900/50 rounded-xl p-3">
                <p class="text-yellow-400 text-xs font-bold mb-1">⚠ Catatan</p>
                <ul class="text-gray-400 text-xs space-y-1">
                    <li>• Email yang sudah terdaftar akan dilewati</li>
                    <li>• Password default: <span class="text-white font-mono">member12345</span></li>
                    <li>• Instansi tidak dikenal akan default ke POLRI</li>
                    <li>• Proses tidak bisa dibatalkan setelah upload</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function onFileChange(input) {
    const file = input.files[0];
    if (!file) return;
    document.getElementById('file-name').textContent = file.name;
    document.getElementById('drop-zone').classList.add('border-red-800');
    document.getElementById('drop-zone').classList.remove('border-gray-700');
}

// Drag & drop
const dropZone = document.getElementById('drop-zone');
dropZone.addEventListener('dragover', e => { e.preventDefault(); dropZone.classList.add('border-red-800'); });
dropZone.addEventListener('dragleave', () => dropZone.classList.remove('border-red-800'));
dropZone.addEventListener('drop', e => {
    e.preventDefault();
    const file = e.dataTransfer.files[0];
    if (file) {
        document.getElementById('file-input').files = e.dataTransfer.files;
        onFileChange(document.getElementById('file-input'));
    }
});
</script>
@endpush