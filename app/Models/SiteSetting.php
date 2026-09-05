<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class SiteSetting extends Model
{
    protected $fillable=['key','value'];
    public static function getValue(string $key,$default=null){$value=Cache::remember('site_setting:'.$key,300,fn()=>static::where('key',$key)->value('value'));return $value??$default;}
    public static function putValue(string $key,$value):void{static::updateOrCreate(['key'=>$key],['value'=>$value]);Cache::forget('site_setting:'.$key);}
}
