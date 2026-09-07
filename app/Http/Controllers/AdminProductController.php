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
    public function index()
    {
        $products = Product::with(['category', 'storageProvider'])->latest()->paginate(20);
        $categories = Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $storageProviders = StorageProvider::where('is_active', true)->orderByDesc('is_default')->orderBy('name')->get();
        return view('admin.products.index', compact('products', 'categories', 'storageProviders'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $storageProviders = StorageProvider::where('is_active', true)->orderByDesc('is_default')->orderBy('name')->get();
        return view('admin.products.create', compact('categories', 'storageProviders'));
    }

    public function store(Request $request, StorageManager $storageManager)
    {
        $data = $this->validateData($request);
        $data['description'] = $this->sanitizeProductDescription($data['description'] ?? null);

        $provider = StorageProvider::findOrFail($data['storage_provider_id']);
        abort_unless($provider->is_active, 422, 'Storage Provider انتخاب‌شده فعال نیست.');

        $slug = $data['slug'] ?? Str::slug($data['title']);
        $product = new Product();
        $product->category_id = $data['category_id'];
        $product->storage_provider_id = $provider->id;
        $product->title = $data['title'];
        $product->slug = $slug;
        $product->short_description = $data['short_description'] ?? null;
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->is_published = $request->boolean('is_published');

        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $request->file('thumbnail')->store('products/images', 'local');
        }

        if ($request->hasFile('product_file')) {
            $file = $request->file('product_file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . ($extension ? '.' . $extension : '');
            $storagePath = 'products/files/' . date('Y/m') . '/' . $filename;
            $storedPath = $storageManager->upload($provider, $file, $storagePath);
            $product->storage_path = $storedPath;
            $product->file_path = $storedPath;
            $product->file_name = $file->getClientOriginalName();
        }

        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ایجاد شد.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('sort_order')->orderBy('name')->get();
        $storageProviders = StorageProvider::where('is_active', true)->orderByDesc('is_default')->orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories', 'storageProviders'));
    }

    public function update(Request $request, Product $product, StorageManager $storageManager)
    {
        $data = $this->validateData($request, $product);
        $data['description'] = $this->sanitizeProductDescription($data['description'] ?? null);

        $provider = StorageProvider::findOrFail($data['storage_provider_id']);
        abort_unless($provider->is_active, 422, 'Storage Provider انتخاب‌شده فعال نیست.');

        $product->category_id = $data['category_id'];
        $product->storage_provider_id = $provider->id;
        $product->title = $data['title'];
        $product->slug = $data['slug'];
        $product->short_description = $data['short_description'] ?? null;
        $product->description = $data['description'] ?? null;
        $product->price = $data['price'];
        $product->is_published = $request->boolean('is_published');

        if ($request->hasFile('thumbnail')) {
            $product->thumbnail = $request->file('thumbnail')->store('products/images', 'local');
        }

        if ($request->hasFile('product_file')) {
            if ($product->storage_path && $product->storageProvider) {
                try {
                    $oldProvider = $storageManager->provider($product->storageProvider);
                    $oldProvider->delete($product->storage_path);
                } catch (\Throwable $e) {
                    report($e);
                }
            }
            $file = $request->file('product_file');
            $extension = $file->getClientOriginalExtension();
            $filename = Str::uuid() . ($extension ? '.' . $extension : '');
            $storagePath = 'products/files/' . date('Y/m') . '/' . $filename;
            $storedPath = $storageManager->upload($provider, $file, $storagePath);
            $product->storage_path = $storedPath;
            $product->file_path = $storedPath;
            $product->file_name = $file->getClientOriginalName();
        }

        $product->save();
        return redirect()->route('admin.products.index')->with('success', 'محصول با موفقیت ویرایش شد.');
    }

    public function destroy(Product $product, StorageManager $storageManager)
    {
        if ($product->storage_path && $product->storageProvider) {
            try {
                $provider = $storageManager->provider($product->storageProvider);
                $provider->delete($product->storage_path);
            } catch (\Throwable $e) {
                report($e);
            }
        }
        $product->delete();
        return back()->with('success', 'محصول حذف شد.');
    }

    private function validateData(Request $request, ?Product $product = null): array
    {
        $uniqueSlug = 'unique:products,slug';
        if ($product) $uniqueSlug .= ',' . $product->id;

        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'storage_provider_id' => ['required', 'exists:storage_providers,id'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'regex:/^[a-z0-9-]+$/', $uniqueSlug],
            'short_description' => ['nullable', 'string', 'max:1000'],
            'description' => ['nullable', 'string'],
            'price' => ['required', 'integer', 'min:0'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:5120'],
            'product_file' => [$product ? 'nullable' : 'required', 'file', 'max:512000'],
        ]);
    }

    private function sanitizeProductDescription(?string $html): ?string
    {
        if ($html === null || trim($html) === '') return null;

        $html = preg_replace('/<(script|style|iframe|frame|frameset|object|embed|video|audio|source|canvas|svg|form|input|button|textarea|select|option|img|a|pre|code)\b[^>]*>.*?<\/\1>/is', '', $html);
        $html = preg_replace('/<(img|a|video|audio|source|iframe|object|embed|script|style|svg|canvas|form|input|button|textarea|select|option)\b[^>]*\/?\s*>/i', '', $html);
        $html = preg_replace('/\s(?:style|class|id|onclick|onload|onerror|onmouseover|onfocus|onblur)\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/\b(?:href|src|srcset|action|formaction)\s*=\s*(?:"[^"]*"|\'[^\']*\'|[^\s>]+)/i', '', $html);
        $html = preg_replace('/[\x{1F000}-\x{1FAFF}\x{1FC00}-\x{1FFFF}\x{2600}-\x{27BF}\x{FE0F}\x{1F3FB}-\x{1F3FF}]/u', '', $html);

        $allowed = '<p><br><h2><h3><h4><strong><b><em><i><u><ul><ol><li><blockquote><span>';
        $html = strip_tags($html, $allowed);
        return trim($html);
    }
}
