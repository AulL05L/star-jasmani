<?php

namespace App\Http\Controllers\Member;

use App\Http\Controllers\Controller;
use App\Services\KebugaranScoring;
use Illuminate\Http\Request;

class KebugaranDashboardController extends Controller
{
    public function index(Request $request)
    {
        $athlete = auth()->user()->athlete;

        if (!$athlete) {
            return view('member.kebugaran.dashboard', [
                'athlete'      => null,
                'period'       => null,
                'periods'      => collect(),
                'sessions'     => collect(),
                'parameters'   => KebugaranScoring::$parameters,
                'latestScores' => [],
                'totalScore'   => ['score' => 0, 'category' => 'kurang'],
                'chartData'    => [],
            ]);
        }

        $periods = $athlete->kebugaranPeriods()->with('sessions')->get();

        // Pilih periode: dari query param atau terbaru
        $selectedPeriodId = $request->query('period_id');
        $period = $selectedPeriodId
            ? $periods->firstWhere('id', $selectedPeriodId)
            : $periods->first();

        $sessions     = collect();
        $latestScores = [];
        $chartData    = [];
        $totalScore   = ['score' => 0, 'category' => 'kurang'];

        if ($period) {
            $sessions = $period->sessions()->with('scores')->get();
            $gender   = $athlete->gender;

            // Skor terbaru (sesi terakhir)
            $lastSession = $sessions->last();
            if ($lastSession) {
                foreach (array_keys(KebugaranScoring::$parameters) as $param) {
                    $s = $lastSession->scoreFor($param);
                    if ($s) {
                        $cat = KebugaranScoring::category($param, $s->value, $gender);
                        $latestScores[$param] = [
                            'value'      => $s->value,
                            'category'   => $cat,
                            'label'      => KebugaranScoring::categoryLabel($cat),
                            'color'      => KebugaranScoring::categoryColor($cat),
                            'percentage' => KebugaranScoring::percentage($param, $s->value, $gender),
                            'range'      => KebugaranScoring::$rangeLabels[$param][$gender] ?? '',
                        ];
                    }
                }
                $totalScore = KebugaranScoring::totalScore(
                    collect($latestScores)->mapWithKeys(fn($v, $k) => [$k => $v['value']])->all(),
                    $gender
                );
            }

            // Data chart: per sesi per parameter
            $paramKeys = array_keys(KebugaranScoring::$parameters);
            $labels    = [];
            $datasets  = [];

            foreach ($paramKeys as $param) {
                $datasets[$param] = [];
            }

            foreach ($sessions as $sess) {
                $labels[] = 'Sesi ' . $sess->session_number . ' (' . $sess->date->format('d/m') . ')';
                foreach ($paramKeys as $param) {
                    $s = $sess->scoreFor($param);
                    $datasets[$param][] = $s ? (float) $s->value : null;
                }
            }

            $chartData = ['labels' => $labels, 'datasets' => $datasets];
        }

        return view('member.kebugaran.dashboard', compact(
            'athlete', 'period', 'periods', 'sessions',
            'latestScores', 'totalScore', 'chartData'
        ) + ['parameters' => KebugaranScoring::$parameters]);
    }
}
