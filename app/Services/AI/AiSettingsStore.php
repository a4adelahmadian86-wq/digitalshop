<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Crypt;use Illuminate\Support\Facades\DB;use Illuminate\Support\Facades\Schema;
class AiSettingsStore
{
 public function get(string $key,$default=null){if(!Schema::hasTable('ai_settings'))return config('ai.'.$key,$default);$row=DB::table('ai_settings')->where('key',$key)->first();if(!$row)return config('ai.'.$key,$default);return $row->encrypted&&$row->value?Crypt::decryptString($row->value):$row->value;}
 public function put(string $key,?string $value,bool $encrypted=false):void{if(!Schema::hasTable('ai_settings'))return;DB::table('ai_settings')->updateOrInsert(['key'=>$key],['value'=>$value===null?null:($encrypted?Crypt::encryptString($value):$value),'encrypted'=>$encrypted,'updated_at'=>now(),'created_at'=>now()]);}
}