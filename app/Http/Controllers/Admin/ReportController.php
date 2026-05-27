<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\SamaptaScore;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ReportController extends Controller
{
    public function samaptaPdf(SamaptaScore $samaptaScore): Response
    {
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

    public function bmiPdf(Athlete $athlete): Response
    {
        $athlete->load(['user', 'bmiRecords']);

        $pdf = Pdf::loadView('admin.reports.bmi', compact('athlete'))
                  ->setPaper([0, 0, 841.89, 595.28], 'landscape')
                  ->set_option('defaultFont', 'dejavu sans')
                  ->set_option('isHtml5ParserEnabled', true)
                  ->set_option('isRemoteEnabled', false)
                  ->set_option('dpi', 96);

        $filename = 'Laporan-Kebugaran-' . str_replace(' ', '-', $athlete->user->name) . '-' . now()->format('d-m-Y') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * PDF Rekap per Parameter — semua atlet dalam satu parameter
     */
    public function rekapParameter(Request $request): Response
    {
        $tahun       = $request->get('tahun', now()->year);
        $parameterKe = $request->get('parameter_ke', 1);

        $scores = SamaptaScore::with(['athlete.user'])
            ->whereYear('assessment_date', $tahun)
            ->where('parameter_ke', $parameterKe)
            ->whereNotNull('score_final')
            ->orderBy('score_final', 'desc')
            ->get();

        $stats = [
            'total'     => $scores->count(),
            'avg'       => round($scores->avg('score_final'), 1),
            'tertinggi' => round($scores->max('score_final'), 1),
            'terendah'  => round($scores->min('score_final'), 1),
            'grade_a'   => $scores->where('grade', 'A')->count(),
            'grade_b'   => $scores->where('grade', 'B')->count(),
            'grade_c'   => $scores->where('grade', 'C')->count(),
            'grade_d'   => $scores->where('grade', 'D')->count(),
            'grade_e'   => $scores->where('grade', 'E')->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.rekap-parameter', compact(
            'scores', 'stats', 'tahun', 'parameterKe'
        ))
            ->setPaper([0, 0, 841.89, 595.28], 'landscape')
            ->set_option('defaultFont', 'dejavu sans')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', false)
            ->set_option('dpi', 96);

        $filename = "Rekap-Parameter-{$parameterKe}-Tahun-{$tahun}.pdf";

        return $pdf->download($filename);
    }

    /**
     * PDF Rekap per Tahun — semua parameter dalam satu tahun
     */
    public function rekapTahun(Request $request): Response
    {
        $tahun = $request->get('tahun', now()->year);

        // Ambil semua data dikelompokkan per parameter
        $allScores = SamaptaScore::with(['athlete.user'])
            ->whereYear('assessment_date', $tahun)
            ->whereNotNull('score_final')
            ->orderBy('parameter_ke')
            ->orderBy('score_final', 'desc')
            ->get();

        $parameterList = $allScores->pluck('parameter_ke')->unique()->sort()->values();

        // Statistik per parameter
        $statsPerParameter = $parameterList->mapWithKeys(fn($p) => [
            $p => [
                'total'     => $allScores->where('parameter_ke', $p)->count(),
                'avg'       => round($allScores->where('parameter_ke', $p)->avg('score_final'), 1),
                'tertinggi' => round($allScores->where('parameter_ke', $p)->max('score_final'), 1),
                'terendah'  => round($allScores->where('parameter_ke', $p)->min('score_final'), 1),
                'grade_a'   => $allScores->where('parameter_ke', $p)->where('grade', 'A')->count(),
                'grade_b'   => $allScores->where('parameter_ke', $p)->where('grade', 'B')->count(),
                'grade_c'   => $allScores->where('parameter_ke', $p)->where('grade', 'C')->count(),
                'grade_d'   => $allScores->where('parameter_ke', $p)->where('grade', 'D')->count(),
                'grade_e'   => $allScores->where('parameter_ke', $p)->where('grade', 'E')->count(),
            ]
        ]);

        // Statistik keseluruhan tahun
        $statsTotal = [
            'total'     => $allScores->count(),
            'avg'       => round($allScores->avg('score_final'), 1),
            'tertinggi' => round($allScores->max('score_final'), 1),
            'terendah'  => round($allScores->min('score_final'), 1),
            'grade_a'   => $allScores->where('grade', 'A')->count(),
            'grade_b'   => $allScores->where('grade', 'B')->count(),
            'grade_c'   => $allScores->where('grade', 'C')->count(),
            'grade_d'   => $allScores->where('grade', 'D')->count(),
            'grade_e'   => $allScores->where('grade', 'E')->count(),
        ];

        $pdf = Pdf::loadView('admin.reports.rekap-tahun', compact(
            'allScores', 'parameterList', 'statsPerParameter', 'statsTotal', 'tahun'
        ))
            ->setPaper([0, 0, 841.89, 595.28], 'landscape')
            ->set_option('defaultFont', 'dejavu sans')
            ->set_option('isHtml5ParserEnabled', true)
            ->set_option('isRemoteEnabled', false)
            ->set_option('dpi', 96);

        $filename = "Rekap-Tahunan-{$tahun}.pdf";

        return $pdf->download($filename);
    }
}