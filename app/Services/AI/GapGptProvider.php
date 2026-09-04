<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Http;
class GapGptProvider implements AIProviderInterface
{
 private function setting(string $key,$default=null){return app(AiSettingsStore::class)->get($key,$default);}
 public function available():bool{return (bool)$this->setting('fallback_key',config('ai.fallback_key'))&&(bool)$this->setting('fallback_endpoint',config('ai.fallback_endpoint'));}
 public function analyze(array $payload):array{
  if(!$this->available())return ['status'=>'unavailable','text'=>''];$model=$payload['model']??$this->setting('fallback_model',config('ai.fallback_model','gpt-4o-mini'));$key=$this->setting('fallback_key',config('ai.fallback_key'));$endpoint=$this->setting('fallback_endpoint',config('ai.fallback_endpoint'));$response=Http::timeout((int)$this->setting('timeout',config('ai.timeout',30)))->withToken($key)->post(rtrim($endpoint,'/').'/chat/completions',['model'=>$model,'messages'=>[['role'=>'system','content'=>$payload['system']??'به فارسی دقیق و کوتاه پاسخ بده.'],['role'=>'user','content'=>$payload['prompt']??json_encode($payload,JSON_UNESCAPED_UNICODE)]],'temperature'=>(float)($payload['temperature']??0.2),'max_tokens'=>(int)($payload['max_tokens']??1200)]);$response->throw();$data=$response->json();return ['status'=>'success','text'=>$data['choices'][0]['message']['content']??'','raw'=>$data,'input_tokens'=>(int)($data['usage']['prompt_tokens']??0),'output_tokens'=>(int)($data['usage']['completion_tokens']??0)];
 }
}