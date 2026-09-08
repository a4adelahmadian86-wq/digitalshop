<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ProductImageController extends Controller
{
    public function show(Product $product)
    {
        $thumbnail = trim((string) $product->thumbnail);
        abort_if($thumbnail === '', 404);

        if (filter_var($thumbnail, FILTER_VALIDATE_URL)) {
            return redirect()->away($thumbnail);
        }

        $path = ltrim(str_replace('\\', '/', $thumbnail), '/');
        if (str_starts_with($path, 'storage/')) $path = substr($path, 8);
        if (str_starts_with($path, 'public/')) $path = substr($path, 7);

        $disk = Storage::disk('local');
        abort_unless($disk->exists($path), 404);

        return response()->file($disk->path($path), [
            'Content-Type' => $disk->mimeType($path) ?: 'image/jpeg',
            'Cache-Control' => 'public, max-age=86400',
        ]);
    }
}
