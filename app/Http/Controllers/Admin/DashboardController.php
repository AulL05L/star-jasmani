<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\SamaptaScore;
use App\Models\User;
use App\Models\Institution;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        // ── Filter ──
        $tahun      = $request->get('tahun', now()->year);
        $batchId    = $request->get('batch_id');
        $genderFilter = $request->get('gender', 'all');

        // ── Stats Cards ──
        $totalMember  = User::where('role', 'member')->count();
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

        // ── Trend Perkembangan Nilai (7 hari terakhir) ──
        $trendData = SamaptaScore::selectRaw('DATE(assessment_date) as tanggal, AVG(score_final) as avg_nilai')
            ->where('assessment_date', '>=', now()->subDays(6)->startOfDay())
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();

        $trendLabels = $trendData->pluck('tanggal')->map(fn($d) =>
            \Carbon\Carbon::parse($d)->isoFormat('D MMM')
        );
        $trendNilai  = $trendData->pluck('avg_nilai')->map(fn($v) => round($v, 1));

        // Rata-rata keseluruhan (garis putus-putus)
        $trendAvgLine = $trendLabels->map(fn() => $rataRataNilai);

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

        // ── Grafik 3: Trend Kelulusan per Parameter ──
        $passingGrade = 70;
        $trendKelulusanQuery = SamaptaScore::query()
            ->whereYear('assessment_date', $tahun)
            ->whereNotNull('parameter_ke')
            ->groupBy('parameter_ke')
            ->selectRaw("
                parameter_ke,
                COUNT(*) as total,
                SUM(CASE WHEN score_final >= {$passingGrade} THEN 1 ELSE 0 END) as lulus,
                ROUND(SUM(CASE WHEN score_final >= {$passingGrade} THEN 1 ELSE 0 END) * 100.0 / COUNT(*), 1) as persen_lulus
            ")
            ->orderBy('parameter_ke');

        if ($batchId) {
            $trendKelulusanQuery->where('batch_id', $batchId);
        }

        $trendKelulusan = $trendKelulusanQuery->get();

        // ── Member Terbaru ──
        $memberTerbaru = Athlete::with(['user', 'samaptaScores' => function($q) use ($tahun) {
            $q->whereYear('assessment_date', $tahun)->orderBy('parameter_ke', 'desc');
        }])
        ->orderBy('created_at', 'desc')
        ->limit(5)
        ->get();

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
            'totalMember', 'totalPutra', 'totalPutri',
            'memberBaruBulanIni', 'memberBaruBulanLalu',
            'programBerjalan', 'parameterTerakhir',
            'rataRataNilai', 'trendRataRata',
            'evaluasiMingguIni',
            'trendLabels', 'trendNilai', 'trendAvgLine',
            'gradeDistribution', 'totalGrade',
            'parameterLabels', 'avgPutraPerParameter', 'avgPutriPerParameter',
            'komponenPutra', 'komponenPutri',
            'trendKelulusan', 'passingGrade',
            'memberTerbaru', 'topPerformer', 'statusFisik',
            'batches', 'tahunList', 'tahun', 'batchId', 'genderFilter'
        ));
    }
}