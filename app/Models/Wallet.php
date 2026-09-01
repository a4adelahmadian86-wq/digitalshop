<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Wallet extends Model
{
    protected $fillable = [
        'user_id',
        'balance',
        'currency',
        'is_active',
    ];

    protected $casts = [
        'balance' => 'integer',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(
            WalletTransaction::class
        );
    }

    public function topups(): HasMany
    {
        return $this->hasMany(
            WalletTopup::class
        );
    }

    public function hasBalance(
        int $amount
    ): bool {
        return $this->balance >= $amount;
    }
}
