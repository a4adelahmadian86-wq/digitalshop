<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductDraft extends Model
{
    protected $fillable = ['user_id','payload','status'];
    protected $casts = ['payload'=>'array'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}