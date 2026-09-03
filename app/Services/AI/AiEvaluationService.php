<?php
namespace App\Services\AI;
use App\Models\AiModelExperiment;
use App\Models\Product;
class AiEvaluationService
{
 public function __construct(private AIManager $ai,private ProductKnowledgeService $knowledge){}
 public function run(?Product $product=null):array{
  $models=config('ai.evaluation_models',[]);if(!$models)return ['status'=>'no_models','results'=>[]];$product=$product?:Product::with('files')->where('is_published',1)->latest()->first();if(!$product)return ['status'=>'no_product','results'=>[]];$product->load('files');$this->knowledge->index($product);$evidence=$this->knowledge->evidence($product,$product->title.' '.($product->description??''),10);$payload=['task'=>'evaluation','product_id'=>$product->id,'title'=>$product->title,'description'=>$product->description,'evidence'=>$evidence];$results=[];foreach($models as $model){$experiment=AiModelExperiment::firstOrCreate(['name'=>'eval-'.$model,'provider'=>config('ai.provider','openai-compatible'),'model'=>$model,'task'=>'product_review'],['enabled'=>true]);$experiment->update(['started_at'=>now(),'enabled'=>true]);$started=microtime(true);try{$out=$this->ai->inspectProduct($payload+['model'=>$model]);$lat=(int)((microtime(true)-$started)*1000);$metrics=['latency_ms'=>$lat,'score'=>$out['score']??null,'findings'=>count($out['findings']??[]),'status'=>$out['status']??null];$experiment->update(['metrics'=>$metrics,'ended_at'=>now()]);$results[]=['model'=>$model,'metrics'=>$metrics];}catch(\Throwable $e){$experiment->update(['metrics'=>['error'=>$e->getMessage()],'ended_at'=>now()]);$results[]=['model'=>$model,'metrics'=>['status'=>'failed']];}}return ['status'=>'completed','product_id'=>$product->id,'results'=>$results];
 }
}