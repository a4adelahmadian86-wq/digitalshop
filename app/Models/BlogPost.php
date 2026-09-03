<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class BlogPost extends Model { protected $fillable=['slug','title','excerpt','content','author_id','is_published','published_at']; protected $casts=['is_published'=>'boolean','published_at'=>'datetime']; public function author(){return $this->belongsTo(User::class,'author_id');} }
