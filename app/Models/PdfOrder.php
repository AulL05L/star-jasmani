<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PdfOrder extends Model
{
    protected $fillable = [
        'public_score_result_id',
        'order_number',
        'amount',
        'payment_status',
        'external_order_id',
        'payment_url',
        'qris_url',
        'payment_reference',
        'paid_at',
        'expired_at',
        'raw_callback_payload',
    ];

    protected $casts = [
        'amount'     => 'integer',
        'paid_at'    => 'datetime',
        'expired_at' => 'datetime',
    ];

    public function scoreResult(): BelongsTo
    {
        return $this->belongsTo(PublicScoreResult::class, 'public_score_result_id');
    }
}
