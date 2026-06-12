<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KebugaranSession extends Model
{
    protected $fillable = ['period_id', 'session_number', 'date', 'notes'];

    protected function casts(): array
    {
        return ['date' => 'date'];
    }

    public function period(): BelongsTo
    {
        return $this->belongsTo(KebugaranPeriod::class, 'period_id');
    }

    public function scores(): HasMany
    {
        return $this->hasMany(KebugaranScore::class, 'session_id');
    }

    public function scoreFor(string $parameter): ?KebugaranScore
    {
        return $this->scores->firstWhere('parameter', $parameter);
    }
}
