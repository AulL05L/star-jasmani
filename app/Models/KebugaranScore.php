<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KebugaranScore extends Model
{
    protected $fillable = ['session_id', 'parameter', 'value'];

    protected function casts(): array
    {
        return ['value' => 'float'];
    }

    public function session(): BelongsTo
    {
        return $this->belongsTo(KebugaranSession::class, 'session_id');
    }
}
