<?php

namespace App\Http\Controllers;

use App\Models\AiUserEvent;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPreview;
use App\Services\AI\CustomerIntentService;
use App\Services\AI\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;

class ProductController
{
    public function show(Product $product, CustomerIntentService $intent, ProductSearchService $searchService)
    {
        abort_unless($product->is_published,404);
        $recentIds=array_values(array_unique(array_map('intval',session('recent_products',[]))));$recentIds=array_values(array_diff($recentIds,[$product->id]));array_unshift($recentIds,$product->id);session(['recent_products'=>array_slice($recentIds,0,12)]);
        $downloadItem=auth()->check()?OrderItem::where('product_id',$product->id)->whereHas('order',fn($q)=>$q->where('user_id',auth()->id())->whereIn('status',['paid','completed']))->first():null;
        $product->load(['category','files','reviews'=>fn($q)=>$q->where('is_published',true)->latest(),'questions'=>fn($q)=>$q->where('is_published',true)->with(['user','answers.user'])]);
        $pdfFile=$product->files->first(fn($file)=>strtolower((string)$file->extension)==='pdf');$pdfPreview=$pdfFile?ProductPreview::where('product_file_id',$pdfFile->id)->where('source_sha256',$pdfFile->sha256)->latest('id')->first():null;
        $previewExcerpt=$pdfPreview?->excerpt;
        if(!$previewExcerpt){$previewExcerpt=Cache::rememberForever('product_preview_excerpt_'.$product->id.'_'.sha1((string)($pdfFile?->sha256??$product->updated_at)),fn()=> $this->selectPreviewExcerpt($product));}
        $semanticQuery=trim((string)$product->title);
        $semanticRelated=$semanticQuery!==''?$searchService->search($semanticQuery,20):collect();
        $related=$semanticRelated->reject(fn($p)=>(int)$p->id===(int)$product->id)->map(function($p)use($product){$score=(int)($p->smart_score??0);if((int)$p->category_id===(int)$product->category_id)$score+=35;$p->similarity_score=$score;return $p;})->sortByDesc('similarity_score')->take(8)->values();
        if($related->count()<8){$fallback=Product::where('is_published',true)->where('id','<>',$product->id)->where('category_id',$product->category_id)->with('category')->latest('id')->take(12)->get();$related=$related->concat($fallback)->unique('id')->take(8)->values();}
        $recommended=$searchService->recommend(auth()->id(),$product->id,8)->reject(fn($p)=>$p->id===$product->id)->values();$recentList=array_slice($recentIds,1);$recentProducts=Product::with('files')->whereIn('id',$recentList)->where('is_published',true)->get()->sortBy(fn($p)=>array_search($p->id,$recentList,true))->take(6)->values();
        $togetherOrderIds=OrderItem::where('product_id',$product->id)->limit(150)->pluck('order_id');$purchasedTogether=$togetherOrderIds->isNotEmpty()?Product::where('is_published',true)->where('products.id','<>',$product->id)->whereIn('id',OrderItem::whereIn('order_id',$togetherOrderIds)->where('product_id','<>',$product->id)->select('product_id')->distinct())->withCount(['orderItems as together_count'=>fn($q)=>$q->whereIn('order_id',$togetherOrderIds)])->orderByDesc('together_count')->take(6)->get():collect();
        $coViewIds=AiUserEvent::query()->where('event','product_view')->where('product_id',$product->id)->whereNotNull('session_id')->latest('id')->limit(100)->pluck('session_id');$viewedTogether=$coViewIds->isNotEmpty()?Product::where('is_published',true)->where('id','<>',$product->id)->whereIn('id',AiUserEvent::whereIn('session_id',$coViewIds)->where('event','product_view')->where('product_id','<>',$product->id)->select('product_id')->distinct())->take(6)->get():collect();
        $laterIds=array_map('intval',session('later',[]));$isLater=in_array($product->id,$laterIds,true);$reviewCount=$product->reviews->count();$reviewAverage=$reviewCount?round((float)$product->reviews->avg('rating'),1):0;$reviewStars=(int)round($reviewAverage);$reviewSummary=$reviewCount?'از میان '.number_format($reviewCount).' نظر ثبت‌شده، میانگین امتیاز این محصول '.number_format($reviewAverage,1).' از ۵ است.':'هنوز نظر کافی برای ساخت خلاصه تجربه خریداران ثبت نشده است.';
        $specs=[['label'=>'فرمت','value'=>$product->file_format?:strtoupper($pdfFile?->extension??'فایل دیجیتال')],['label'=>'تعداد صفحات','value'=>$product->page_count?number_format($product->page_count):'اعلام نشده'],['label'=>'تعداد فایل','value'=>number_format($product->files->count())],['label'=>'حجم','value'=>$this->formatSize((int)($product->files->sum('size')??0))]];
        $intent->record('product_view',null,$product->id);$blocks=collect();$pool=[['key'=>'purchased','title'=>'کاربرانی که این را خریدند، این‌ها را هم خریدند','items'=>$purchasedTogether],['key'=>'viewed','title'=>'گاهی اوقات در کنار این محصول دیده شده','items'=>$viewedTogether],['key'=>'recent','title'=>'تازه دیده‌شده','items'=>$recentProducts],['key'=>'smart','title'=>'پیشنهاد هوشمند برای شما','items'=>$recommended]];$offset=(int)(($product->id+now()->day)%count($pool));for($i=0;$i<count($pool);$i++){ $candidate=$pool[($i+$offset)%count($pool)];if($candidate['items']->isNotEmpty()){$blocks->push($candidate);if($blocks->count()>=3)break;}}
        return view('product',compact('product','downloadItem','related','recommended','recentProducts','isLater','pdfFile','pdfPreview','previewExcerpt','specs','reviewCount','reviewAverage','reviewStars','reviewSummary','blocks'));
    }

    public function legacy(Product $product){abort_unless($product->is_published,404);return redirect()->route('product.show',$product,301);}
    public function search(Request $request,CustomerIntentService $intent,ProductSearchService $searchService){$q=trim((string)$request->input('q',''));$categoryId=(int)$request->input('category',0);if($q!==''){$intent->record('search',$q);$recent=array_values(array_filter(array_diff(session('recent_searches',[]),[$q])));array_unshift($recent,$q);session(['recent_searches'=>array_slice($recent,0,8)]);}$base=Product::where('is_published',true)->when($categoryId>0,fn($x)=>$x->where('category_id',$categoryId));if($q==='')$products=$base->latest('id')->paginate(12)->withQueryString();else{$all=$searchService->search($q,48);if($categoryId>0)$all=$all->filter(fn($p)=>(int)$p->category_id===$categoryId)->values();$page=max(1,(int)$request->input('page',1));$perPage=12;$slice=$all->slice(($page-1)*$perPage,$perPage)->values();$products=new LengthAwarePaginator($slice,$all->count(),$perPage,$page,['path'=>$request->url(),'query'=>$request->query()]);}return view('search',compact('products','q','categoryId'));}
    private function selectPreviewExcerpt(Product $product):?string{try{$documents=$product->knowledgeDocuments()->with('chunks')->get();$paragraphs=[];foreach($documents as $document)foreach($document->chunks->sortBy('chunk_no') as $chunk){$text=trim(preg_replace('/\s+/u',' ',strip_tags((string)$chunk->content)));foreach(preg_split('/(?<=[.!?؟])\s+/u',$text)?:[] as $sentence){$sentence=trim($sentence);if(mb_strlen($sentence)>=80)$paragraphs[]=$sentence;}}if(!$paragraphs)return $product->short_description?Str::limit(trim(strip_tags($product->short_description)),420):null;$keywords=preg_split('/\s+/u',mb_strtolower((string)$product->title),-1,PREG_SPLIT_NO_EMPTY)?:[];$best=null;$bestScore=-1;foreach($paragraphs as $paragraph){$score=min(25,(int)(mb_strlen($paragraph)/35));$lower=mb_strtolower($paragraph);foreach($keywords as $word)if(mb_strlen($word)>=3&&str_contains($lower,$word))$score+=12;if(preg_match('/(نتیجه|مناسب|کاربرد|مزیت|شامل|هدف|راهکار|نکته|مهم|پیشنهاد)/u',$paragraph))$score+=10;if(mb_strlen($paragraph)>=120&&mb_strlen($paragraph)<=480)$score+=8;if($score>$bestScore){$bestScore=$score;$best=$paragraph;}}return $best?Str::limit($best,420):null;}catch(\Throwable){return null;}}
    private function formatSize(int $bytes):string{if($bytes<=0)return 'اعلام نشده';$units=['بایت','کیلوبایت','مگابایت','گیگابایت'];$i=0;$size=(float)$bytes;while($size>=1024&&$i<count($units)-1){$size/=1024;$i++;}return number_format($size,$i?1:0).' '.$units[$i];}
}
