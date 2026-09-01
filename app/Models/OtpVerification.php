<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpVerification extends Model
{
    protected $fillable = [
        'phone',
        'purpose',
        'code_hash',
        'expires_at',
        'verified_at',
        'attempts',
        'last_sent_at',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'verified_at' => 'datetime',
        'last_sent_at' => 'datetime',
    ];

    public function expired(): bool
    {
        return now()->greaterThan(
            $this->expires_at
        );
    }

    public function verified(): bool
    {
        return $this->verified_at !== null;
    }
}