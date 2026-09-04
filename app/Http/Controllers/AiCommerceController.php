<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\AI\AIManager;
use App\Services\AI\CustomerIntentService;
use App\Services\AI\ProductSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class AiCommerceController extends Controller
{
    public function chat(Request $request, CustomerIntentService $intent, ProductSearchService $search, AIManager $ai): JsonResponse
    {
        $message=trim((string)$request->input('message'));abort_if($message==='',422,'پیام خالی است.');$intent->record('assistant_message',$message);
        $products=$search->search($message,5);
        $items=$products->map(fn(Product $product)=>['id'=>$product->id,'title'=>$product->title,'slug'=>$product->slug,'url'=>route('product.show',$product),'price'=>$product->price,'score'=>$product->smart_score??0,'evidence'=>array_map(fn($row)=>['file'=>$row['file'],'snippet'=>$row['snippet'],'source_hash'=>$row['source_hash']],$product->evidence??[])])->values();
        $withEvidence=$items->filter(fn($item)=>count($item['evidence'])>0)->values();
        if($withEvidence->isEmpty())return response()->json(['ok'=>true,'available'=>$ai->isAvailable(),'message'=>$items->isEmpty()?'برای این درخواست محصول مناسبی در محتوای واقعی فروشگاه پیدا نکردم.':'محصولات مشابه پیدا شدند، اما برای پاسخ محتوایی شواهد کافی از فایل‌ها در پایگاه دانش ندارم.','products'=>$items,'evidence_based'=>true]);
        $top=$withEvidence->take(3);
        $cacheKey='ai:shopping:'.($request->user()?->id??0).':'.sha1(mb_strtolower($message));
        $result=Cache::remember($cacheKey,now()->addSeconds(45),function()use($ai,$message,$top){$prompt="کاربر این درخواست را مطرح کرده است:\n{$message}\n\nفقط بر اساس گزینه‌ها و شواهد زیر پاسخ بده. اگر شواهد کافی نیست صریح بگو. محصول ساختگی، قیمت ساختگی یا ادعای خارج از شواهد نساز. پاسخ فارسی، کوتاه و کاربردی باشد و در پایان حداکثر سه محصول مناسب را نام ببر.\n\n";foreach($top as $item){$prompt.="محصول: {$item['title']} | قیمت: ".number_format((float)$item['price'])." تومان\nشاهد: ".($item['evidence'][0]['snippet']??'')."\n\n";}return $ai->inspectProduct(['task'=>'shopping_assistant','product_id'=>null,'system'=>'تو دستیار خرید فروشگاه فایل هستی. فقط از داده‌های ارائه‌شده استفاده کن و هیچ واقعیتی را حدس نزن.','prompt'=>$prompt,'temperature'=>0.15,'max_tokens'=>650]);});
        $fallback='بر اساس شواهد موجود، این گزینه‌ها بیشترین تطابق را دارند:';if(empty($result['text']))foreach($top as $item)$fallback.="\n• {$item['title']}";
        return response()->json(['ok'=>true,'available'=>$ai->isAvailable(),'message'=>$result['text']??$fallback,'products'=>$items,'evidence_based'=>true]);
    }

    public function product(Product $product, CustomerIntentService $intent, ProductSearchService $search): JsonResponse
    {
        abort_unless($product->is_published,404);$intent->record('product_view',null,$product->id);$recommendations=$search->recommend(auth()->id(),$product->id,6);
        return response()->json(['ok'=>true,'product'=>['id'=>$product->id,'title'=>$product->title,'summary'=>$product->ai_summary,'ai_status'=>$product->ai_status,'ai_score'=>$product->ai_score],'recommendations'=>$recommendations->map(fn(Product $item)=>['id'=>$item->id,'title'=>$item->title,'url'=>route('product.show',$item),'score'=>$item->recommendation_score??0])->values()]);
    }
}
