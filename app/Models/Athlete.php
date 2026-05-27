<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;


class Athlete extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'gender',
        'nik',
        'birth_date',
        'phone',
        'height_cm',
        'weight_kg',
        'target_institution',
        'batch',
        'batch_id',
        'photo_path',
        'allowed_parameters',
    ];

    public function institution(): BelongsTo
    {
        return $this->belongsTo(Institution::class);
    }

    public function injuryTracks(): HasMany
    {
        return $this->hasMany(InjuryTrack::class, 'athlete_id');
    }

    protected function casts(): array
    {
        return [
            'birth_date'         => 'date',
            'height_cm'          => 'float',
            'weight_kg'          => 'float',
            'allowed_parameters' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function batchGroup(): BelongsTo
    {
        return $this->belongsTo(Batch::class, 'batch_id');
    }

    public function injuryRecords(): HasMany
    {
        return $this->hasMany(InjuryRecord::class, 'athlete_id')
                    ->orderBy('injury_date', 'desc');
    }

    public function activeInjuries(): HasMany
    {
        return $this->hasMany(InjuryRecord::class, 'athlete_id')
                    ->where('is_recovered', false);
    }

    public function samaptaScores(): HasMany
    {
        return $this->hasMany(SamaptaScore::class, 'athlete_id')
                    ->orderBy('assessment_date', 'desc');
    }

    public function bmiRecords(): HasMany
    {
        return $this->hasMany(BmiRecord::class, 'athlete_id')
                    ->orderBy('recorded_date', 'desc');
    }

    public function getUpperBodyTestAttribute(): string
    {
        return $this->gender === 'pria' ? 'Pull-Up' : 'Chin-Up';
    }

    public function getLatestSamaptaAttribute(): ?SamaptaScore
    {
        return $this->samaptaScores()->first();
    }

    public function getLatestBmiAttribute(): ?BmiRecord
    {
        return $this->bmiRecords()->first();
    }
}