<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class SiteSetting extends Model
{
 protected $fillable=['key','value'];
 public static function getValue(string $key,$default=null){return static::where('key',$key)->value('value')??$default;}
 public static function putValue(string $key,$value):void{static::updateOrCreate(['key'=>$key],['value'=>$value]);}
}