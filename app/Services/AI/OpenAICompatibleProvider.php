<?php
namespace App\Services\AI;
use Illuminate\Support\Facades\Http;
class OpenAICompatibleProvider implements AIProviderInterface
{
 public function available():bool{return (bool)config('ai.endpoint')&&(bool)config('ai.key')&&(bool)config('ai.model');}
 public function analyze(array $payload):array{
  if(!$this->available())return ['status'=>'unavailable','score'=>null,'findings'=>[['severity'=>'info','code'=>'ai_not_configured','message'=>'Provider هوش مصنوعی پیکربندی نشده است.']], 'evidence'=>$payload['evidence']??[]];
  $model=$payload['model']??config('ai.model');$system='You are the product-review AI for a Persian digital-file marketplace. Evidence is authoritative. Never invent claims. If evidence is missing, say so. Return strict JSON with status, score, findings, evidence, summary.';$response=Http::withToken(config('ai.key'))->acceptJson()->timeout((int)config('ai.timeout',60))->post(config('ai.endpoint'),['model'=>$model,'temperature'=>0,'messages'=>[['role'=>'system','content'=>$system],['role'=>'user','content'=>json_encode($payload,JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES)]]]);$response->throw();$data=$response->json();$content=data_get($data,'choices.0.message.content','');$content=preg_replace('/^```(?:json)?|```$/m','',trim($content));$parsed=json_decode($content,true);if(!is_array($parsed))$parsed=['status'=>'success','score'=>null,'findings'=>[['severity'=>'warning','code'=>'invalid_model_json','message'=>'مدل پاسخ ساختاریافته معتبر برنگرداند؛ ادعایی ساخته نشد.']],'evidence'=>$payload['evidence']??[]];$usage=$data['usage']??[];$parsed['input_tokens']=(int)($usage['prompt_tokens']??0);$parsed['output_tokens']=(int)($usage['completion_tokens']??0);$parsed['model']=$model;$parsed['provider']='openai-compatible';return $parsed;
 }
}