<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\StorageProvider;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(){
        $products=Product::with(['category','storageProvider'])->latest()->paginate(20);
        $categories=Category::where('is_active',true)->orderBy('sort_order')->orderBy('name')->get();
        $storageProviders=StorageProvider::where('is_active',true)->orderByDesc('is_default')->orderBy('name')->get();
        return view('admin.products.index',compact('products','categories','storageProviders'));
    }
    public function create(){
        $categories=Category::where('is_active',true)->orderBy('sort_order')->orderBy('name')->get();
        $storageProviders=StorageProvider::where('is_active',true)->orderByDesc('is_default')->orderBy('name')->get();
        return view('admin.products.create',compact('categories','storageProviders'));
    }
    public function store(Request $request,StorageManager $storageManager){
        $data=$this->validateData($request);$provider=StorageProvider::findOrFail($data['storage_provider_id']);abort_unless($provider->is_active,422,'Storage Provider انتخاب‌شده فعال نیست.');
        $product=new Product();$product->category_id=$data['category_id'];$product->storage_provider_id=$provider->id;$product->title=$data['title'];$product->slug=$data['slug'];$product->short_description=$data['short_description']??null;$product->description=$data['description']??null;$product->price=$data['price'];$product->is_published=$request->boolean('is_published');
        if($request->hasFile('thumbnail')) $product->thumbnail=$request->file('thumbnail')->store('products/images','local');
        if($request->hasFile('product_file')) $this->storeProductFile($product,$provider,$request->file('product_file'),$storageManager);
        $product->save();return redirect()->route('admin.products.index')->with('success','محصول با موفقیت ایجاد شد.');
    }
    public function edit(Product $product){
        $categories=Category::where('is_active',true)->orderBy('sort_order')->orderBy('name')->get();$storageProviders=StorageProvider::where('is_active',true)->orderByDesc('is_default')->orderBy('name')->get();return view('admin.products.edit',compact('product','categories','storageProviders'));
    }
    public function update(Request $request,Product $product,StorageManager $storageManager){
        $data=$this->validateData($request,$product);$provider=StorageProvider::findOrFail($data['storage_provider_id']);abort_unless($provider->is_active,422,'Storage Provider انتخاب‌شده فعال نیست.');
        $product->category_id=$data['category_id'];$product->storage_provider_id=$provider->id;$product->title=$data['title'];$product->slug=$data['slug'];$product->short_description=$data['short_description']??null;$product->description=$data['description']??null;$product->price=$data['price'];$product->is_published=$request->boolean('is_published');
        if($request->hasFile('thumbnail')) $product->thumbnail=$request->file('thumbnail')->store('products/images','local');
        if($request->hasFile('product_file')){if($product->storage_path&&$product->storageProvider){try{$storageManager->provider($product->storageProvider)->delete($product->storage_path);}catch(\Throwable $e){report($e);}}$this->storeProductFile($product,$provider,$request->file('product_file'),$storageManager);}
        $product->save();return redirect()->route('admin.products.index')->with('success','محصول با موفقیت ویرایش شد.');
    }
    protected function storeProductFile(Product $product,StorageProvider $provider,$file,StorageManager $storageManager):void{
        $extension=$file->getClientOriginalExtension();$filename=Str::uuid().($extension?'.'.$extension:'');$storagePath='products/files/'.date('Y/m').'/'.$filename;$stored=$storageManager->upload($provider,$file,$storagePath);$product->storage_path=$stored;$product->file_path=$stored;$product->file_name=$file->getClientOriginalName();
    }
    public function toggle(Product $product){$product->update(['is_published'=>!$product->is_published]);return back()->with('success',$product->is_published?'محصول منتشر شد.':'محصول از انتشار خارج شد.');}
    public function destroy(Product $product,StorageManager $storageManager){if($product->storage_path&&$product->storageProvider){try{$storageManager->provider($product->storageProvider)->delete($product->storage_path);}catch(\Throwable $e){report($e);}}$product->delete();return back()->with('success','محصول حذف شد.');}
    private function validateData(Request $request,?Product $product=null):array{
        $unique='unique:products,slug'.($product?','.$product->id:'');
        return $request->validate(['category_id'=>['required','exists:categories,id'],'storage_provider_id'=>['required','exists:storage_providers,id'],'title'=>['required','string','max:255'],'slug'=>['required','string','max:255','regex:/^[a-z0-9-]+$/',$unique],'short_description'=>['nullable','string','max:1000'],'description'=>['nullable','string'],'price'=>['required','integer','min:0'],'thumbnail'=>['nullable','image','mimes:jpg,jpeg,png,webp','max:5120'],'product_file'=>[$product?'nullable':'required','file','max:512000']]);
    }
}