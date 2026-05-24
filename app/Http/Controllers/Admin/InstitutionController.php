<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Institution;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InstitutionController extends Controller
{
    public function index(): View
    {
        $institutions = Institution::withCount(['athletes', 'samaptaScores'])->get();
        return view('admin.institutions.index', compact('institutions'));
    }

    public function edit(Institution $institution): View
    {
        return view('admin.institutions.edit', compact('institution'));
    }

    public function update(Request $request, Institution $institution): RedirectResponse
    {
        $request->validate([
            'name'          => ['required', 'string', 'max:100'],
            'ukg_weight'    => ['required', 'numeric', 'min:0', 'max:100'],
            'renang_weight' => ['required', 'numeric', 'min:0', 'max:100'],
            'passing_grade' => ['required', 'numeric', 'min:0', 'max:100'],
            'is_active'     => ['nullable', 'boolean'],
        ], [
            'ukg_weight.max'    => 'Bobot UKG maksimal 100.',
            'renang_weight.max' => 'Bobot Renang maksimal 100.',
        ]);

        // Validasi total bobot harus 100
        $total = $request->ukg_weight + $request->renang_weight;
        if ($total != 100) {
            return back()->withErrors([
                'ukg_weight' => "Total bobot UKG + Renang harus 100%. Sekarang: {$total}%"
            ])->withInput();
        }

        $institution->update([
            'name'          => $request->name,
            'ukg_weight'    => $request->ukg_weight,
            'renang_weight' => $request->renang_weight,
            'passing_grade' => $request->passing_grade,
            'is_active'     => $request->boolean('is_active', true),
        ]);

        return redirect()
            ->route('admin.institutions.index')
            ->with('success', "Pengaturan {$institution->code} berhasil diperbarui.");
    }
}