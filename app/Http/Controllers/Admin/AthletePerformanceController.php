<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use Illuminate\View\View;

class AthletePerformanceController extends Controller
{
    public function show(Athlete $athlete): View
    {
        $athlete->load([
            'user',
            'institution',
            'samaptaScores' => fn($q) => $q->orderBy('parameter_ke'),
            'bmiRecords',
            'injuryTracks.recordedBy',
        ]);

        $scores    = $athlete->samaptaScores;
        $latestBmi = $athlete->bmiRecords->first();

        // Stats per parameter untuk grafik
        $parameterLabels = $scores->map(fn($s) => 'P' . $s->parameter_ke)->values();
        $nilaiAkhir      = $scores->pluck('score_final')->values();
        $nilaiJasmani    = $scores->pluck('score_ukg_avg')->values();
        $nilaiRenang     = $scores->pluck('score_renang')->values();
        $nilaiLari       = $scores->pluck('score_lari')->values();
        $nilaiPushup     = $scores->pluck('score_pushup')->values();
        $nilaiSitup      = $scores->pluck('score_situp')->values();
        $nilaiPullup     = $scores->pluck('score_pullup')->values();
        $nilaiShuttle    = $scores->pluck('score_shuttle')->values();

        $latestScore = $scores->last();

        // Progress vs parameter sebelumnya
        $prevScore = $scores->count() >= 2 ? $scores->nth($scores->count() - 1) : null;

        return view('admin.athletes.performance', compact(
            'athlete', 'scores', 'latestBmi', 'latestScore',
            'parameterLabels', 'nilaiAkhir', 'nilaiJasmani', 'nilaiRenang',
            'nilaiLari', 'nilaiPushup', 'nilaiSitup', 'nilaiPullup', 'nilaiShuttle'
        ));
    }
}