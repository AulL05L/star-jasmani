<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Batch;
use App\Models\BatchParameter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BatchController extends Controller
{
    public function index(): View
    {
        $batches = Batch::withCount('athletes')
                        ->with('parameters')
                        ->orderByDesc('year')
                        ->orderBy('name')
                        ->paginate(15);

        return view('admin.batches.index', compact('batches'));
    }

    public function create(): View
    {
        return view('admin.batches.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'year'             => ['required', 'integer', 'min:2020', 'max:2099'],
            'institution_code' => ['required', 'string', 'in:POLRI,TNI-AD,TNI-AL,TNI-AU'],
            'description'      => ['nullable', 'string', 'max:500'],
            'max_parameters'   => ['required', 'integer', 'min:1', 'max:4'],
            'started_at'       => ['nullable', 'date'],
            'ended_at'         => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $batch = Batch::create($validated);

        return redirect()
            ->route('admin.batches.show', $batch)
            ->with('success', "Angkatan \"{$batch->name}\" berhasil dibuat.");
    }

    public function show(Batch $batch): View
    {
        $batch->load(['athletes.user', 'parameters.samaptaScores']);

        return view('admin.batches.show', compact('batch'));
    }

    public function edit(Batch $batch): View
    {
        return view('admin.batches.edit', compact('batch'));
    }

    public function update(Request $request, Batch $batch): RedirectResponse
    {
        $validated = $request->validate([
            'name'             => ['required', 'string', 'max:100'],
            'year'             => ['required', 'integer', 'min:2020', 'max:2099'],
            'institution_code' => ['required', 'string', 'in:POLRI,TNI-AD,TNI-AL,TNI-AU'],
            'description'      => ['nullable', 'string', 'max:500'],
            'max_parameters'   => ['required', 'integer', 'min:1', 'max:4'],
            'started_at'       => ['nullable', 'date'],
            'ended_at'         => ['nullable', 'date', 'after_or_equal:started_at'],
        ]);

        $batch->update($validated);

        return redirect()
            ->route('admin.batches.show', $batch)
            ->with('success', 'Data angkatan berhasil diperbarui.');
    }

    public function destroy(Batch $batch): RedirectResponse
    {
        $name = $batch->name;
        $batch->delete();

        return redirect()
            ->route('admin.batches.index')
            ->with('success', "Angkatan \"{$name}\" berhasil dihapus.");
    }

    // ── Parameter Management ──────────────────────────────────────

    public function storeParameter(Request $request, Batch $batch): RedirectResponse
    {
        $request->validate([
            'parameter_number' => ['required', 'integer', 'min:1', 'max:4',
                'unique:batch_parameters,parameter_number,NULL,id,batch_id,' . $batch->id],
            'label'            => ['required', 'string', 'max:100'],
            'test_date'        => ['nullable', 'date'],
            'description'      => ['nullable', 'string', 'max:500'],
        ], [
            'parameter_number.unique' => 'Parameter dengan nomor tersebut sudah ada di angkatan ini.',
        ]);

        BatchParameter::create([
            'batch_id'         => $batch->id,
            'parameter_number' => $request->parameter_number,
            'label'            => $request->label,
            'test_date'        => $request->test_date,
            'description'      => $request->description,
        ]);

        return redirect()
            ->route('admin.batches.show', $batch)
            ->with('success', "Parameter \"{$request->label}\" berhasil ditambahkan.");
    }

    public function destroyParameter(Batch $batch, BatchParameter $parameter): RedirectResponse
    {
        $label = $parameter->label;
        $parameter->delete();

        return redirect()
            ->route('admin.batches.show', $batch)
            ->with('success', "Parameter \"{$label}\" berhasil dihapus.");
    }
}
