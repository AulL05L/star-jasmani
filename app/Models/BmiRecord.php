<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BmiRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'athlete_id', 'recorded_by', 'height_cm',
        'weight_kg', 'bmi_value', 'bmi_status',
        'recorded_date', 'notes',
    ];

    protected function casts(): array
    {
        return [
            'height_cm'     => 'float',
            'weight_kg'     => 'float',
            'bmi_value'     => 'float',
            'recorded_date' => 'date',
        ];
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public static function calculate(float $heightCm, float $weightKg): array
    {
        $heightM = $heightCm / 100;
        $bmi     = round($weightKg / ($heightM ** 2), 2);
        $status  = match (true) {
            $bmi < 17.0 => 'Kurang',
            $bmi < 25.0 => 'Normal',
            $bmi < 30.0 => 'Gemuk',
            default     => 'Obesitas',
        };
        return [$bmi, $status];
    }
}