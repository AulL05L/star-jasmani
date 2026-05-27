<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AthletesImport;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AthleteImportController extends Controller
{
    public function create(): View
    {
        return view('admin.athletes.import');
    }

    public function template(): Response
    {
        $headers = [
            'nama', 'email', 'gender', 'password',
            'institusi', 'batch', 'nik', 'telepon',
            'tanggal_lahir', 'tinggi', 'berat',
        ];

        $rows = [
            $headers,
            ['Budi Santoso',  'budi@email.com', 'pria',   'member12345', 'POLRI',  'Batch-2026', '', '', '1995-06-15', '175', '70'],
            ['Siti Rahayu',   'siti@email.com',  'wanita', 'member12345', 'TNI-AD', 'Batch-2026', '', '', '1997-03-22', '162', '55'],
        ];

        $csv = collect($rows)
            ->map(fn($row) => implode(',', array_map(
                fn($v) => str_contains((string) $v, ',') ? '"' . $v . '"' : $v,
                $row
            )))
            ->implode("\n");

        return response($csv, 200, [
            'Content-Type'        => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="template-import-atlet.csv"',
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:5120'],
        ], [
            'file.required' => 'File wajib diupload.',
            'file.mimes'    => 'Format file harus xlsx, xls, atau csv.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        $import = new AthletesImport();
        Excel::import($import, $request->file('file'));

        return redirect()
            ->route('admin.athletes.import')
            ->with('import_results', [
                'success' => $import->success,
                'skipped' => $import->skipped,
                'results' => $import->results,
                'errors'  => $import->errors,
            ]);
    }
}