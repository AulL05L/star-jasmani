<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PublicScoreResult extends Model
{
    protected $fillable = [
        'token',
        'calculator_type',
        'gender',
        'raw_lari_meter',
        'raw_pullup_reps',
        'raw_situp_reps',
        'raw_pushup_reps',
        'raw_shuttle_seconds',
        'raw_renang_seconds',
        'score_lari',
        'score_pullup',
        'score_situp',
        'score_pushup',
        'score_shuttle',
        'score_jasmani_b',
        'score_renang',
        'score_ukg_avg',
        'score_final',
        'grade',
        'grade_label',
        'is_lulus',
        'ukg_weight',
        'renang_weight',
        'expires_at',
    ];

    protected $casts = [
        'is_lulus'            => 'boolean',
        'expires_at'          => 'datetime',
        'raw_lari_meter'      => 'integer',
        'raw_pullup_reps'     => 'integer',
        'raw_situp_reps'      => 'integer',
        'raw_pushup_reps'     => 'integer',
        'raw_shuttle_seconds' => 'float',
        'raw_renang_seconds'  => 'float',
        'score_lari'          => 'float',
        'score_pullup'        => 'float',
        'score_situp'         => 'float',
        'score_pushup'        => 'float',
        'score_shuttle'       => 'float',
        'score_jasmani_b'     => 'float',
        'score_renang'        => 'float',
        'score_ukg_avg'       => 'float',
        'score_final'         => 'float',
        'ukg_weight'          => 'float',
        'renang_weight'       => 'float',
    ];

    public function pdfOrders(): HasMany
    {
        return $this->hasMany(PdfOrder::class, 'public_score_result_id');
    }
}
