<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;use Illuminate\Database\Eloquent\Relations\BelongsTo;
class ReaderSubscription extends Model{protected $fillable=['user_id','plan','price','starts_at','ends_at','status','metadata'];protected $casts=['starts_at'=>'datetime','ends_at'=>'datetime','metadata'=>'array'];public function user():BelongsTo{return $this->belongsTo(User::class);}public function isActive():bool{return $this->status==='active'&&$this->ends_at?->isFuture();}}