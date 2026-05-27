<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSamaptaRequest;
use App\Models\Athlete;
use App\Models\Institution;
use App\Models\SamaptaScore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class SamaptaController extends Controller
{
    public function index(Request $request): View
    {
        $tahun       = $request->get('tahun', now()->year);
        $parameterKe = $request->get('parameter_ke');
        $grade       = $request->get('grade');
        $gender      = $request->get('gender');
        $search      = $request->get('search');

        $query = SamaptaScore::with(['athlete.user', 'coach'])
                    ->whereYear('assessment_date', $tahun)
                    ->orderBy('assessment_date', 'desc')
                    ->orderBy('parameter_ke', 'asc');

        if ($parameterKe) {
            $query->where('parameter_ke', $parameterKe);
        }

        if ($grade) {
            $query->where('grade', $grade);
        }

        if ($gender) {
            $query->whereHas('athlete', fn($q) =>
                $q->where('gender', $gender === 'putra' ? 'pria' : 'wanita')
            );
        }

        if ($search) {
            $query->whereHas('athlete.user', fn($q) =>
                $q->where('name', 'like', "%{$search}%")
            );
        }

        $scores = $query->paginate(20)->withQueryString();

        $tahunList = SamaptaScore::selectRaw('YEAR(assessment_date) as tahun')
            ->groupBy('tahun')
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        if ($tahunList->isEmpty()) {
            $tahunList = collect([now()->year]);
        }

        $parameterList = SamaptaScore::whereYear('assessment_date', $tahun)
            ->distinct()
            ->orderBy('parameter_ke')
            ->pluck('parameter_ke');

        $stats = SamaptaScore::whereYear('assessment_date', $tahun)
            ->when($parameterKe, fn($q) => $q->where('parameter_ke', $parameterKe))
            ->selectRaw('
                COUNT(*) as total,
                ROUND(AVG(score_final), 1) as avg_nilai,
                SUM(CASE WHEN grade = "A" THEN 1 ELSE 0 END) as grade_a,
                SUM(CASE WHEN grade = "B" THEN 1 ELSE 0 END) as grade_b,
                SUM(CASE WHEN grade = "C" THEN 1 ELSE 0 END) as grade_c,
                SUM(CASE WHEN grade = "D" THEN 1 ELSE 0 END) as grade_d,
                SUM(CASE WHEN grade = "E" THEN 1 ELSE 0 END) as grade_e
            ')
            ->first();

        return view('admin.samapta.index', compact(
            'scores', 'tahunList', 'parameterList', 'stats',
            'tahun', 'parameterKe', 'grade', 'gender', 'search'
        ));
    }

    public function create(Request $request): View
    {
        $athletes = Athlete::with(['user', 'institution'])
                        ->whereHas('user', fn($q) => $q->where('is_active', true))
                        ->get();

        $selectedAthlete = $request->filled('athlete_id')
            ? Athlete::with(['user', 'institution'])->findOrFail($request->input('athlete_id'))
            : null;

        $institutions = Institution::where('is_active', true)->get();

        return view('admin.samapta.create', compact('athletes', 'selectedAthlete', 'institutions'));
    }

    public function store(StoreSamaptaRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $athlete   = Athlete::findOrFail($validated['athlete_id']);

        $institution = null;
        if ($athlete->institution_id) {
            $institution = Institution::find($athlete->institution_id);
        }
        if (!$institution && isset($validated['institution'])) {
            $institution = Institution::where('code', $validated['institution'])->first();
        }
        if (!$institution) {
            $institution = Institution::where('code', 'POLRI')->first();
        }

        $score = new SamaptaScore();
        $score->athlete_id      = $athlete->id;
        $score->assessed_by     = Auth::id();
        $score->institution     = $validated['institution'] ?? 'POLRI';
        $score->institution_id  = $institution?->id;
        $score->session_label   = $validated['session_label'] ?? null;
        $score->assessment_date = $validated['assessment_date'];
        $score->parameter_ke    = $validated['parameter_ke'] ?? 1;
        $score->tahun_sesi      = isset($validated['assessment_date'])
            ? \Carbon\Carbon::parse($validated['assessment_date'])->year
            : now()->year;

        $score->raw_lari_meter      = $validated['raw_lari_meter'] ?? null;
        $score->raw_pushup_reps     = $validated['raw_pushup_reps'] ?? null;
        $score->raw_situp_reps      = $validated['raw_situp_reps'] ?? null;
        $score->raw_pullup_reps     = $validated['raw_pullup_reps'] ?? null;
        $score->raw_shuttle_seconds = $validated['raw_shuttle_seconds'] ?? null;
        $score->raw_renang_seconds  = $validated['raw_renang_seconds'] ?? null;
        $score->notes               = $validated['notes'] ?? null;

        $score->calculateAndFill($athlete->gender, $institution);
        $score->save();

        return redirect()
            ->route('admin.samapta.show', $score)
            ->with('success', "Nilai berhasil disimpan. Nilai Akhir: {$score->score_final}");
    }

    public function show(SamaptaScore $samaptaScore): View
    {
        $samaptaScore->load(['athlete.user', 'coach']);

        $history = SamaptaScore::where('athlete_id', $samaptaScore->athlete_id)
                        ->where('id', '!=', $samaptaScore->id)
                        ->orderBy('assessment_date', 'desc')
                        ->limit(5)->get();

        return view('admin.samapta.show', compact('samaptaScore', 'history'));
    }

    public function edit(SamaptaScore $samaptaScore): View
    {
        $samaptaScore->load(['athlete.user']);
        $athletes     = Athlete::with('user')->get();
        $institutions = Institution::where('is_active', true)->get();
        return view('admin.samapta.edit', compact('samaptaScore', 'athletes', 'institutions'));
    }

    public function update(StoreSamaptaRequest $request, SamaptaScore $samaptaScore): RedirectResponse
    {
        $validated   = $request->validated();
        $athlete     = Athlete::findOrFail($validated['athlete_id']);
        $institution = Institution::where('code', $validated['institution'] ?? 'POLRI')->first()
                    ?? Institution::where('code', 'POLRI')->first();

        $samaptaScore->fill($validated);
        $samaptaScore->calculateAndFill($athlete->gender, $institution);
        $samaptaScore->save();

        return redirect()
            ->route('admin.samapta.show', $samaptaScore)
            ->with('success', 'Nilai berhasil diperbarui.');
    }

    public function destroy(SamaptaScore $samaptaScore): RedirectResponse
    {
        $samaptaScore->delete();
        return redirect()
            ->route('admin.samapta.index')
            ->with('success', 'Data penilaian berhasil dihapus.');
    }
}