<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\KebugaranPeriod;
use App\Models\KebugaranSession;
use App\Models\KebugaranScore;
use App\Services\KebugaranScoring;
use Illuminate\Http\Request;

class KebugaranController extends Controller
{
    // ── LIST semua periode (bisa filter per atlet) ────────────────────────────
    public function index(Request $request)
    {
        $athletes = Athlete::with('user')
            ->whereHas('user')
            ->orderBy('id')
            ->get();

        $query = KebugaranPeriod::with(['athlete.user', 'sessions'])
            ->orderBy('start_date', 'desc');

        if ($request->filled('athlete_id')) {
            $query->where('athlete_id', $request->athlete_id);
        }

        $periods = $query->paginate(20)->withQueryString();

        return view('admin.kebugaran.index', compact('periods', 'athletes'));
    }

    // ── FORM buat periode baru ────────────────────────────────────────────────
    public function createPeriod()
    {
        $athletes = Athlete::with('user')->whereHas('user')->orderBy('id')->get();
        return view('admin.kebugaran.create-period', compact('athletes'));
    }

    public function storePeriod(Request $request)
    {
        $data = $request->validate([
            'athlete_id' => 'required|exists:athletes,id',
            'name'       => 'required|string|max:100',
            'start_date' => 'required|date',
            'end_date'   => 'nullable|date|after_or_equal:start_date',
            'notes'      => 'nullable|string|max:500',
        ]);

        $period = KebugaranPeriod::create($data);

        return redirect()->route('admin.kebugaran.period.show', $period)
            ->with('success', 'Periode berhasil dibuat.');
    }

    // ── DETAIL periode (list sesi) ────────────────────────────────────────────
    public function showPeriod(KebugaranPeriod $period)
    {
        $period->load(['athlete.user', 'sessions.scores']);
        $parameters = KebugaranScoring::$parameters;
        $gender     = $period->athlete->gender;

        // Bangun tabel: sesi × parameter → nilai + kategori
        $rows = [];
        foreach ($period->sessions as $session) {
            $row = ['session' => $session, 'scores' => []];
            foreach (array_keys($parameters) as $param) {
                $s = $session->scoreFor($param);
                if ($s) {
                    $cat = KebugaranScoring::category($param, $s->value, $gender);
                    $row['scores'][$param] = [
                        'value'    => $s->value,
                        'category' => $cat,
                        'label'    => KebugaranScoring::categoryLabel($cat),
                        'color'    => KebugaranScoring::categoryColor($cat),
                    ];
                } else {
                    $row['scores'][$param] = null;
                }
            }
            $rows[] = $row;
        }

        return view('admin.kebugaran.show-period', compact('period', 'parameters', 'rows'));
    }

    public function destroyPeriod(KebugaranPeriod $period)
    {
        $period->delete();
        return redirect()->route('admin.kebugaran.index')
            ->with('success', 'Periode dihapus.');
    }

    // ── FORM input sesi baru ──────────────────────────────────────────────────
    public function createSession(KebugaranPeriod $period)
    {
        $period->load('athlete');
        $parameters   = KebugaranScoring::$parameters;
        $nextNumber   = $period->sessions()->max('session_number') + 1;
        $athlete      = $period->athlete;
        $bmiSuggested = $this->calcBmi($athlete->height_cm, $athlete->weight_kg);

        return view('admin.kebugaran.create-session', compact(
            'period', 'parameters', 'nextNumber', 'athlete', 'bmiSuggested'
        ));
    }

    public function storeSession(Request $request, KebugaranPeriod $period)
    {
        $request->validate([
            'date'    => 'required|date',
            'notes'   => 'nullable|string|max:500',
            'scores'  => 'required|array',
            'scores.*'=> 'nullable|numeric|min:0|max:9999',
        ]);

        $session = $period->sessions()->create([
            'session_number' => $period->sessions()->max('session_number') + 1,
            'date'           => $request->date,
            'notes'          => $request->notes,
        ]);

        foreach ($request->scores as $param => $value) {
            if ($value !== null && $value !== '') {
                KebugaranScore::updateOrCreate(
                    ['session_id' => $session->id, 'parameter' => $param],
                    ['value' => (float) $value]
                );
            }
        }

        return redirect()->route('admin.kebugaran.period.show', $period)
            ->with('success', "Sesi #{$session->session_number} berhasil disimpan.");
    }

    // ── EDIT sesi ─────────────────────────────────────────────────────────────
    public function editSession(KebugaranSession $session)
    {
        $session->load(['period.athlete', 'scores']);
        $parameters   = KebugaranScoring::$parameters;
        $athlete      = $session->period->athlete;
        $bmiSuggested = $this->calcBmi($athlete->height_cm, $athlete->weight_kg);

        return view('admin.kebugaran.edit-session', compact(
            'session', 'parameters', 'athlete', 'bmiSuggested'
        ));
    }

    public function updateSession(Request $request, KebugaranSession $session)
    {
        $request->validate([
            'date'    => 'required|date',
            'notes'   => 'nullable|string|max:500',
            'scores'  => 'required|array',
            'scores.*'=> 'nullable|numeric|min:0|max:9999',
        ]);

        $session->update([
            'date'  => $request->date,
            'notes' => $request->notes,
        ]);

        foreach ($request->scores as $param => $value) {
            if ($value !== null && $value !== '') {
                KebugaranScore::updateOrCreate(
                    ['session_id' => $session->id, 'parameter' => $param],
                    ['value' => (float) $value]
                );
            } else {
                KebugaranScore::where('session_id', $session->id)
                    ->where('parameter', $param)->delete();
            }
        }

        return redirect()->route('admin.kebugaran.period.show', $session->period_id)
            ->with('success', "Sesi #{$session->session_number} diperbarui.");
    }

    public function destroySession(KebugaranSession $session)
    {
        $periodId = $session->period_id;
        $session->delete();
        return redirect()->route('admin.kebugaran.period.show', $periodId)
            ->with('success', 'Sesi dihapus.');
    }

    // ── HELPER ────────────────────────────────────────────────────────────────
    private function calcBmi(?float $height, ?float $weight): ?float
    {
        if (!$height || !$weight || $height <= 0) return null;
        return round($weight / (($height / 100) ** 2), 1);
    }
}
