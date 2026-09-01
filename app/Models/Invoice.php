<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'user_id',
        'invoice_number',
        'subtotal',
        'discount',
        'tax',
        'total',
        'wallet_amount',
        'gateway_amount',
        'payment_method',
        'status',
        'issued_at',
        'metadata',
    ];

    protected $casts = [
        'subtotal' => 'integer',
        'discount' => 'integer',
        'tax' => 'integer',
        'total' => 'integer',
        'wallet_amount' => 'integer',
        'gateway_amount' => 'integer',
        'issued_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}