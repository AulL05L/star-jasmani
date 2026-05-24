<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Imports\AthletesImport;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class AthleteImportController extends Controller
{
    public function create(): View
    {
        return view('admin.athletes.import');
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