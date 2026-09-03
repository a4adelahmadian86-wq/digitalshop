<?php
namespace App\Http\Controllers;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductDraft;
use App\Models\ProductFile;
use App\Models\ProductUpload;
use App\Models\StorageProvider;
use App\Services\AI\SellerProductReviewService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
class SellerProductController extends Controller
{
 public function index(Request $request){$products=Product::with('category')->where('submitted_by',$request->user()->id)->latest()->paginate(15);return view('account.seller.products.index',compact('products'));}
 public function create(){ $categories=Category::where('is_active',1)->orderBy('sort_order')->orderBy('name')->get();$draft=ProductDraft::where('user_id',auth()->id())->latest('id')->first();return view('account.seller.products.create',compact('categories','draft'));}
 public function store(Request $request,SellerProductReviewService $review){$data=$request->validate(['category_id'=>'required|exists:categories,id','title'=>'required|string|max:255','slug'=>'nullable|string|max:255|regex:/^[a-z0-9-]+$/','short_description'=>'nullable|string|max:1000','description'=>'nullable|string|max:100000','price'=>'required|integer|min:0','upload_ids'=>'required|array|min:1|max:30','upload_ids.*'=>'integer']);$ids=array_values(array_unique(array_map('intval',$data['upload_ids'])));$uploads=ProductUpload::where('user_id',auth()->id())->where('status','uploaded')->whereIn('id',$ids)->get();abort_if($uploads->isEmpty(),422,'حداقل یک فایل معتبر لازم است.');$provider=StorageProvider::where('is_active',1)->where('is_default',1)->first()?:StorageProvider::where('is_active',1)->orderBy('id')->first();abort_unless($provider,422,'ذخیره‌سازی فعال تنظیم نشده است.');$slug=$data['slug']?:Str::slug($data['title']);if($slug==='')$slug='product-'.Str::lower(Str::random(10));$product=DB::transaction(function()use($data,$uploads,$provider,$slug){$p=Product::create(['category_id'=>$data['category_id'],'storage_provider_id'=>$provider->id,'title'=>$data['title'],'slug'=>$slug,'short_description'=>$data['short_description']??null,'description'=>$data['description']??null,'price'=>$data['price'],'is_published'=>false,'approval_status'=>'pending','submitted_by'=>auth()->id(),'submitted_at'=>now()]);foreach($uploads as $i=>$upload){ProductFile::create(['product_id'=>$p->id,'storage_provider_id'=>$upload->storage_provider_id,'original_name'=>$upload->original_name,'stored_name'=>basename($upload->stored_path),'storage_path'=>$upload->stored_path,'mime_type'=>$upload->mime_type,'extension'=>$upload->extension,'size'=>$upload->size,'sha256'=>$upload->sha256,'sort_order'=>$i]);$upload->update(['status'=>'attached','product_id'=>$p->id]);}return $p->fresh('files');});$result=$review->inspect($product);if(($result['status']??'')==='blocked'){return redirect()->route('seller.products.index')->with('error','محصول برای بررسی مدیر متوقف شد.');}return redirect()->route('seller.products.index')->with('success','محصول ارسال شد؛ بررسی محتوا انجام شد و اکنون در انتظار تأیید مدیر است.');}
}