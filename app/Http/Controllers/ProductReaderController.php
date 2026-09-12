<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductReaderController extends Controller
{
    public function preview(Request $request, Product $product)
    {
        $previewAvailable = !empty($product->storage_path);
        $error = null;

        try {
            // Do not hard-resolve StorageManager at bootstrap.
        } catch (\Throwable $e) {
            Log::warning('preview bootstrap', ['id' => $product->id, 'msg' => $e->getMessage()]);
            $error = 'پیش‌نمایش موقتاً در دسترس نیست.';
        }

        return view('product.preview', compact('product', 'previewAvailable', 'error'));
    }
}
