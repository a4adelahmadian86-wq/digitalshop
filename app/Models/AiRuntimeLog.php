<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiRuntimeLog extends Model
{
    protected $fillable=['user_id','session_id','operation','provider','model','status','latency_ms','input_tokens','output_tokens','cost','request_meta','response_meta','error_message'];
    protected $casts=['request_meta'=>'array','response_meta'=>'array','cost'=>'decimal:6'];
    public function user(): BelongsTo{return $this->belongsTo(User::class);}
}
