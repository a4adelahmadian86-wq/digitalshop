<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDraft extends Model
{
    protected $fillable = ['user_id', 'product_id', 'payload', 'last_saved_at'];

    protected $casts = ['payload' => 'array', 'last_saved_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
