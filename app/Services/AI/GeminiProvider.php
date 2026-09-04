<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Http;
class GeminiProvider implements AIProviderInterface
{
 public function available():bool{return (bool)config('ai.key');}
 public function analyze(array $payload):array{
  if(!$this->available())return ['status'=>'unavailable','text'=>''];
  $model=$payload['model']??$this->modelFor($payload['task']??'');
  $url='https://generativelanguage.googleapis.com/v1beta/models/'.rawurlencode($model).':generateContent';
  $prompt=$payload['prompt']??json_encode($payload,JSON_UNESCAPED_UNICODE);
  $system=$payload['system']??'پاسخ دقیق، فارسی، ساختاریافته و کوتاه بده. اگر داده کافی نیست حدس نزن.';
  $response=Http::timeout((int)config('ai.timeout',30))->withQueryParameters(['key'=>config('ai.key')])->post($url,['systemInstruction'=>['parts'=>[['text'=>$system]]],'contents'=>[['role'=>'user','parts'=>[['text'=>$prompt]]]],'generationConfig'=>['temperature'=>(float)($payload['temperature']??0.2),'maxOutputTokens'=>(int)($payload['max_tokens']??1200)]]);
  $response->throw();$data=$response->json();$parts=$data['candidates'][0]['content']['parts']??[];$text=collect($parts)->pluck('text')->filter()->implode("\n");return ['status'=>'success','text'=>$text,'raw'=>$data,'input_tokens'=>(int)($data['usageMetadata']['promptTokenCount']??0),'output_tokens'=>(int)($data['usageMetadata']['candidatesTokenCount']??0)];
 }
 private function modelFor(string $task):string{return match($task){ 'shopping_assistant','product_review','product_description','structured_json','file_analysis','evaluation'=>'gemini-2.5-flash', 'product_recommendation','customer_behavior','support_ai'=>'gemini-2.5-flash-lite', default=>config('ai.model','gemini-2.5-flash')};}
}