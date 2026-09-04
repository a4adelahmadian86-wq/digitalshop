<?php
namespace App\Services\AI;
class ResilientAIProvider implements AIProviderInterface
{
 public function __construct(private AIProviderInterface $primary,private AIProviderInterface $fallback){}
 public function available():bool{return $this->primary->available()||$this->fallback->available();}
 public function analyze(array $payload):array{
  if($this->primary->available()){try{return $this->primary->analyze($payload);}catch(\Throwable $e){if(!$this->fallback->available())throw $e;}}
  if($this->fallback->available())return $this->fallback->analyze($payload);
  return ['status'=>'unavailable','text'=>''];
 }
}