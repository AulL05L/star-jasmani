<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\KebugaranPeriod;
use App\Models\KebugaranSession;
use App\Models\SamaptaScore;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // ── Filter ──
        $tahun        = $request->input('tahun', now()->year);
        $batchId      = $request->input('batch_id');
        $genderFilter = $request->input('gender', 'all');
        $program      = $request->input('program', 'polri'); // 'polri' | 'kebugaran'

        // ── Stats Cards ──
        $totalMember  = User::where('role', 'member')->count();
        $totalPolri   = Athlete::where('program', 'polri')->count();
        $totalKebugaran = Athlete::where('program', 'kebugaran')->count();
        $totalPutra   = Athlete::where('gender', 'pria')->count();
        $totalPutri   = Athlete::where('gender', 'wanita')->count();

        // Member baru bulan ini
        $memberBaruBulanIni = User::where('role', 'member')
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();

        $memberBaruBulanLalu = User::where('role', 'member')
            ->whereMonth('created_at', now()->subMonth()->month)
            ->whereYear('created_at', now()->subMonth()->year)
            ->count();

        // Program berjalan (batch aktif tahun ini)
        $programBerjalan = \App\Models\Batch::where('year', $tahun)
            ->count();

        // Parameter terakhir yang sedang berjalan
        $parameterTerakhir = SamaptaScore::whereYear('assessment_date', $tahun)
            ->max('parameter_ke') ?? 1;

        // Rata-rata nilai keseluruhan
        $rataRataNilai = round(SamaptaScore::whereYear('assessment_date', $tahun)
            ->avg('score_final') ?? 0, 1);

        $rataRataMingguLalu = round(SamaptaScore::whereBetween('assessment_date', [
            now()->subWeek()->startOfWeek(),
            now()->subWeek()->endOfWeek(),
        ])->avg('score_final') ?? 0, 1);

        $trendRataRata = $rataRataNilai > 0
            ? round((($rataRataNilai - $rataRataMingguLalu) / max($rataRataMingguLalu, 1)) * 100, 1)
            : 0;

        // Evaluasi minggu ini
        $evaluasiMingguIni = SamaptaScore::whereBetween('assessment_date', [
            now()->startOfWeek(),
            now()->endOfWeek(),
        ])->count();

        // ── Kebugaran Stats ──
        $kebugaranStats = [
            'total_athletes' => $totalKebugaran,
            'total_periods'  => KebugaranPeriod::count(),
            'total_sessions' => KebugaranSession::count(),
            'last_session'   => KebugaranSession::latest('date')->value('date'),
        ];

        // ── Kebugaran: progress per atlet (untuk chart di tab kebugaran) ──
        $kebugaranAthletes = \App\Models\Athlete::with([
            'user',
            'kebugaranPeriods.sessions.scores',
        ])->where('program', 'kebugaran')->get();

        $kebugaranChartData  = [];   // per atlet: latest score per parameter + progress vs sebelumnya
        $kebugaranParamAvg   = [];   // rata-rata skor per parameter semua atlet (session terbaru)
        $paramKeys = array_keys(\App\Services\KebugaranScoring::$parameters);

        foreach ($kebugaranAthletes as $a) {
            $gender   = $a->gender;
            $allSessions = $a->kebugaranPeriods
                ->flatMap(fn($p) => $p->sessions)
                ->sortBy('date');

            $lastSess = $allSessions->last();
            $prevSess = $allSessions->count() >= 2 ? $allSessions->nth(2)->last() : null;

            if (!$lastSess) continue;

            $latestScores = [];
            $prevScores   = [];
            foreach ($paramKeys as $pk) {
                $sv = $lastSess->scores->firstWhere('parameter', $pk);
                $latestScores[$pk] = $sv ? (float) $sv->value : null;

                if ($prevSess) {
                    $pv = $prevSess->scores->firstWhere('parameter', $pk);
                    $prevScores[$pk] = $pv ? (float) $pv->value : null;
                }
            }

            $totalLatest = \App\Services\KebugaranScoring::totalScore(
                array_filter($latestScores, fn($v) => $v !== null), $gender
            );
            $totalPrev = $prevSess
                ? \App\Services\KebugaranScoring::totalScore(
                    array_filter($prevScores, fn($v) => $v !== null), $gender
                  )
                : null;

            $kebugaranChartData[] = [
                'name'         => $a->user->name,
                'gender'       => $gender,
                'totalScore'   => $totalLatest['score'],
                'totalPrev'    => $totalPrev ? $totalPrev['score'] : null,
                'delta'        => $totalPrev ? ($totalLatest['score'] - $totalPrev['score']) : null,
                'category'     => $totalLatest['category'],
                'scores'       => $latestScores,
                'prevScores'   => $prevScores,
                'sessionCount' => $allSessions->count(),
            ];
        }

        // Rata-rata per parameter (semua atlet kebugaran, session terbaru)
        foreach ($paramKeys as $pk) {
            $vals = collect($kebugaranChartData)->pluck("scores.{$pk}")->filter()->values();
            $kebugaranParamAvg[$pk] = $vals->count() ? round($vals->avg(), 1) : null;
        }

        // ── Distribusi Grade ──
        $gradeDistribution = SamaptaScore::whereYear('assessment_date', $tahun)
            ->whereNotNull('grade')
            ->selectRaw('grade, COUNT(*) as total')
            ->groupBy('grade')
            ->orderBy('grade')
            ->pluck('total', 'grade');

        $totalGrade = $gradeDistribution->sum();

        // ── Grafik 1: Rata-rata per Parameter (Putra vs Putri) ──
        $avgPerParameterQuery = SamaptaScore::query()
            ->join('athletes', 'samapta_scores.athlete_id', '=', 'athletes.id')
            ->whereYear('samapta_scores.assessment_date', $tahun)
            ->whereNotNull('samapta_scores.parameter_ke')
            ->groupBy('samapta_scores.parameter_ke', 'athletes.gender')
            ->selectRaw('
                samapta_scores.parameter_ke,
                athletes.gender,
                ROUND(AVG(samapta_scores.score_final), 1) as avg_nilai_akhir,
                COUNT(*) as jumlah
            ')
            ->orderBy('samapta_scores.parameter_ke');

        if ($batchId) {
            $avgPerParameterQuery->where('samapta_scores.batch_id', $batchId);
        }

        $avgPerParameter = $avgPerParameterQuery->get();

        $parameterList = $avgPerParameter->pluck('parameter_ke')->unique()->sort()->values();

        $avgPutraPerParameter = $parameterList->map(function ($p) use ($avgPerParameter) {
            $data = $avgPerParameter->where('parameter_ke', $p)->where('gender', 'pria')->first();
            return $data ? $data->avg_nilai_akhir : 0;
        });

        $avgPutriPerParameter = $parameterList->map(function ($p) use ($avgPerParameter) {
            $data = $avgPerParameter->where('parameter_ke', $p)->where('gender', 'wanita')->first();
            return $data ? $data->avg_nilai_akhir : 0;
        });

        $parameterLabels = $parameterList->map(fn($p) => "Parameter {$p}");

        // ── Grafik 2: Distribusi Komponen Tes per Kelompok ──
        $komponenQuery = SamaptaScore::query()
            ->join('athletes', 'samapta_scores.athlete_id', '=', 'athletes.id')
            ->whereYear('samapta_scores.assessment_date', $tahun)
            ->selectRaw('
                athletes.gender,
                ROUND(AVG(samapta_scores.score_lari), 1)    as avg_lari,
                ROUND(AVG(samapta_scores.score_pushup), 1)  as avg_pushup,
                ROUND(AVG(samapta_scores.score_situp), 1)   as avg_situp,
                ROUND(AVG(samapta_scores.score_pullup), 1)  as avg_pullup,
                ROUND(AVG(samapta_scores.score_shuttle), 1) as avg_shuttle,
                ROUND(AVG(samapta_scores.score_renang), 1)  as avg_renang
            ')
            ->groupBy('athletes.gender');

        if ($batchId) {
            $komponenQuery->where('samapta_scores.batch_id', $batchId);
        }
        if ($genderFilter !== 'all') {
            $komponenQuery->where('athletes.gender', $genderFilter === 'putra' ? 'pria' : 'wanita');
        }

        $komponenData = $komponenQuery->get();
        $komponenPutra = $komponenData->where('gender', 'pria')->first();
        $komponenPutri = $komponenData->where('gender', 'wanita')->first();

        // ── Grafik 3: Distribusi Grade per Parameter ──
        $gradePerParameterQuery = SamaptaScore::query()
            ->whereYear('assessment_date', $tahun)
            ->whereNotNull('parameter_ke')
            ->whereNotNull('grade')
            ->groupBy('parameter_ke', 'grade')
            ->selectRaw('parameter_ke, grade, COUNT(*) as total')
            ->orderBy('parameter_ke')
            ->orderBy('grade');

        if ($batchId) {
            $gradePerParameterQuery->where('batch_id', $batchId);
        }

        $gradePerParameterRaw = $gradePerParameterQuery->get();
        $gradeParamList = $gradePerParameterRaw->pluck('parameter_ke')->unique()->sort()->values();
        $gradeColors = ['A' => '#4ade80','B' => '#60a5fa','C' => '#facc15','D' => '#fb923c','E' => '#f87171'];
        $gradeDatasets = collect(['A','B','C','D','E'])->map(fn($g) => [
            'label'           => "Grade {$g}",
            'data'            => $gradeParamList->map(fn($p) =>
                $gradePerParameterRaw->where('parameter_ke', $p)->where('grade', $g)->first()?->total ?? 0
            )->values(),
            'backgroundColor' => $gradeColors[$g],
        ]);

        // ── Member Terbaru (filtered by program) ──
        $memberTerbaruQuery = Athlete::with(['user', 'samaptaScores' => function($q) use ($tahun) {
            $q->whereYear('assessment_date', $tahun)->orderBy('parameter_ke', 'desc');
        }, 'kebugaranPeriods'])
        ->where('program', $program)
        ->orderBy('created_at', 'desc')
        ->limit(5);

        $memberTerbaru = $memberTerbaruQuery->get();

        // ── Top Performer ──
        $topPerformer = SamaptaScore::with('athlete.user')
            ->whereYear('assessment_date', $tahun)
            ->whereNotNull('score_final')
            ->orderBy('score_final', 'desc')
            ->limit(3)
            ->get()
            ->unique('athlete_id')
            ->values();

        // ── Status Fisik Rata-rata ──
        $statusFisik = SamaptaScore::whereYear('assessment_date', $tahun)
            ->selectRaw('
                ROUND(AVG(score_lari), 1)    as avg_lari,
                ROUND(AVG(score_pushup), 1)  as avg_pushup,
                ROUND(AVG(score_situp), 1)   as avg_situp,
                ROUND(AVG(score_pullup), 1)  as avg_pullup,
                ROUND(AVG(score_shuttle), 1) as avg_shuttle,
                ROUND(AVG(score_renang), 1)  as avg_renang,
                ROUND(AVG(score_final), 1)   as avg_final
            ')
            ->first();

        // ── Batch list untuk filter ──
        $batches = \App\Models\Batch::where('year', $tahun)
            ->orderBy('name')
            ->get();

        $tahunList = SamaptaScore::selectRaw('YEAR(assessment_date) as tahun')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([now()->year]);
        }

        return view('admin.dashboard', compact(
            'totalMember', 'totalPutra', 'totalPutri', 'totalPolri', 'totalKebugaran',
            'memberBaruBulanIni', 'memberBaruBulanLalu',
            'programBerjalan', 'parameterTerakhir',
            'rataRataNilai', 'trendRataRata',
            'evaluasiMingguIni',
            'kebugaranStats', 'kebugaranChartData', 'kebugaranParamAvg',
            'gradeDistribution', 'totalGrade',
            'parameterLabels', 'avgPutraPerParameter', 'avgPutriPerParameter',
            'komponenPutra', 'komponenPutri',
            'gradeParamList', 'gradeDatasets',
            'memberTerbaru', 'topPerformer', 'statusFisik',
            'batches', 'tahunList', 'tahun', 'batchId', 'genderFilter', 'program'
        ));
    }
}