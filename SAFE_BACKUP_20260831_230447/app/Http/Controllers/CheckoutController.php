<?php

namespace App\Http\Controllers;

use App\Models\DiscountCode;
use App\Models\Order;
use App\Models\Product;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function index()
    {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart')
                ->with('error', 'سبد خرید شما خالی است.');
        }

        $products = Product::whereIn('id', array_keys($cart))
            ->where('is_published', true)
            ->get();

        if ($products->isEmpty()) {
            session()->forget('cart');

            return redirect()
                ->route('cart')
                ->with('error', 'محصولات سبد خرید دیگر در دسترس نیستند.');
        }

        $validProducts = collect();

        foreach ($products as $product) {
            if (!$this->bought($product)) {
                $validProducts->push($product);
            }
        }

        if ($validProducts->isEmpty()) {
            session()->forget('cart');

            return redirect()
                ->route('cart')
                ->with(
                    'error',
                    'تمام محصولات این سبد قبلاً خریداری شده‌اند.'
                );
        }

        $products = $validProducts;

        $cart = collect($cart)
            ->filter(
                fn ($quantity, $productId) =>
                    $products->contains('id', (int) $productId)
            )
            ->mapWithKeys(
                fn ($quantity, $productId) =>
                    [(int) $productId => 1]
            )
            ->all();

        session(['cart' => $cart]);

        $subtotal = (float) $products->sum('price');

        $discountCode = $this->getValidDiscount();

        $discount = $discountCode
            ? $this->calculateDiscount(
                $subtotal,
                $discountCode
            )
            : 0;

        $total = max(0, $subtotal - $discount);

        return view('checkout', [
            'products' => $products,
            'cart' => $cart,
            'subtotal' => $subtotal,
            'discount' => $discount,
            'total' => $total,
            'payable' => $total,
            'discountCode' => $discountCode?->code,
        ]);
    }

    public function applyDiscount(Request $request)
    {
        $data = $request->validate([
            'code' => 'required|string|max:50',
        ]);

        $code = strtoupper(trim($data['code']));

        $discount = DiscountCode::where('code', $code)->first();

        if (!$discount) {
            return back()->with(
                'discount_error',
                'کد تخفیف پیدا نشد.'
            );
        }

        if (!$discount->valid()) {
            return back()->with(
                'discount_error',
                'این کد تخفیف معتبر نیست یا اعتبار آن به پایان رسیده است.'
            );
        }

        session([
            'discount_code' => $discount->code,
        ]);

        return back()->with(
            'discount_success',
            'کد تخفیف با موفقیت اعمال شد.'
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

    public function store(
        Request $request,
        WalletService $walletService
    ) {
        $cart = session('cart', []);

        if (empty($cart)) {
            return redirect()
                ->route('cart')
                ->with(
                    'error',
                    'سبد خرید خالی است.'
                );
        }

        $order = DB::transaction(function () use (
            $cart,
            $walletService
        ) {

            $products = Product::whereIn(
                'id',
                array_keys($cart)
            )
            ->where('is_published', true)
            ->lockForUpdate()
            ->get();

            if ($products->isEmpty()) {
                abort(
                    redirect()
                        ->route('cart')
                        ->with(
                            'error',
                            'محصولات سبد خرید در دسترس نیستند.'
                        )
                );
            }

            foreach ($products as $product) {

                if ($this->bought($product)) {
                    abort(
                        redirect()
                            ->route('cart')
                            ->with(
                                'error',
                                'یکی از محصولات قبلاً خریداری شده است.'
                            )
                    );
                }
            }

            $subtotal =
                (float) $products->sum('price');

            $discountCode =
                $this->getValidDiscount(true);

            $discount =
                $discountCode
                    ? $this->calculateDiscount(
                        $subtotal,
                        $discountCode
                    )
                    : 0;

            $total =
                max(
                    0,
                    $subtotal - $discount
                );

            /*
             * اگر سفارش قبلاً پرداخت شده باشد
             * هرگز دوباره پرداخت نمی‌شود.
             */

            $existingOrder = Order::where(
                'user_id',
                auth()->id()
            )
            ->where('status', 'paid')
            ->whereHas(
                'items',
                function ($query) use ($products) {
                    $query->whereIn(
                        'product_id',
                        $products->pluck('id')
                    );
                }
            )
            ->first();

            if ($existingOrder) {
                abort(
                    redirect()
                        ->route(
                            'checkout.success',
                            $existingOrder
                        )
                        ->with(
                            'success',
                            'این محصولات قبلاً خریداری شده‌اند.'
                        )
                );
            }

            $wallet =
                $walletService->wallet(
                    auth()->user()
                );

            $useWallet =
                $wallet->balance >= $total;

            /*
             * اگر Wallet کل مبلغ را پوشش دهد،
             * سفارش مستقیماً paid می‌شود.
             */

            $order = Order::create([
                'user_id' =>
                    auth()->id(),

                'order_number' =>
                    $this->orderNumber(),

                'status' =>
                    $total <= 0 || $useWallet
                        ? 'paid'
                        : 'pending',

                'subtotal' =>
                    $subtotal,

                'discount' =>
                    $discount,

                'total' =>
                    $total,

                'paid_at' =>
                    $total <= 0 || $useWallet
                        ? now()
                        : null,
            ]);

            foreach ($products as $product) {

                $order->items()->create([
                    'product_id' =>
                        $product->id,

                    'price' =>
                        $product->price,

                    'quantity' =>
                        1,
                ]);
            }

            /*
             * Discount usage
             */

            if ($discountCode) {

                $updated =
                    DiscountCode::where(
                        'id',
                        $discountCode->id
                    )
                    ->where(
                        'is_active',
                        true
                    )
                    ->where(function ($query) {

                        $query
                            ->whereNull('max_uses')
                            ->orWhereColumn(
                                'used_count',
                                '<',
                                'max_uses'
                            );
                    })
                    ->lockForUpdate()
                    ->increment(
                        'used_count'
                    );

                if (!$updated) {

                    abort(
                        redirect()
                            ->route('checkout')
                            ->with(
                                'discount_error',
                                'ظرفیت استفاده از کد تخفیف تمام شده است.'
                            )
                    );
                }
            }

            /*
             * Wallet
             */

            if (
                $total > 0 &&
                $useWallet
            ) {

                $walletService->debit(
                    auth()->user(),
                    $total,
                    'پرداخت سفارش ' .
                        $order->order_number,
                    Order::class,
                    $order->id
                );
            }

            /*
             * Free or Wallet-paid
             */

            if (
                $total <= 0 ||
                $useWallet
            ) {

                return $order;
            }

            return $order;
        });

        /*
         * Cart فقط بعد از موفقیت ایجاد Order پاک شود.
         */

        if (
            $order->status === 'paid'
        ) {

            session()->forget([
                'cart',
                'discount_code',
            ]);

            return redirect()
                ->route(
                    'checkout.success',
                    $order
                )
                ->with(
                    'success',
                    'سفارش شما با موفقیت پرداخت شد.'
                );
        }

        /*
         * در این نقطه هنوز Wallet کافی نیست.
         *
         * پس Cart را پاک نمی‌کنیم.
         */

        return redirect()
            ->route(
                'payment.pay',
                $order
            );
    }

    public function success(Order $order)
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        $order->load('items.product');

        return view(
            'checkout-success',
            compact('order')
        );
    }

    private function getValidDiscount(
        bool $lock = false
    ): ?DiscountCode {
        $code = session('discount_code');

        if (!$code) {
            return null;
        }

        $query = DiscountCode::where(
            'code',
            $code
        );

        if ($lock) {
            $query->lockForUpdate();
        }

        $discount = $query->first();

        if (!$discount || !$discount->valid()) {
            session()->forget('discount_code');

            return null;
        }

        return $discount;
    }

    private function calculateDiscount(
        float $subtotal,
        DiscountCode $discount
    ): float {
        if ($subtotal <= 0) {
            return 0;
        }

        if ($discount->is_percent) {
            $value =
                $subtotal *
                ((float) $discount->amount / 100);
        } else {
            $value =
                (float) $discount->amount;
        }

        return min(
            $subtotal,
            max(0, $value)
        );
    }

    private function bought(Product $product): bool
    {
        return Order::where(
            'user_id',
            auth()->id()
        )
        ->whereIn(
            'status',
            ['paid', 'completed']
        )
        ->whereHas(
            'items',
            fn ($query) =>
                $query->where(
                    'product_id',
                    $product->id
                )
        )
        ->exists();
    }

    private function orderNumber(): string
    {
        do {
            $number =
                'DS-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(Str::random(5));

        } while (
            Order::where(
                'order_number',
                $number
            )->exists()
        );

        return $number;
    }
}
