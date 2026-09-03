<?php
namespace App\Services\AI;
use App\Models\AiProductAnalysis;
use App\Models\Product;
use Illuminate\Support\Str;
class SellerProductReviewService
{
 public function __construct(private ProductKnowledgeService $knowledge,private AIManager $ai,private AiRuntimeLogger $logger){}
 public function inspect(Product $product):array{
  $started=microtime(true);$log=$this->logger->start('seller.product.review',config('ai.provider'),'');
  try{$product->load('files');$this->knowledge->index($product);$evidence=$this->knowledge->evidence($product,$product->title.' '.($product->description??''),10);$payload=['task'=>'seller_product_review','product_id'=>$product->id,'title'=>$product->title,'short_description'=>$product->short_description,'description'=>$product->description,'files'=>$product->files->map(fn($f)=>['name'=>$f->original_name,'extension'=>$f->extension,'size'=>$f->size,'sha256'=>$f->sha256])->values()->all(),'evidence'=>$evidence];$result=$this->ai->inspectProduct($payload);$status=$result['status']??'unavailable';$score=$result['score']??null;$analysis=AiProductAnalysis::create(['product_id'=>$product->id,'status'=>$status,'score'=>$score,'findings'=>$result['findings']??[],'evidence'=>$result['evidence']??$evidence,'source_hash'=>hash('sha256',json_encode($payload,JSON_UNESCAPED_UNICODE)),'inspected_at'=>now()]);$product->update(['ai_status'=>$status,'ai_score'=>$score,'ai_summary'=>$this->summary($result),'ai_report'=>$result,'ai_source_hash'=>$analysis->source_hash,'ai_indexed_at'=>now()]);$this->logger->finish($log,'success',['analysis_id'=>$analysis->id,'evidence_count'=>count($evidence)],(int)((microtime(true)-$started)*1000));return $result+['analysis_id'=>$analysis->id];}catch(\Throwable $e){$this->logger->fail($log,$e,(int)((microtime(true)-$started)*1000));$product->update(['ai_status'=>'failed','ai_report'=>['status'=>'failed','error'=>'AI review failed']]);throw $e;}
 }
 private function summary(array $result):string{$findings=$result['findings']??[];if(!$findings)return 'بررسی محتوای محصول انجام شد.';return Str::limit(collect($findings)->pluck('message')->filter()->implode(' | '),1000);}
}