<?php

namespace App\Services\Order;

use App\Models\Invoice;
use App\Models\Order;
use App\Notifications\DigitalShopNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class FinalizeOrderService
{
    public function finalize(
        Order $order,
        array $payment = []
    ): Order {
        return DB::transaction(function () use (
            $order,
            $payment
        ) {

            $order = Order::query()
                ->with('items.product')
                ->lockForUpdate()
                ->findOrFail($order->id);

            /*
             * Idempotency
             *
             * اگر سفارش قبلاً نهایی شده،
             * هیچ عملیات مالی/اعلانی دوباره انجام نمی‌شود.
             */
            if ($order->status === 'paid') {

                if (!$order->invoice()->exists()) {
                    $this->createInvoice(
                        $order,
                        $payment
                    );
                }

                return $order;
            }

            $order->status = 'paid';
            $order->paid_at = $order->paid_at ?: now();

            if (
                isset($payment['payment_method']) &&
                $payment['payment_method']
            ) {
                $order->payment_method =
                    $payment['payment_method'];
            }

            $order->save();

            /*
             * Payment record
             */
            if (
                !empty($payment['transaction_id'])
            ) {
                $order->payments()->firstOrCreate(
                    [
                        'transaction_id' =>
                            (string) $payment['transaction_id'],
                    ],
                    [
                        'gateway' =>
                            $payment['gateway'] ?? 'gateway',

                        'amount' =>
                            (int) (
                                $payment['amount']
                                ?? $order->gateway_amount
                            ),

                        'status' => 'paid',

                        'paid_at' => now(),
                    ]
                );
            }

            /*
             * Invoice
             */
            $invoice = $this->createInvoice(
                $order,
                $payment
            );

            /*
             * Notification
             */
            $this->sendPurchaseNotification(
                $order,
                $invoice
            );

            /*
             * Cart cleanup
             *
             * فقط بعد از paid شدن واقعی سفارش.
             */
            $this->removePurchasedItemsFromCart(
                $order
            );

            return $order->fresh([
                'items.product',
                'invoice',
            ]);
        });
    }

    private function createInvoice(
        Order $order,
        array $payment = []
    ): Invoice {
        $existing = Invoice::where(
            'order_id',
            $order->id
        )->first();

        if ($existing) {
            return $existing;
        }

        return Invoice::create([
            'order_id' =>
                $order->id,

            'user_id' =>
                $order->user_id,

            'invoice_number' =>
                $this->invoiceNumber(),

            'subtotal' =>
                (int) $order->subtotal,

            'discount' =>
                (int) $order->discount,

            'tax' =>
                (int) $order->tax,

            'total' =>
                (int) $order->total,

            'wallet_amount' =>
                (int) $order->wallet_amount,

            'gateway_amount' =>
                (int) $order->gateway_amount,

            'payment_method' =>
                $payment['payment_method']
                ?? $order->payment_method,

            'status' =>
                'paid',

            'issued_at' =>
                now(),

            'metadata' => [
                'gateway' =>
                    $payment['gateway'] ?? null,

                'transaction_id' =>
                    $payment['transaction_id'] ?? null,

                'ref_id' =>
                    $payment['ref_id'] ?? null,
            ],
        ]);
    }

    private function invoiceNumber(): string
    {
        do {
            $number =
                'INV-' .
                now()->format('YmdHis') .
                '-' .
                strtoupper(
                    Str::random(5)
                );

        } while (
            Invoice::where(
                'invoice_number',
                $number
            )->exists()
        );

        return $number;
    }

    private function removePurchasedItemsFromCart(
        Order $order
    ): void {
        $cart = session('cart', []);

        if (!$cart) {
            return;
        }

        foreach ($order->items as $item) {
            unset(
                $cart[$item->product_id]
            );
        }

        if ($cart) {
            session([
                'cart' => $cart,
            ]);
        } else {
            session()->forget('cart');
        }
    }

private function sendPurchaseNotification(
    Order $order,
    Invoice $invoice
): void {
    $user = $order->user;

    if (!$user) {
        return;
    }

    $alreadySent = $user->notifications()
        ->where(
            'type',
            DigitalShopNotification::class
        )
        ->where(
            'data->category',
            'order'
        )
        ->where(
            'data->meta->event',
            'order_paid'
        )
        ->where(
            'data->meta->order_id',
            $order->id
        )
        ->exists();

    if ($alreadySent) {
        return;
    }

    $user->notify(
        new DigitalShopNotification(
            category: 'order',

            title: 'خرید با موفقیت انجام شد',

            message:
                'سفارش ' .
                $order->order_number .
                ' با موفقیت پرداخت شد.',

            url:
                route(
                    'checkout.success',
                    $order
                ),

            actionLabel:
                'مشاهده سفارش',

            meta: [
                'event' =>
                    'order_paid',

                'order_id' =>
                    $order->id,

                'invoice_id' =>
                    $invoice->id,

                'order_number' =>
                    $order->order_number,

                'total' =>
                    (int) $order->total,
            ],
        )
    );
}
}