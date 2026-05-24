<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\BmiRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class BmiController extends Controller
{
    public function create(Athlete $athlete): View
    {
        $athlete->load(['user', 'bmiRecords']);
        return view('admin.bmi.create', compact('athlete'));
    }

    public function store(Request $request, Athlete $athlete): RedirectResponse
    {
        $request->validate([
            'height_cm'     => ['required', 'numeric', 'min:100', 'max:250'],
            'weight_kg'     => ['required', 'numeric', 'min:30', 'max:200'],
            'recorded_date' => ['required', 'date', 'before_or_equal:today'],
            'notes'         => ['nullable', 'string', 'max:500'],
        ], [
            'height_cm.required' => 'Tinggi badan wajib diisi.',
            'weight_kg.required' => 'Berat badan wajib diisi.',
            'recorded_date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
        ]);

        [$bmi, $status] = BmiRecord::calculate(
            $request->height_cm,
            $request->weight_kg
        );

        BmiRecord::create([
            'athlete_id'    => $athlete->id,
            'recorded_by'   => Auth::id(),
            'height_cm'     => $request->height_cm,
            'weight_kg'     => $request->weight_kg,
            'bmi_value'     => $bmi,
            'bmi_status'    => $status,
            'recorded_date' => $request->recorded_date,
            'notes'         => $request->notes,
        ]);

        $athlete->update([
            'height_cm' => $request->height_cm,
            'weight_kg' => $request->weight_kg,
        ]);

        return redirect()
            ->route('admin.athletes.show', $athlete)
            ->with('success', "BMI {$athlete->user->name}: {$bmi} ({$status})");
    }

    public function destroy(BmiRecord $bmiRecord): RedirectResponse
    {
        $athleteId = $bmiRecord->athlete_id;
        $bmiRecord->delete();

        return redirect()
            ->route('admin.athletes.show', $athleteId)
            ->with('success', 'Data BMI berhasil dihapus.');
    }
}