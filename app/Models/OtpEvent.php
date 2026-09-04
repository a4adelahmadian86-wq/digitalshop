<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class OtpEvent extends Model{protected $fillable=['phone','event','channel','ip'];}