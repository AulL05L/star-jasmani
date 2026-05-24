<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreSamaptaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'athlete_id'          => ['required', 'integer', 'exists:athletes,id'],
            'assessment_date'     => ['required', 'date', 'before_or_equal:today'],
            'institution' => ['required', 'string', 'exists:institutions,code'],
            'session_label'       => ['nullable', 'string', 'max:100'],
            'raw_lari_meter'      => ['nullable', 'integer', 'min:0', 'max:6000'],
            'raw_pushup_reps'     => ['nullable', 'integer', 'min:0', 'max:200'],
            'raw_situp_reps'      => ['nullable', 'integer', 'min:0', 'max:200'],
            'raw_pullup_reps'     => ['nullable', 'integer', 'min:0', 'max:100'],
            'raw_shuttle_seconds' => ['nullable', 'numeric', 'min:5', 'max:60'],
            'raw_renang_seconds' => ['nullable', 'numeric', 'min:10', 'max:999'],
            'parameter_ke' => ['required', 'integer', 'min:1', 'max:4'],
        ];
    }

    public function messages(): array
    {
        return [
            'athlete_id.required'  => 'Peserta wajib dipilih.',
            'athlete_id.exists'    => 'Peserta tidak ditemukan.',
            'assessment_date.before_or_equal' => 'Tanggal tidak boleh lebih dari hari ini.',
            'institution.in'       => 'Institusi tidak valid.',
        ];
    }
}