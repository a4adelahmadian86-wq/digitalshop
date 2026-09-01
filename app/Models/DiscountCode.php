<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    protected $fillable = [
        'code',
        'amount',
        'is_percent',
        'max_uses',
        'used_count',
        'is_active',
        'starts_at',
        'expires_at',
    ];

    protected $casts = [
        'is_percent' => 'boolean',
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    public function valid()
    {
        $now = now();

        return $this->is_active
            && (!$this->starts_at || $this->starts_at <= $now)
            && (!$this->expires_at || $this->expires_at >= $now)
            && (!$this->max_uses || $this->used_count < $this->max_uses);
    }
}