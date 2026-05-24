<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\InjuryTrack;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InjuryController extends Controller
{
    public function create(Athlete $athlete)
    {
        return view('admin.injury.create', compact('athlete'));
    }

    public function store(Request $request, Athlete $athlete): RedirectResponse
    {
        $request->validate([
            'bagian_tubuh'     => ['required', 'string', 'max:100'],
            'deskripsi_cedera' => ['required', 'string', 'max:1000'],
            'tanggal_cedera'   => ['required', 'date', 'before_or_equal:today'],
            'tanggal_sembuh'   => ['nullable', 'date', 'after_or_equal:tanggal_cedera'],
            'status'           => ['required', 'in:aktif,pulih,monitoring'],
            'catatan_medis'    => ['nullable', 'string', 'max:1000'],
        ]);

        InjuryTrack::create([
            'athlete_id'       => $athlete->id,
            'recorded_by'      => Auth::id(),
            'bagian_tubuh'     => $request->bagian_tubuh,
            'deskripsi_cedera' => $request->deskripsi_cedera,
            'tanggal_cedera'   => $request->tanggal_cedera,
            'tanggal_sembuh'   => $request->tanggal_sembuh,
            'status'           => $request->status,
            'catatan_medis'    => $request->catatan_medis,
        ]);

        return redirect()
            ->route('admin.athletes.show', $athlete)
            ->with('success', 'Riwayat cedera berhasil dicatat.');
    }

    public function update(Request $request, InjuryTrack $injury): RedirectResponse
    {
        $request->validate([
            'status'        => ['required', 'in:aktif,pulih,monitoring'],
            'tanggal_sembuh'=> ['nullable', 'date'],
            'catatan_medis' => ['nullable', 'string', 'max:1000'],
        ]);

        $injury->update($request->only(['status', 'tanggal_sembuh', 'catatan_medis']));

        return redirect()
            ->route('admin.athletes.show', $injury->athlete_id)
            ->with('success', 'Status cedera diperbarui.');
    }

    public function destroy(InjuryTrack $injury): RedirectResponse
    {
        $athleteId = $injury->athlete_id;
        $injury->delete();

        return redirect()
            ->route('admin.athletes.show', $athleteId)
            ->with('success', 'Catatan cedera dihapus.');
    }
}