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
    private const THUMBNAILS = 'Images/pdf.png,Images/word.png,Images/excel.png,Images/powerpoint.png,Images/html.png,Images/css.png,Images/JavaScript.png,Images/Python.png,Images/php.png,Images/SQL.png,Images/JSON.png,Images/APK.png,Images/svg.png,Images/WordPress.png';

    public function index(Request $request)
    {
        $q = trim((string) $request->get('q'));
        $products = Product::with(['category','files'])
            ->when($q !== '', fn($x) => $x->where(fn($w) => $w->where('id', is_numeric($q) ? (int) $q : -1)->orWhere('title','like','%'.$q.'%')->orWhere('slug','like','%'.$q.'%')))
            ->latest()->paginate(20)->withQueryString();
        $categories = Category::where('is_active',true)->where('status',true)->orderBy('sort_order')->orderBy('name')->get();
        return view('admin.products.index', compact('products','categories','q'));
    }

    public function create()
    {
        $categories = Category::where('is_active',true)->where('status',true)->orderBy('level')->orderBy('sort_order')->orderBy('name')->get();
        $draft = ProductDraft::where('user_id',auth()->id())->latest('id')->first();
        return view('admin.products.create-v3', compact('categories','draft'));
    }

    public function store(Request $request, ProductKnowledgeService $knowledge)
    {
        $data = $this->validateData($request, null);
        $ids = $data['upload_ids'] ?? [];
        if (!$ids) {
            $draft = ProductDraft::where('user_id',auth()->id())->latest('id')->first();
            $ids = (array) data_get($draft?->payload,'upload_ids',[]);
        }
        $uploads = $this->ownedUploads($ids);
        if ($uploads->isEmpty()) return back()->withErrors(['upload_ids'=>'حداقل یک فایل محصول باید آپلود شود.'])->withInput();

        $detected = $this->detectFormat($uploads->first());
        $format = $this->normalizeFormat($data['file_format'] ?? '') ?: $detected;
        if (!$format) return back()->withErrors(['file_format'=>'فرمت فایل اصلی قابل تشخیص نیست؛ آن را وارد کنید.'])->withInput();
        if (!$request->boolean('format_confirmed') && empty($data['file_format'])) return back()->withErrors(['file_format'=>'فرمت تشخیص‌داده‌شده را تأیید کنید.'])->withInput();

        $provider = $this->resolveProvider();
        $baseSlug = $this->makeSlug($data['title']);
        $product = DB::transaction(function () use ($data,$uploads,$provider,$format,$baseSlug) {
            $product = Product::create([
                'category_id'=>$data['category_id'], 'storage_provider_id'=>$provider->id, 'title'=>$data['title'],
                'slug'=>'pending-'.Str::uuid(), 'thumbnail'=>$data['thumbnail'], 'short_description'=>$data['short_description']??null,
                'description'=>$this->cleanHtml($data['description']??''), 'seo_keywords'=>$data['seo_keywords']??null,
                'meta_title'=>$data['meta_title']??$data['title'], 'meta_description'=>$data['meta_description']??Str::limit(strip_tags($data['short_description']??$data['description']??''),320,''),
                'file_format'=>$format, 'page_count'=>isset($data['page_count'])&&$data['page_count']!==''?(int)$data['page_count']:null,
                'price'=>$data['price'], 'is_published'=>true, 'approval_status'=>'approved', 'approved_by'=>auth()->id(),
                'approved_at'=>now(), 'submitted_by'=>auth()->id(), 'submitted_at'=>now(),
            ]);
            $product->update(['slug'=>$product->id.'-'.$baseSlug]);
            $this->attachUploads($product,$uploads);
            return $product->fresh('files');
        });
        $knowledge->index($product);
        ProductDraft::where('user_id',auth()->id())->delete();
        return redirect()->route('admin.products.index')->with('success','محصول #'.$product->id.' ایجاد شد و مستقیم منتشر شد.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active',true)->where('status',true)->orderBy('level')->orderBy('sort_order')->orderBy('name')->get();
        $storageProviders = StorageProvider::where('is_active',true)->orderByDesc('is_default')->orderBy('name')->get();
        $product->load('files');
        return view('admin.products.edit',compact('product','categories','storageProviders'));
    }

    public function update(Request $request, Product $product, ProductKnowledgeService $knowledge)
    {
        $data = $this->validateData($request,$product);
        $provider = $product->storageProvider ?: $this->resolveProvider();
        DB::transaction(function () use ($data,$product,$provider) {
            $changes = [
                'category_id'=>$data['category_id'], 'storage_provider_id'=>$provider->id, 'title'=>$data['title'],
                'short_description'=>$data['short_description']??null, 'description'=>$this->cleanHtml($data['description']??''),
                'seo_keywords'=>$data['seo_keywords']??null, 'meta_title'=>$data['meta_title']??$data['title'],
                'meta_description'=>$data['meta_description']??Str::limit(strip_tags($data['short_description']??$data['description']??''),320,''),
                'file_format'=>$this->normalizeFormat($data['file_format']??$product->file_format),
                'page_count'=>isset($data['page_count'])&&$data['page_count']!==''?(int)$data['page_count']:null,
                'price'=>$data['price'], 'approval_status'=>'approved', 'approved_by'=>auth()->id(), 'approved_at'=>now(),
            ];
            if (!empty($data['thumbnail'])) $changes['thumbnail']=$data['thumbnail'];
            // The URL slug is intentionally immutable after creation.
            $product->update($changes);
            if (!empty($data['upload_ids'])) $this->attachUploads($product,$this->ownedUploads($data['upload_ids']));
        });
        $knowledge->index($product->fresh('files'));
        return redirect()->route('admin.products.index')->with('success','محصول #'.$product->id.' بروزرسانی شد.');
    }

    public function toggle(Product $product)
    {
        if ($product->approval_status !== 'approved') return back()->withErrors(['product'=>'این محصول هنوز تأیید نشده است.']);
        $product->update(['is_published'=>!$product->is_published]);
        return back()->with('success',$product->is_published?'محصول منتشر شد.':'محصول از انتشار خارج شد.');
    }

    public function destroy(Product $product, StorageManager $storageManager)
    {
        foreach ($product->files as $file) { try { $storageManager->provider($file->storageProvider)->delete($file->storage_path); } catch (\Throwable $e) { report($e); } }
        $product->delete();
        return back()->with('success','محصول حذف شد.');
    }

    private function resolveProvider(): StorageProvider
    {
        $provider = StorageProvider::where('is_active',true)->where('is_default',true)->first() ?: StorageProvider::where('is_active',true)->orderBy('id')->first();
        abort_unless($provider,422,'هیچ Storage Provider فعالی تنظیم نشده است. آن را از بخش ذخیره‌سازی تنظیم کنید.');
        return $provider;
    }

    private function attachUploads(Product $product, $uploads): void
    {
        $start=(int)$product->files()->max('sort_order')+1;
        foreach($uploads->values() as $i=>$upload){
            ProductFile::create(['product_id'=>$product->id,'storage_provider_id'=>$upload->storage_provider_id,'original_name'=>$upload->original_name,'stored_name'=>basename($upload->stored_path),'storage_path'=>$upload->stored_path,'mime_type'=>$upload->mime_type,'extension'=>$upload->extension,'size'=>$upload->size,'sha256'=>$upload->sha256,'sort_order'=>$start+$i]);
            $upload->update(['status'=>'attached','product_id'=>$product->id]);
        }
    }

    private function ownedUploads(array $ids)
    {
        $ids=array_values(array_unique(array_filter(array_map('intval',$ids))));
        return ProductUpload::where('user_id',auth()->id())->where('status','uploaded')->whereIn('id',$ids)->orderByRaw('FIELD(id,'.implode(',',$ids?:[0]).')')->get();
    }

    private function validateData(Request $request, ?Product $product): array
    {
        $thumbRules=$product?['nullable','string','in:'.self::THUMBNAILS]:['required','string','in:'.self::THUMBNAILS];
        return $request->validate([
            'category_id'=>['required','exists:categories,id'], 'title'=>['required','string','max:255'], 'thumbnail'=>$thumbRules,
            'short_description'=>['nullable','string','max:300'], 'description'=>['nullable','string','max:100000'],
            'seo_keywords'=>['nullable','string','max:5000'], 'meta_title'=>['nullable','string','max:255'], 'meta_description'=>['nullable','string','max:320'],
            'file_format'=>['nullable','string','max:20','regex:/^\.?[A-Za-z0-9]{1,12}$/'], 'format_confirmed'=>['nullable','boolean'],
            'page_count'=>['nullable','integer','min:0','max:1000000'], 'price'=>['required','integer','min:0'], 'upload_ids'=>['nullable','array','max:30'], 'upload_ids.*'=>['integer'],
        ]);
    }

    private function normalizeFormat(?string $format): ?string
    {
        $format=trim((string)$format); if($format==='') return null; $format=ltrim($format,'.'); return '.'.strtolower($format);
    }

    private function detectFormat(?ProductUpload $upload): ?string
    {
        if(!$upload) return null; $ext=strtolower((string)$upload->extension); return $ext?'.'.$ext:null;
    }

    private function makeSlug(string $title): string
    {
        $slug=Str::slug(Str::transliterate($title));
        return $slug ?: 'digital-file-'.Str::lower(Str::random(8));
    }

    private function cleanHtml(string $html): string
    {
        $html=strip_tags($html,'<p><br><strong><b><em><i><u><h2><h3><h4><ul><ol><li><a><blockquote><div><span>');
        $html=preg_replace('/\s+on[a-z]+\s*=\s*("[^"]*"|\'[^\']*\'|[^\s>]+)/iu','',$html)??$html;
        $html=preg_replace('/javascript\s*:/iu','',$html)??$html;
        return trim($html);
    }
}
