<?php
namespace App\Services\AI;
class AIManager
{
 public function __construct(private AIProviderInterface $provider,private AiRuntimeLogger $logger){}
 public function inspectProduct(array $payload):array{
  $started=microtime(true);$task=$payload['task']??'ai.inspect';$payload['model']=$payload['model']??config('ai.task_models.'.$task,config('ai.model'));
  $log=$this->logger->start($task,config('ai.provider'),$payload['model'],['product_id'=>$payload['product_id']??null]);
  try{$result=$this->provider->analyze($payload);$this->logger->finish($log,'success',['provider_available'=>$this->provider->available(),'status'=>$result['status']??null],(int)((microtime(true)-$started)*1000),(int)($result['input_tokens']??0),(int)($result['output_tokens']??0),isset($result['cost'])?(float)$result['cost']:null);return $result;}catch(\Throwable $e){$this->logger->fail($log,$e,(int)((microtime(true)-$started)*1000));throw $e;}
 }
 public function isAvailable():bool{return $this->provider->available();}
}