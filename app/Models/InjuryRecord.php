<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InjuryRecord extends Model
{
    protected $fillable = [
        'athlete_id',
        'recorded_by',
        'injury_type',
        'description',
        'injury_date',
        'recovery_notes',
        'is_recovered',
        'recovered_at',
    ];

    protected function casts(): array
    {
        return [
            'injury_date'  => 'date',
            'recovered_at' => 'date',
            'is_recovered' => 'boolean',
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

    public function scopeActive($query)
    {
        return $query->where('is_recovered', false);
    }
}
