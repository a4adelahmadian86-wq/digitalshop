<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Http;
class GapGptProvider implements AIProviderInterface
{
 public function available():bool{return (bool)config('ai.fallback_key')&&(bool)config('ai.fallback_endpoint');}
 public function analyze(array $payload):array{
  if(!$this->available())return ['status'=>'unavailable','text'=>''];
  $model=$payload['model']??config('ai.fallback_model','gpt-4o-mini');
  $response=Http::timeout((int)config('ai.timeout',30))->withToken(config('ai.fallback_key'))->post(rtrim(config('ai.fallback_endpoint'),'/').'/chat/completions',['model'=>$model,'messages'=>[['role'=>'system','content'=>$payload['system']??'به فارسی دقیق و کوتاه پاسخ بده.'],['role'=>'user','content'=>$payload['prompt']??json_encode($payload,JSON_UNESCAPED_UNICODE)]],'temperature'=>(float)($payload['temperature']??0.2),'max_tokens'=>(int)($payload['max_tokens']??1200)]);
  $response->throw();$data=$response->json();return ['status'=>'success','text'=>$data['choices'][0]['message']['content']??'','raw'=>$data,'input_tokens'=>(int)($data['usage']['prompt_tokens']??0),'output_tokens'=>(int)($data['usage']['completion_tokens']??0)];
 }
}