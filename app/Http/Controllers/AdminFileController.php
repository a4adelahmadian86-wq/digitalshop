<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;

class AdminFileController extends Controller
{
    public function index(Request $request)
    {
        $query=Product::with(['category','storageProvider'])->where(function($q){$q->whereNotNull('storage_path')->orWhereNotNull('thumbnail');});
        if($request->filled('q')) $query->where(function($q) use($request){$q->where('title','like','%'.$request->q.'%')->orWhere('file_name','like','%'.$request->q.'%');});
        $products=$query->latest()->paginate(24)->withQueryString();
        return view('admin.files.index',compact('products'));
    }

    public function download(Product $product, StorageManager $storageManager)
    {
        abort_unless($product->storage_path && $product->storageProvider,404);
        abort_unless($product->storageProvider->is_active,404);
        return $storageManager->provider($product->storageProvider)->download($product->storage_path,$product->file_name ?: basename($product->storage_path));
    }
}