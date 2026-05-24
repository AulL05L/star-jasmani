<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Athlete;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AthleteController extends Controller
{
    public function index(): View
    {
        $athletes = Athlete::with('user')
                        ->orderBy('created_at', 'desc')
                        ->paginate(20);

        return view('admin.athletes.index', compact('athletes'));
    }

    public function create(): View
    {
        $institutions = \App\Models\Institution::where('is_active', true)->get();
        return view('admin.athletes.create', compact('institutions'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'unique:users,email'],
            'password'           => ['required', 'string', 'min:8'],
            'gender'             => ['required', 'in:pria,wanita'],
            'birth_date'         => ['nullable', 'date'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'height_cm'          => ['nullable', 'numeric', 'min:100', 'max:250'],
            'weight_kg'          => ['nullable', 'numeric', 'min:30', 'max:200'],
            'target_institution' => ['nullable', 'string'],
            'batch'              => ['nullable', 'string', 'max:50'],
            'nik'                => ['nullable', 'string', 'max:20', 'unique:athletes,nik'],
        ], [
            'email.unique'    => 'Email sudah terdaftar.',
            'nik.unique'      => 'NIK sudah terdaftar.',
            'password.min'    => 'Password minimal 8 karakter.',
            'height_cm.min'   => 'Tinggi badan tidak valid.',
            'weight_kg.min'   => 'Berat badan tidak valid.',
        ]);

        DB::transaction(function () use ($request) {
            $user = User::create([
                'name'      => $request->name,
                'email'     => $request->email,
                'password'  => Hash::make($request->password),
                'role'      => 'member',
                'is_active' => true,
            ]);

            Athlete::create([
                'user_id'            => $user->id,
                'gender'             => $request->gender,
                'nik'                => $request->nik,
                'birth_date'         => $request->birth_date,
                'phone'              => $request->phone,
                'height_cm'          => $request->height_cm,
                'weight_kg'          => $request->weight_kg,
                'target_institution' => $request->target_institution,
                'batch'              => $request->batch,
                'institution_id'     => $request->institution_id,
            ]);
        });

        return redirect()
            ->route('admin.athletes.index')
            ->with('success', "Peserta {$request->name} berhasil ditambahkan.");
    }

    public function show(Athlete $athlete)
    {
        $athlete->load([
            'user',
            'samaptaScores',
            'bmiRecords',
            'injuryTracks.recordedBy',
        ]);

        return view('admin.athletes.show', compact('athlete'));
    }

    public function edit(Athlete $athlete): View
    {
        $athlete->load('user');
        $institutions = \App\Models\Institution::where('is_active', true)->get();
        return view('admin.athletes.edit', compact('athlete', 'institutions'));
    }

    public function update(Request $request, Athlete $athlete): RedirectResponse
    {
        $request->validate([
            'name'               => ['required', 'string', 'max:255'],
            'email'              => ['required', 'email', 'unique:users,email,' . $athlete->user_id],
            'gender'             => ['required', 'in:pria,wanita'],
            'birth_date'         => ['nullable', 'date'],
            'phone'              => ['nullable', 'string', 'max:20'],
            'height_cm'          => ['nullable', 'numeric', 'min:100', 'max:250'],
            'weight_kg'          => ['nullable', 'numeric', 'min:30', 'max:200'],
            'target_institution' => ['nullable', 'string'],
            'batch'              => ['nullable', 'string', 'max:50'],
            'nik'                => ['nullable', 'string', 'max:20', 'unique:athletes,nik,' . $athlete->id],
            'password'           => ['nullable', 'string', 'min:8'],
        ]);

        DB::transaction(function () use ($request, $athlete) {
            $userData = [
                'name'  => $request->name,
                'email' => $request->email,
            ];
            if ($request->filled('password')) {
                $userData['password'] = Hash::make($request->password);
            }
            $athlete->user->update($userData);

            $athlete->update([
                'gender'             => $request->gender,
                'nik'                => $request->nik,
                'birth_date'         => $request->birth_date,
                'phone'              => $request->phone,
                'height_cm'          => $request->height_cm,
                'weight_kg'          => $request->weight_kg,
                'target_institution' => $request->target_institution,
                'batch'              => $request->batch,
            ]);
        });

        return redirect()
            ->route('admin.athletes.show', $athlete)
            ->with('success', 'Data peserta berhasil diperbarui.');
    }

    public function destroy(Athlete $athlete): RedirectResponse
    {
        $name = $athlete->user->name;
        DB::transaction(function () use ($athlete) {
            $athlete->delete();
            $athlete->user->delete();
        });

        return redirect()
            ->route('admin.athletes.index')
            ->with('success', "Peserta {$name} berhasil dihapus.");
    }
}