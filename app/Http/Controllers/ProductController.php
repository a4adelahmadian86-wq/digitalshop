<?php

namespace App\Http\Controllers;

use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductPreview;
use App\Services\AI\CustomerIntentService;
use App\Services\AI\ProductSearchService;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ProductController
{
    public function show(Product $product, CustomerIntentService $intent, ProductSearchService $searchService)
    {
        abort_unless($product->is_published, 404);
        $recentIds = array_values(array_unique(array_map('intval', session('recent_products', []))));
        $recentIds = array_values(array_diff($recentIds, [$product->id]));
        array_unshift($recentIds, $product->id);
        session(['recent_products' => array_slice($recentIds, 0, 10)]);

        $downloadItem = auth()->check()
            ? OrderItem::where('product_id', $product->id)->whereHas('order', fn ($q) => $q->where('user_id', auth()->id())->whereIn('status', ['paid', 'completed']))->first()
            : null;
        $product->load(['category', 'files', 'reviews' => fn ($q) => $q->where('is_published', true)->latest(), 'questions' => fn ($q) => $q->where('is_published', true)->with(['user', 'answers.user'])]);

        $pdfFile = $product->files->first(fn ($file) => strtolower((string) $file->extension) === 'pdf');
        $pdfPreview = $pdfFile ? ProductPreview::where('product_file_id', $pdfFile->id)->where('source_sha256', $pdfFile->sha256)->latest('id')->first() : null;
        $previewExcerpt = null;
        try {
            $previewDocument = $product->knowledgeDocuments()->with('chunks')->first();
            $previewExcerpt = $previewDocument?->chunks?->sortBy('chunk_no')->first()?->content;
            $previewExcerpt = $previewExcerpt ? Str::limit(strip_tags($previewExcerpt), 420) : null;
        } catch (\Throwable) {}

        $related = Product::where('is_published', true)->where('category_id', $product->category_id)->where('id', '<>', $product->id)->latest('id')->take(6)->get();
        $recentList = array_slice($recentIds, 1);
        $recentProducts = Product::whereIn('id', $recentList)->where('is_published', true)->get()->sortBy(fn ($p) => array_search($p->id, $recentList, true))->take(4)->values();
        $recommended = $searchService->recommend(auth()->id(), $product->id, 6)->reject(fn ($p) => $p->id === $product->id)->values();
        $laterIds = array_map('intval', session('later', []));
        $isLater = in_array($product->id, $laterIds, true);
        $intent->record('product_view', null, $product->id);

        return view('product', compact('product', 'downloadItem', 'related', 'recommended', 'recentProducts', 'isLater', 'pdfFile', 'pdfPreview', 'previewExcerpt'));
    }

    public function legacy(Product $product)
    {
        abort_unless($product->is_published, 404);
        return redirect()->route('product.show', $product, 301);
    }

    public function search(Request $request, CustomerIntentService $intent, ProductSearchService $searchService)
    {
        $q = trim((string) $request->input('q', '')); $categoryId = (int) $request->input('category', 0);
        if ($q !== '') { $intent->record('search', $q); $recent = array_values(array_filter(array_diff(session('recent_searches', []), [$q]))); array_unshift($recent, $q); session(['recent_searches' => array_slice($recent, 0, 8)]); }
        $base = Product::where('is_published', true)->when($categoryId > 0, fn ($x) => $x->where('category_id', $categoryId));
        if ($q === '') $products = $base->latest('id')->paginate(12)->withQueryString();
        else { $all = $searchService->search($q, 48); if ($categoryId > 0) $all = $all->filter(fn ($p) => (int) $p->category_id === $categoryId)->values(); $page = max(1, (int) $request->input('page', 1)); $perPage = 12; $slice = $all->slice(($page - 1) * $perPage, $perPage)->values(); $products = new LengthAwarePaginator($slice, $all->count(), $perPage, $page, ['path' => $request->url(), 'query' => $request->query()]); }
        return view('search', compact('products', 'q', 'categoryId'));
    }
}
