<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserAiMemory extends Model
{
    protected $fillable=['user_id','category','memory_key','memory_value','importance','source','persistent','last_confirmed_at','expires_at'];
    protected $casts=['persistent'=>'boolean','last_confirmed_at'=>'datetime','expires_at'=>'datetime'];
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
}
