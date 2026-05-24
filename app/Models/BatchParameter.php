<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BatchParameter extends Model
{
    protected $fillable = [
        'batch_id',
        'parameter_number',
        'label',
        'test_date',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'test_date'        => 'date',
            'parameter_number' => 'integer',
        ];
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(Batch::class);
    }

    public function samaptaScores(): HasMany
    {
        return $this->hasMany(SamaptaScore::class, 'parameter_id');
    }

    public function getAthleteCountAttribute(): int
    {
        return $this->samaptaScores()->count();
    }

    public function getAvgFinalScoreAttribute(): ?float
    {
        $avg = $this->samaptaScores()->avg('score_final');
        return $avg ? round($avg, 2) : null;
    }
}
