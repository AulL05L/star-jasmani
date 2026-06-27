<?php

namespace App\Http\Controllers;

use App\Models\SamaptaScore;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KalkulatorController extends Controller
{
    public function form(): View
    {
        return view('kalkulator.polri');
    }

    public function hitung(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'gender'              => ['required', 'in:pria,wanita'],
            'raw_lari_meter'      => ['required', 'integer', 'min:0', 'max:6000'],
            'raw_pullup_reps'     => ['required', 'integer', 'min:0', 'max:100'],
            'raw_situp_reps'      => ['required', 'integer', 'min:0', 'max:200'],
            'raw_pushup_reps'     => ['required', 'integer', 'min:0', 'max:200'],
            'raw_shuttle_seconds' => ['required', 'numeric',  'min:5',  'max:60'],
            'raw_renang_seconds'  => ['required', 'numeric',  'min:10', 'max:999'],
        ], [
            'gender.required'              => 'Pilih gender terlebih dahulu.',
            'gender.in'                    => 'Gender tidak valid.',
            'raw_lari_meter.required'      => 'Jarak lari wajib diisi.',
            'raw_lari_meter.integer'       => 'Jarak lari harus bilangan bulat (meter).',
            'raw_lari_meter.max'           => 'Jarak lari maksimal 6000 meter.',
            'raw_pullup_reps.required'     => 'Nilai pull up / chin up wajib diisi.',
            'raw_pullup_reps.integer'      => 'Nilai pull up / chin up harus bilangan bulat.',
            'raw_pullup_reps.max'          => 'Nilai pull up / chin up maksimal 100.',
            'raw_situp_reps.required'      => 'Jumlah sit up wajib diisi.',
            'raw_situp_reps.integer'       => 'Jumlah sit up harus bilangan bulat.',
            'raw_situp_reps.max'           => 'Jumlah sit up maksimal 200.',
            'raw_pushup_reps.required'     => 'Jumlah push up wajib diisi.',
            'raw_pushup_reps.integer'      => 'Jumlah push up harus bilangan bulat.',
            'raw_pushup_reps.max'          => 'Jumlah push up maksimal 200.',
            'raw_shuttle_seconds.required' => 'Waktu shuttle run wajib diisi.',
            'raw_shuttle_seconds.numeric'  => 'Waktu shuttle run harus berupa angka (detik).',
            'raw_shuttle_seconds.min'      => 'Waktu shuttle run minimal 5 detik.',
            'raw_shuttle_seconds.max'      => 'Waktu shuttle run maksimal 60 detik.',
            'raw_renang_seconds.required'  => 'Waktu renang wajib diisi.',
            'raw_renang_seconds.numeric'   => 'Waktu renang harus berupa angka (detik).',
            'raw_renang_seconds.min'       => 'Waktu renang minimal 10 detik.',
            'raw_renang_seconds.max'       => 'Waktu renang maksimal 999 detik.',
        ]);

        $score = new SamaptaScore();
        $score->raw_lari_meter      = $data['raw_lari_meter'];
        $score->raw_pullup_reps     = $data['raw_pullup_reps'];
        $score->raw_situp_reps      = $data['raw_situp_reps'];
        $score->raw_pushup_reps     = $data['raw_pushup_reps'];
        $score->raw_shuttle_seconds = $data['raw_shuttle_seconds'];
        $score->raw_renang_seconds  = $data['raw_renang_seconds'];

        // null institution = POLRI default: 80% UKG + 20% Renang
        $score->calculateAndFill($data['gender']);

        session(['kalkulator_hasil' => [
            'gender'              => $data['gender'],
            'raw_lari_meter'      => $data['raw_lari_meter'],
            'raw_pullup_reps'     => $data['raw_pullup_reps'],
            'raw_situp_reps'      => $data['raw_situp_reps'],
            'raw_pushup_reps'     => $data['raw_pushup_reps'],
            'raw_shuttle_seconds' => $data['raw_shuttle_seconds'],
            'raw_renang_seconds'  => $data['raw_renang_seconds'],
            'score_lari'          => $score->score_lari,
            'score_pullup'        => $score->score_pullup,
            'score_situp'         => $score->score_situp,
            'score_pushup'        => $score->score_pushup,
            'score_shuttle'       => $score->score_shuttle,
            'score_renang'        => $score->score_renang,
            'score_jasmani_b'     => $score->score_jasmani_b,
            'score_ukg_avg'       => $score->score_ukg_avg,
            'score_final'         => $score->score_final,
            'grade'               => $score->grade,
            'grade_label'         => $score->grade_label,
            'is_lulus'            => $score->is_lulus,
        ]]);

        return redirect()->route('kalkulator.polri.hasil');
    }

    public function hasil(): View|RedirectResponse
    {
        $hasil = session('kalkulator_hasil');

        if (! $hasil) {
            return redirect()->route('kalkulator.polri')
                ->with('error', 'Silakan isi form kalkulator terlebih dahulu.');
        }

        return view('kalkulator.hasil', compact('hasil'));
    }
}
