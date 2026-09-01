<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'status',
        'subtotal',
        'discount',
        'tax',
        'discount_code',
        'total',
        'wallet_amount',
        'gateway_amount',
        'payment_method',
        'payment_authority',
        'payment_ref_id',
        'paid_at',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
        'wallet_amount' => 'integer',
        'gateway_amount' => 'integer',
        'paid_at' => 'datetime',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(
            OrderItem::class
        );
    }

    public function payment(): HasOne
    {
        return $this->hasOne(
            Payment::class
        );
    }

    public function invoice(): HasOne
    {
        return $this->hasOne(
            Invoice::class
        );
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(
            User::class
        );
    }
}
