<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Services\AI\CustomerIntentService;
use App\Services\AI\ProductSearchService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index(ProductSearchService $searchService)
    {
        $cart = session('cart', []);
        $later = array_map('intval', session('later', []));
        $ids = array_values(array_unique(array_map('intval', array_keys($cart))));
        $products = Product::with('files')->whereIn('id', $ids ?: [0])->where('is_published', true)->get()->sortBy(fn ($p) => array_search($p->id, $ids, true))->values();
        $laterProducts = Product::with('files')->whereIn('id', $later ?: [0])->where('is_published', true)->get()->sortBy(fn ($p) => array_search($p->id, $later, true))->values();
        $subtotal = (float) $products->sum('price');
        $discount = $this->discount($subtotal);
        $total = max(0, $subtotal - $discount);
        $recommendations = $searchService->recommendForCart(auth()->id(), session()->getId(), $ids, 4);
        $recentIds = array_values(array_unique(array_map('intval', session('recent_products', []))));
        $recentProducts = Product::whereIn('id', $recentIds ?: [0])->where('is_published', true)->get()->sortBy(fn ($p) => array_search($p->id, $recentIds, true))->take(4)->values();
        return view('cart', compact('cart', 'products', 'later', 'laterProducts', 'subtotal', 'discount', 'total', 'recommendations', 'recentProducts'));
    }

    public function add(Product $product, CustomerIntentService $intent)
    {
        abort_unless($product->is_published, 404);
        if (auth()->check() && $this->bought($product)) return back()->with('cart_error', 'این فایل را قبلاً خریداری کرده‌اید.');
        $cart = session('cart', []);
        $cart[$product->id] = 1;
        session(['cart' => $cart]);
        $intent->record('cart_add', null, $product->id);
        return back()->with('cart_added', true);
    }

    public function remove(Product $product, CustomerIntentService $intent)
    {
        $cart = session('cart', []); unset($cart[$product->id]); session(['cart' => $cart]);
        $intent->record('cart_remove', null, $product->id);
        return back();
    }

    public function later(Product $product, CustomerIntentService $intent)
    {
        $cart = session('cart', []); $later = array_map('intval', session('later', [])); unset($cart[$product->id]);
        if (!in_array($product->id, $later, true)) $later[] = $product->id;
        session(['cart' => $cart, 'later' => $later]);
        $intent->record('cart_later', null, $product->id);
        return back();
    }

    public function moveToCart(Product $product, CustomerIntentService $intent)
    {
        $later = array_map('intval', session('later', [])); $cart = session('cart', []);
        if (auth()->check() && $this->bought($product)) return back()->with('cart_error', 'این فایل را قبلاً خریداری کرده‌اید.');
        $cart[$product->id] = 1; $later = array_values(array_diff($later, [$product->id]));
        session(['cart' => $cart, 'later' => $later]);
        $intent->record('cart_move_to_cart', null, $product->id);
        return back();
    }

    public function applyDiscount(Request $request)
    {
        $data = $request->validate(['code' => 'required|string|max:50']);
        $code = DiscountCode::where('code', strtoupper(trim($data['code'])))->first();
        if (!$code || !$code->valid()) return back()->with('discount_error', 'کد تخفیف معتبر نیست یا اعتبار آن به پایان رسیده است.');
        session(['discount_code' => $code->code]);
        return back()->with('discount_success', 'کد تخفیف اعمال شد.');
    }

    public function removeDiscount()
    {
        session()->forget('discount_code');
        return back()->with('discount_success', 'کد تخفیف حذف شد.');
    }

    private function discount(float $subtotal): float
    {
        $code = session('discount_code'); if (!$code) return 0;
        $discount = DiscountCode::where('code', $code)->first();
        if (!$discount || !$discount->valid()) { session()->forget('discount_code'); return 0; }
        return $discount->is_percent ? min($subtotal, $subtotal * ((float) $discount->amount / 100)) : min($subtotal, (float) $discount->amount);
    }

    private function bought(Product $product): bool
    {
        return Order::where('user_id', auth()->id())->whereIn('status', ['paid', 'completed'])->whereHas('items', fn ($q) => $q->where('product_id', $product->id))->exists();
    }
}
