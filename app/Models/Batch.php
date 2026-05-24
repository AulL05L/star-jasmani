<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Batch extends Model
{
    protected $fillable = [
        'name',
        'year',
        'institution_code',
        'description',
        'max_parameters',
        'started_at',
        'ended_at',
    ];

    protected function casts(): array
    {
        return [
            'year'           => 'integer',
            'max_parameters' => 'integer',
            'started_at'     => 'date',
            'ended_at'       => 'date',
        ];
    }

    public function athletes(): HasMany
    {
        return $this->hasMany(Athlete::class, 'batch_id');
    }

    public function parameters(): HasMany
    {
        return $this->hasMany(BatchParameter::class, 'batch_id')
                    ->orderBy('parameter_number');
    }

    public function getAthleteCountAttribute(): int
    {
        return $this->athletes()->count();
    }

    public function scopeYear($query, int $year)
    {
        return $query->where('year', $year);
    }
}
