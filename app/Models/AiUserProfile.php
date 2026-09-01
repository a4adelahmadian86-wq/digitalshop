<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiUserProfile extends Model
{
    protected $fillable = [
        'user_id', 'interests', 'search_terms', 'recent_products', 'preferences', 'last_seen_at'
    ];

    protected $casts = [
        'interests' => 'array',
        'search_terms' => 'array',
        'recent_products' => 'array',
        'preferences' => 'array',
        'last_seen_at' => 'datetime',
    ];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
