<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Http;
class OpenAICompatibleProvider implements AIProviderInterface
{
 public function available():bool{return (bool)config('ai.key')&&(bool)config('ai.endpoint');}
 public function analyze(array $payload):array{
  if(!$this->available())return ['status'=>'unavailable','text'=>''];
  $response=Http::timeout((int)config('ai.timeout',30))->withToken(config('ai.key'))->post(rtrim(config('ai.endpoint'),'/').'/chat/completions',['model'=>$payload['model']??config('ai.model'),'messages'=>[['role'=>'system','content'=>$payload['system']??'به فارسی دقیق و کوتاه پاسخ بده.'],['role'=>'user','content'=>$payload['prompt']??json_encode($payload,JSON_UNESCAPED_UNICODE)]],'temperature'=>(float)($payload['temperature']??0.2),'max_tokens'=>(int)($payload['max_tokens']??1200)]);
  $response->throw();$data=$response->json();$text=$data['choices'][0]['message']['content']??'';return ['status'=>'success','text'=>$text,'raw'=>$data,'input_tokens'=>(int)($data['usage']['prompt_tokens']??0),'output_tokens'=>(int)($data['usage']['completion_tokens']??0)];
 }
}