<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Services\Storage\StorageManager;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductApiController extends Controller
{
    public function store(Request $request, StorageManager $storage)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', 'unique:products,slug'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'price' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'file' => ['nullable', 'file', 'max:512000'],
            'file_name' => ['nullable', 'string', 'max:255'],
            'storage_provider_id' => ['nullable', 'integer', 'exists:storage_providers,id'],
        ]);

        $product = new Product();
        $product->category_id = $data['category_id'];
        $product->title = $data['title'];
        $product->slug = $data['slug'] ?? Str::slug($data['title']).'-'.Str::lower(Str::random(6));
        $product->price = $data['price'];
        $product->short_description = $data['short_description'] ?? null;
        $product->description = $data['description'] ?? null;
        $product->is_published = false;

        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $request->file('thumbnail')->store('products/images', 'local');
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $extension = $file->getClientOriginalExtension();
            $path = 'products/files/'.date('Y/m').'/'.Str::uuid().($extension ? '.'.$extension : '');
            $result = $storage->uploadTo('files', $file, $path, 'external', $data['storage_provider_id'] ?? null);
            $product->storage_provider_id = $result['provider']->id;
            $product->storage_path = $result['path'];
            $product->file_path = $result['path'];
            $product->file_name = $data['file_name'] ?? $file->getClientOriginalName();
        } elseif (! empty($data['storage_provider_id'])) {
            $provider = $storage->select('files', 'external', (int) $data['storage_provider_id']);
            if ($provider) {
                $product->storage_provider_id = $provider->id;
            }
        }

        $product->save();

        return response()->json(['ok' => true, 'product' => $product->fresh()], 201);
    }
}
