<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WalletTopup extends Model
{
    protected $fillable = [
        'user_id',
        'wallet_id',
        'amount',
        'status',
        'authority',
        'ref_id',
        'paid_at',
        'description',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'integer',
        'paid_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function wallet(): BelongsTo
    {
        return $this->belongsTo(
            Wallet::class
        );
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }
}
