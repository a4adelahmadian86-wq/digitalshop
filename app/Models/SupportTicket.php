<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SupportTicket extends Model { protected $fillable=['user_id','subject','category','status','priority','related_type','related_id','ai_handled','human_requested','human_requested_at']; protected $casts=['ai_handled'=>'boolean','human_requested'=>'boolean','human_requested_at'=>'datetime']; public function user(){return $this->belongsTo(User::class);} public function messages(){return $this->hasMany(SupportMessage::class,'ticket_id');} public function related(){return $this->morphTo(__FUNCTION__,'related_type','related_id');} }
