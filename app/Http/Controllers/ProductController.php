<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function show(Product $product)
    {
        abort_unless($product->is_published, 404);

        $recent = session('recent_products', []);
        $recent = array_diff($recent, [$product->id]);

        array_unshift($recent, $product->id);

        session([
            'recent_products' => array_slice($recent, 0, 8)
        ]);

        $downloadItem = null;

        if (auth()->check()) {
            $downloadItem = OrderItem::where(
                'product_id',
                $product->id
            )->whereHas('order', function ($q) {
                $q->where('user_id', auth()->id())
                  ->where('status', 'paid');
            })->first();
        }

        return view('product', [
            'product' => $product,
            'downloadItem' => $downloadItem
        ]);
    }

    public function search(Request $request)
    {
        $q = trim($request->q ?? '');

        $products = Product::where('is_published', 1)
            ->when($q, fn($query) =>
                $query->where(function ($q2) use ($q) {
                    $q2->where('title', 'like', "%{$q}%")
                       ->orWhere(
                           'short_description',
                           'like',
                           "%{$q}%"
                       );
                })
            )
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('search', compact('products', 'q'));
    }
}