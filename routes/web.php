<?php

use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\SamaptaController;
use App\Http\Controllers\Member\DashboardController as MemberDashboard;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AthleteController;
use App\Http\Controllers\Admin\BatchController;
use App\Http\Controllers\Admin\BmiController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\InjuryController;
use App\Http\Controllers\Admin\InstitutionController;
use App\Http\Controllers\Admin\AthletePerformanceController;
use App\Http\Controllers\Admin\AthleteImportController;


// ── Public ──
Route::get('/', fn() => view('welcome'))->name('home');
Route::get('/daftar', fn() => view('daftar'))->name('daftar');

// ── Auth ──
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');
});

Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth')
    ->name('logout');

// ── Admin ──
Route::middleware(['auth', 'role:admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
        Route::get('reports/bmi/{athlete}', [ReportController::class, 'bmiPdf'])->name('reports.bmi.pdf');

        Route::get('samapta', [SamaptaController::class, 'index'])->name('samapta.index');
        Route::get('samapta/create', [SamaptaController::class, 'create'])->name('samapta.create');
        Route::post('samapta', [SamaptaController::class, 'store'])->name('samapta.store');
        Route::get('samapta/{samaptaScore}', [SamaptaController::class, 'show'])->name('samapta.show');
        Route::get('samapta/{samaptaScore}/edit', [SamaptaController::class, 'edit'])->name('samapta.edit');
        Route::put('samapta/{samaptaScore}', [SamaptaController::class, 'update'])->name('samapta.update');
        Route::delete('samapta/{samaptaScore}', [SamaptaController::class, 'destroy'])->name('samapta.destroy');

        // ⚠️ Import HARUS di atas athletes/{athlete}
        Route::get('athletes/import', [AthleteImportController::class, 'create'])->name('athletes.import');
        Route::get('athletes/import/template', [AthleteImportController::class, 'template'])->name('athletes.import.template');
        Route::post('athletes/import', [AthleteImportController::class, 'store'])->name('athletes.import.store');

        Route::get('athletes', [AthleteController::class, 'index'])->name('athletes.index');
        Route::get('athletes/create', [AthleteController::class, 'create'])->name('athletes.create');
        Route::post('athletes', [AthleteController::class, 'store'])->name('athletes.store');
        Route::get('athletes/{athlete}', [AthleteController::class, 'show'])->name('athletes.show');
        Route::get('athletes/{athlete}/edit', [AthleteController::class, 'edit'])->name('athletes.edit');
        Route::put('athletes/{athlete}', [AthleteController::class, 'update'])->name('athletes.update');
        Route::delete('athletes/{athlete}', [AthleteController::class, 'destroy'])->name('athletes.destroy');
        Route::get('athletes/{athlete}/performance', [AthletePerformanceController::class, 'show'])->name('athletes.performance');

        Route::get('bmi/create/{athlete}', [BmiController::class, 'create'])->name('bmi.create');
        Route::post('bmi/{athlete}', [BmiController::class, 'store'])->name('bmi.store');
        Route::delete('bmi/{bmiRecord}', [BmiController::class, 'destroy'])->name('bmi.destroy');

        Route::get('reports/samapta/{samaptaScore}', [ReportController::class, 'samaptaPdf'])->name('reports.samapta.pdf');
        Route::get('reports/rekap/parameter', [ReportController::class, 'rekapParameter'])->name('reports.rekap.parameter');
        Route::get('reports/rekap/tahun', [ReportController::class, 'rekapTahun'])->name('reports.rekap.tahun');

        Route::resource('batches', BatchController::class);
        Route::post('batches/{batch}/parameters', [BatchController::class, 'storeParameter'])->name('batches.parameters.store');
        Route::delete('batches/{batch}/parameters/{parameter}', [BatchController::class, 'destroyParameter'])->name('batches.parameters.destroy');

        Route::get('institutions', [InstitutionController::class, 'index'])->name('institutions.index');
        Route::get('institutions/{institution}/edit', [InstitutionController::class, 'edit'])->name('institutions.edit');
        Route::patch('institutions/{institution}', [InstitutionController::class, 'update'])->name('institutions.update');

        Route::get('benchmark', fn() => view('admin.benchmark'))->name('benchmark');

        Route::get('athletes/{athlete}/injury/create', [InjuryController::class, 'create'])->name('injury.create');
        Route::post('athletes/{athlete}/injury', [InjuryController::class, 'store'])->name('injury.store');
        Route::patch('injury/{injury}', [InjuryController::class, 'update'])->name('injury.update');
        Route::delete('injury/{injury}', [InjuryController::class, 'destroy'])->name('injury.destroy');
    });

// ── Member ──
Route::middleware(['auth', 'role:member'])
    ->prefix('member')
    ->name('member.')
    ->group(function () {
        Route::get('/dashboard', [MemberDashboard::class, 'index'])->name('dashboard');
        
        // Tambahkan route ini
        Route::get('/reports/{samaptaScore}/pdf', [MemberDashboard::class, 'downloadPdf'])->name('reports.pdf');
    });
