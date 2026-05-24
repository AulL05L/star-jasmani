<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InjuryTrack extends Model
{
    protected $fillable = [
        'athlete_id', 'recorded_by',
        'bagian_tubuh', 'deskripsi_cedera',
        'tanggal_cedera', 'tanggal_sembuh',
        'status', 'catatan_medis',
    ];

    protected function casts(): array
    {
        return [
            'tanggal_cedera' => 'date',
            'tanggal_sembuh' => 'date',
        ];
    }

    public function athlete(): BelongsTo
    {
        return $this->belongsTo(Athlete::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }
}