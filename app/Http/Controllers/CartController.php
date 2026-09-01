<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Order;
use App\Models\DiscountCode;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);
        $later = session('later', []);

        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_published', 1)->get();

        $laterProducts = Product::whereIn('id', $later)
            ->where('is_published', 1)->get();

        $subtotal = $products->sum('price');

        $discount = $this->discount($subtotal);

        $total = max(0, $subtotal - $discount);

        return view('cart', compact(
            'cart',
            'products',
            'later',
            'laterProducts',
            'subtotal',
            'discount',
            'total'
        ));
    }

    public function add(Product $product)
    {
        abort_unless($product->is_published, 404);

        if (auth()->check() && $this->bought($product)) {
            return back()->with(
                'cart_error',
                'این فایل را قبلاً خریداری کرده‌اید.'
            );
        }

        $cart = session('cart', []);

        $cart[$product->id] = 1;

        session(['cart' => $cart]);

        return back()->with('cart_added', true);
    }

    public function remove(Product $product)
    {
        $cart = session('cart', []);

        unset($cart[$product->id]);

        session(['cart' => $cart]);

        return back();
    }

    public function later(Product $product)
    {
        $cart = session('cart', []);
        $later = session('later', []);

        unset($cart[$product->id]);

        if (!in_array($product->id, $later)) {
            $later[] = $product->id;
        }

        session([
            'cart' => $cart,
            'later' => $later
        ]);

        return back();
    }

    public function moveToCart(Product $product)
    {
        $later = session('later', []);
        $cart = session('cart', []);

        if (auth()->check() && $this->bought($product)) {
            return back()->with(
                'cart_error',
                'این فایل را قبلاً خریداری کرده‌اید.'
            );
        }

        $cart[$product->id] = 1;

        $later = array_diff($later, [$product->id]);

        session([
            'cart' => $cart,
            'later' => array_values($later)
        ]);

        return back();
    }

    public function applyDiscount(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50'
        ]);

        $code = DiscountCode::where(
            'code',
            strtoupper(trim($request->code))
        )->first();

        if (!$code || !$code->is_active) {
            return back()->with(
                'discount_error',
                'کد تخفیف معتبر نیست.'
            );
        }

        if ($code->starts_at && now()->lt($code->starts_at)) {
            return back()->with(
                'discount_error',
                'این کد هنوز فعال نشده است.'
            );
        }

        if ($code->expires_at && now()->gt($code->expires_at)) {
            return back()->with(
                'discount_error',
                'مهلت استفاده از این کد به پایان رسیده است.'
            );
        }

        if (
            $code->max_uses !== null &&
            $code->used_count >= $code->max_uses
        ) {
            return back()->with(
                'discount_error',
                'ظرفیت استفاده از این کد تکمیل شده است.'
            );
        }

        session([
            'discount_code' => $code->code
        ]);

        return back()->with(
            'discount_success',
            'کد تخفیف اعمال شد.'
        );
    }

    public function removeDiscount()
    {
        session()->forget('discount_code');

        return back()->with(
            'discount_success',
            'کد تخفیف حذف شد.'
        );
    }

    private function discount($subtotal)
    {
        $code = session('discount_code');

        if (!$code) {
            return 0;
        }

        $discount = DiscountCode::where('code', $code)
            ->where('is_active', 1)
            ->first();

        if (!$discount) {
            session()->forget('discount_code');
            return 0;
        }

        if ($discount->is_percent) {
            return min(
                $subtotal,
                $subtotal * ($discount->amount / 100)
            );
        }

        return min($subtotal, $discount->amount);
    }

    private function bought(Product $product)
    {
        return Order::where('user_id', auth()->id())
            ->whereIn('status', ['paid', 'completed'])
            ->whereHas(
                'items',
                fn($q) => $q->where(
                    'product_id',
                    $product->id
                )
            )->exists();
    }
}