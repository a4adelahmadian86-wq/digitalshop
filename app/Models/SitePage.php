<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SitePage extends Model { protected $fillable=['slug','title','meta_title','meta_description','content','is_published']; protected $casts=['is_published'=>'boolean']; }
