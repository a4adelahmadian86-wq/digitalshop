<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->thumbnail, 404);

        $disk = Storage::disk('local');
        $path = $product->thumbnail;

        abort_unless($disk->exists($path), 404);

        return response()->file($disk->path($path), [
            'Content-Type' => $disk->mimeType($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
