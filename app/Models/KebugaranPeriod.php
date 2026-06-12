<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KebugaranPeriod extends Model
{
    protected $fillable = ['athlete_id', 'name', 'start_date', 'end_date', 'notes'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date'   => 'date',
        ];
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function sessions(): HasMany
    {
        return $this->hasMany(KebugaranSession::class, 'period_id')
                    ->orderBy('session_number');
    }
}
