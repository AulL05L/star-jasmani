<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use App\Models\SamaptaScore;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;

class DashboardController extends Controller
{
    public function index(): View
    {
        $user    = Auth::user();
        $athlete = $user->athlete;
        $scores  = $athlete
            ? $athlete->samaptaScores()->orderBy('parameter_ke')->get()
            : collect();
        $latestBmi     = $athlete?->latestBmi;
        $selectedScore = $scores->first();
        $allScores     = $scores;
        $activeInjuries = $athlete
            ? $athlete->injuryTracks()->where('status', '!=', 'pulih')->get()
            : collect();

        return view('member.dashboard', compact(
            'user', 'athlete', 'scores', 'latestBmi',
            'selectedScore', 'allScores', 'activeInjuries'
        ));
    }
    public function downloadPdf(SamaptaScore $samaptaScore): Response
    {
        // Pastikan member hanya bisa download laporan miliknya sendiri
        $athlete = Auth::user()->athlete;

        if (!$athlete || $samaptaScore->athlete_id !== $athlete->id) {
            abort(403, 'Anda tidak memiliki akses ke laporan ini.');
        }

        $samaptaScore->load(['athlete.user', 'coach']);

        $pdf = Pdf::loadView('admin.reports.samapta', compact('samaptaScore'))
                ->setPaper([0, 0, 841.89, 595.28], 'landscape')
                ->set_option('defaultFont', 'dejavu sans')
                ->set_option('isHtml5ParserEnabled', true)
                ->set_option('isRemoteEnabled', false)
                ->set_option('dpi', 96);

        $filename = 'Laporan-Samapta-' . str_replace(' ', '-', $samaptaScore->athlete->user->name) . '-' . $samaptaScore->assessment_date->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }
}