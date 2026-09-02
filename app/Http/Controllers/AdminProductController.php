<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDraft;
use App\Models\ProductFile;
use App\Models\ProductUpload;
use App\Models\StorageProvider;
use App\Services\AI\ProductKnowledgeService;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(){ $products=Product::with(['category','files'])->latest()->paginate(20); $categories=Category::where('is_active',true)->orderBy('sort_order')->orderBy('name')->get(); return view('admin.products.index',compact('products','categories')); }
    public function create(){ $categories=Category::where('is_active',true)->orderBy('sort_order')->orderBy('name')->get(); $draft=ProductDraft::where('user_id',auth()->id())->latest('id')->first(); return view('admin.products.create-v2',compact('categories','draft')); }
    public function store(Request $request,ProductKnowledgeService $knowledge){$data=$this->validateData($request,null);$ids=$data['upload_ids']??[];if(!$ids){$draft=ProductDraft::where('user_id',auth()->id())->latest('id')->first();$ids=(array)data_get($draft?->payload,'upload_ids',[]);} $uploads=$this->ownedUploads($ids);if($uploads->isEmpty())return back()->withErrors(['upload_ids'=>'حداقل یک فایل محصول باید آپلود شود.'])->withInput();$provider=$this->resolveProvider();$slug=$data['slug']?:Str::slug($data['title']);if($slug==='')$slug='product-'.Str::lower(Str::random(10));$product=DB::transaction(function()use($data,$uploads,$provider,$slug){$product=Product::create(['category_id'=>$data['category_id'],'storage_provider_id'=>$provider->id,'title'=>$data['title'],'slug'=>$slug,'short_description'=>$data['short_description']??null,'description'=>$data['description']??null,'price'=>$data['price'],'is_published'=>true,'approval_status'=>'approved','approved_by'=>auth()->id(),'approved_at'=>now(),'submitted_by'=>auth()->id()]);$this->attachUploads($product,$uploads);return $product->fresh('files');});$knowledge->index($product);ProductDraft::where('user_id',auth()->id())->delete();return redirect()->route('admin.products.index')->with('success','محصول مدیر اصلی مستقیم تأیید و منتشر شد.');}
    public function edit(Product $product){$categories=Category::where('is_active',true)->orderBy('sort_order')->orderBy('name')->get();$product->load('files');return view('admin.products.edit',compact('product','categories'));}
    public function update(Request $request,Product $product,ProductKnowledgeService $knowledge){$data=$this->validateData($request,$product);$provider=$product->storageProvider?:$this->resolveProvider();DB::transaction(function()use($data,$product,$provider){$slug=$data['slug']?:Str::slug($data['title']);if($slug==='')$slug='product-'.Str::lower(Str::random(10));$product->update(['category_id'=>$data['category_id'],'storage_provider_id'=>$provider->id,'title'=>$data['title'],'slug'=>$slug,'short_description'=>$data['short_description']??null,'description'=>$data['description']??null,'price'=>$data['price'],'approval_status'=>'approved','approved_by'=>auth()->id(),'approved_at'=>now()]);if(!empty($data['upload_ids']))$this->attachUploads($product,$this->ownedUploads($data['upload_ids']));});$knowledge->index($product->fresh('files'));return redirect()->route('admin.products.index')->with('success','محصول بروزرسانی شد.');}
    public function toggle(Product $product){if($product->approval_status!=='approved')return back()->withErrors(['product'=>'این محصول هنوز تأیید نشده است.']);$product->update(['is_published'=>!$product->is_published]);return back()->with('success',$product->is_published?'محصول منتشر شد.':'محصول از انتشار خارج شد.');}
    public function destroy(Product $product,StorageManager $storageManager){foreach($product->files as $file){try{$storageManager->provider($file->storageProvider)->delete($file->storage_path);}catch(\Throwable $e){report($e);}}$product->delete();return back()->with('success','محصول حذف شد.');}
    private function resolveProvider():StorageProvider{$provider=StorageProvider::where('is_active',true)->where('is_default',true)->first()?:StorageProvider::where('is_active',true)->orderBy('id')->first();abort_unless($provider,422,'هیچ Storage Provider فعالی تنظیم نشده است. آن را از بخش ذخیره‌سازی تنظیم کنید.');return $provider;}
    private function attachUploads(Product $product,$uploads):void{$start=(int)$product->files()->max('sort_order')+1;foreach($uploads->values() as $i=>$upload){ProductFile::create(['product_id'=>$product->id,'storage_provider_id'=>$upload->storage_provider_id,'original_name'=>$upload->original_name,'stored_name'=>basename($upload->stored_path),'storage_path'=>$upload->stored_path,'mime_type'=>$upload->mime_type,'extension'=>$upload->extension,'size'=>$upload->size,'sha256'=>$upload->sha256,'sort_order'=>$start+$i]);$upload->update(['status'=>'attached','product_id'=>$product->id]);}}
    private function ownedUploads(array $ids){$ids=array_values(array_unique(array_filter(array_map('intval',$ids))));return ProductUpload::where('user_id',auth()->id())->where('status','uploaded')->whereIn('id',$ids)->orderByRaw('FIELD(id,'.implode(',',$ids?:[0]).')')->get();}
    private function validateData(Request $request,?Product $product):array{$unique='unique:products,slug'.($product?','.$product->id:'');return $request->validate(['category_id'=>['required','exists:categories,id'],'title'=>['required','string','max:255'],'slug'=>['nullable','string','max:255','regex:/^[a-z0-9-]+$/',$unique],'short_description'=>['nullable','string','max:1000'],'description'=>['nullable','string','max:100000'],'price'=>['required','integer','min:0'],'upload_ids'=>['nullable','array','max:30'],'upload_ids.*'=>['integer']]);}
}
