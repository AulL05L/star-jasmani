<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Institution extends Model
{
    protected $fillable = [
        'code', 'name',
        'ukg_weight', 'renang_weight',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'ukg_weight'    => 'float',
            'renang_weight' => 'float',
            'is_active'     => 'boolean',
        ];
    }

    public function athletes(): HasMany
    {
        return $this->hasMany(Athlete::class);
    }

    public function samaptaScores(): HasMany
    {
        return $this->hasMany(SamaptaScore::class);
    }

    public function hitungNilaiAkhir(float $nilaiTotalJasmani, float $nilaiRenang): float
    {
        $ukgWeight    = $this->ukg_weight / 100;
        $renangWeight = $this->renang_weight / 100;

        return round(
            ($nilaiTotalJasmani * $ukgWeight) +
            ($nilaiRenang * $renangWeight),
            2
        );
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}