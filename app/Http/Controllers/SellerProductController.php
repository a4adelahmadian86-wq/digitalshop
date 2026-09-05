<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductFile;
use App\Models\ProductUpload;
use App\Models\StorageProvider;
use App\Services\AI\ProductKnowledgeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SellerProductController extends Controller
{
    public function index(){ $products=Product::where('submitted_by',auth()->id())->with('files')->latest()->paginate(20);return view('account.seller.products.index',compact('products')); }
    public function create(){ $categories=Category::where('is_active',true)->where('status',true)->orderBy('level')->orderBy('sort_order')->orderBy('name')->get();return view('account.seller.products.create',compact('categories')); }
    public function store(Request $request,ProductKnowledgeService $knowledge){
        $data=$request->validate(['title'=>['required','string','max:255'],'category_id'=>['required','exists:categories,id'],'price'=>['required','integer','min:0'],'short_description'=>['nullable','string','max:300'],'description'=>['nullable','string','max:100000'],'seo_keywords'=>['nullable','string','max:5000'],'meta_title'=>['nullable','string','max:255'],'meta_description'=>['nullable','string','max:320'],'file_format'=>['nullable','string','max:20','regex:/^\.?[A-Za-z0-9]{1,12}$/'],'format_confirmed'=>['nullable','boolean'],'page_count'=>['nullable','integer','min:0','max:1000000'],'upload_ids'=>['required','array','min:1','max:30'],'upload_ids.*'=>['integer']]);
        $uploads=ProductUpload::where('user_id',auth()->id())->where('status','uploaded')->whereIn('id',$data['upload_ids'])->get();if($uploads->count()!==count(array_unique(array_map('intval',$data['upload_ids']))))return back()->withErrors(['upload_ids'=>'فایل انتخاب‌شده معتبر نیست.'])->withInput();
        $format=trim((string)($data['file_format']??''));$format=$format?'.'.ltrim(strtolower($format),'.'):'.'.strtolower((string)$uploads->first()->extension);if(empty($data['file_format'])&&!$request->boolean('format_confirmed'))return back()->withErrors(['file_format'=>'فرمت تشخیص‌داده‌شده را تأیید کنید.'])->withInput();
        $provider=StorageProvider::where('is_active',true)->where('is_default',true)->first()?:StorageProvider::where('is_active',true)->first();abort_unless($provider,422,'ذخیره‌سازی فعال برای فایل‌ها تنظیم نشده است.');
        $baseSlug=Str::slug(Str::transliterate($data['title']))?:'digital-file-'.Str::lower(Str::random(8));
        $product=DB::transaction(function()use($data,$uploads,$provider,$format,$baseSlug){$p=Product::create(['category_id'=>$data['category_id'],'storage_provider_id'=>$provider->id,'title'=>$data['title'],'slug'=>'pending-'.Str::uuid(),'short_description'=>$data['short_description']??null,'description'=>$this->cleanHtml($data['description']??''),'seo_keywords'=>$data['seo_keywords']??null,'meta_title'=>$data['meta_title']??$data['title'],'meta_description'=>$data['meta_description']??Str::limit(strip_tags($data['short_description']??$data['description']??''),320,''),'file_format'=>$format,'page_count'=>isset($data['page_count'])&&$data['page_count']!==''?(int)$data['page_count']:null,'price'=>$data['price'],'is_published'=>false,'approval_status'=>'pending','submitted_by'=>auth()->id(),'submitted_at'=>now()]);$p->update(['slug'=>$p->id.'-'.$baseSlug]);foreach($uploads->values() as $i=>$upload){ProductFile::create(['product_id'=>$p->id,'storage_provider_id'=>$upload->storage_provider_id,'original_name'=>$upload->original_name,'stored_name'=>basename($upload->stored_path),'storage_path'=>$upload->stored_path,'mime_type'=>$upload->mime_type,'extension'=>$upload->extension,'size'=>$upload->size,'sha256'=>$upload->sha256,'sort_order'=>$i]);$upload->update(['status'=>'attached','product_id'=>$p->id]);}return $p;});
        try{$knowledge->index($product->fresh('files'));}catch(\Throwable $e){report($e);}return redirect()->route('seller.products.index')->with('success','محصول برای بررسی ارسال شد.');
    }
    private function cleanHtml(string $html):string{$html=strip_tags($html,'<p><br><strong><b><em><i><u><h2><h3><h4><ul><ol><li><a><blockquote><div><span>');$html=preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/iu','',$html)??$html;return trim(preg_replace('/javascript\s*:/iu','',$html)??$html);}
}
