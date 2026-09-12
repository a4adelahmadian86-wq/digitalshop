<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ProductReaderController extends Controller
{
    public function preview(Request $request, Product $product)
    {
        $previewAvailable = false;
        $previewUrl = null;
        $error = null;

        try {
            if (!empty($product->storage_path)) {
                $previewAvailable = true;
                $previewUrl = route('product.preview', $product);
            }
        } catch (\Throwable $e) {
            Log::warning('Product preview bootstrap failed', [
                'product_id' => $product->id,
                'message' => $e->getMessage(),
            ]);
            $error = 'پیش‌نمایش فعلاً در دسترس نیست.';
        }

        return view('product.preview', [
            'product' => $product,
            'previewAvailable' => $previewAvailable,
            'previewUrl' => $previewUrl,
            'error' => $error,
        ]);
    }
}
