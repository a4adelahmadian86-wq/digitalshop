<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiUserEvent extends Model
{
    protected $fillable = [
        'user_id', 'product_id', 'session_id', 'event', 'query', 'metadata'
    ];

    protected $casts = ['metadata' => 'array'];
}
