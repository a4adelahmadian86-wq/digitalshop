<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiFeedback extends Model
{
    protected $fillable = ['user_id','product_id','session_id','message_id','rating','type','comment','context','resolved_at'];
    protected $casts = ['context' => 'array', 'resolved_at' => 'datetime'];

    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
}
