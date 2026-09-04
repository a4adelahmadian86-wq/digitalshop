<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Http;
class GeminiProvider implements AIProviderInterface
{
 private function setting(string $key,$default=null){return app(AiSettingsStore::class)->get($key,$default);}
 public function available():bool{return (bool)$this->setting('key',config('ai.key'));}
 public function analyze(array $payload):array{
  if(!$this->available())return ['status'=>'unavailable','text'=>''];$model=$payload['model']??$this->modelFor($payload['task']??'');$key=$this->setting('key',config('ai.key'));$timeout=(int)$this->setting('timeout',config('ai.timeout',30));
  $url='https://generativelanguage.googleapis.com/v1beta/models/'.rawurlencode($model).':generateContent';$prompt=$payload['prompt']??json_encode($payload,JSON_UNESCAPED_UNICODE);$system=$payload['system']??'پاسخ دقیق، فارسی، ساختاریافته و کوتاه بده. اگر داده کافی نیست حدس نزن.';
  $response=Http::timeout($timeout)->withQueryParameters(['key'=>$key])->post($url,['systemInstruction'=>['parts'=>[['text'=>$system]]],'contents'=>[['role'=>'user','parts'=>[['text'=>$prompt]]]],'generationConfig'=>['temperature'=>(float)($payload['temperature']??0.2),'maxOutputTokens'=>(int)($payload['max_tokens']??1200)]]);$response->throw();$data=$response->json();$parts=$data['candidates'][0]['content']['parts']??[];$text=collect($parts)->pluck('text')->filter()->implode("\n");return ['status'=>'success','text'=>$text,'raw'=>$data,'input_tokens'=>(int)($data['usageMetadata']['promptTokenCount']??0),'output_tokens'=>(int)($data['usageMetadata']['candidatesTokenCount']??0)];
 }
 private function modelFor(string $task):string{return config('ai.task_models.'.$task,config('ai.model','gemini-2.5-flash'));}
}