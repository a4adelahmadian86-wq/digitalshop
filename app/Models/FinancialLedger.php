<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class FinancialLedger extends Model
{
    protected $fillable = [
        'entry_number',
        'event_type',
        'account_code',
        'account_name',
        'side',
        'amount',
        'currency',
        'reference_type',
        'reference_id',
        'user_id',
        'order_id',
        'description',
        'metadata',
        'posted_at',
    ];

    protected $casts = [
        'amount' => 'integer',
        'metadata' => 'array',
        'posted_at' => 'datetime',
    ];

    public function reference(): MorphTo
    {
        return $this->morphTo();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
