<?php

namespace App\Services\Checkout;

use App\Models\Order;
use App\Models\Payment;
use App\Models\Wallet;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CheckoutPaymentService
{
    public function __construct(
        protected WalletService $walletService
    ) {
    }

    public function payFromWallet(
        Order $order
    ): Order {

        return DB::transaction(
            function () use ($order) {

                $order = Order::query()
                    ->whereKey($order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($order->status === 'paid') {
                    return $order;
                }

                $amount =
                    (int) $order->total;

                $wallet =
                    Wallet::where(
                        'user_id',
                        $order->user_id
                    )
                    ->lockForUpdate()
                    ->first();

                if (
                    !$wallet ||
                    !$wallet->is_active ||
                    $wallet->balance < $amount
                ) {
                    throw new RuntimeException(
                        'موجودی کیف پول برای این سفارش کافی نیست.'
                    );
                }

                $this->walletService->debit(
                    $wallet,
                    $amount,
                    'پرداخت سفارش ' .
                        $order->order_number,
                    Order::class,
                    $order->id,
                    [
                        'order_number' =>
                            $order->order_number,
                    ]
                );

                $order->update([
                    'status' =>
                        'paid',

                    'wallet_amount' =>
                        $amount,

                    'gateway_amount' =>
                        0,

                    'payment_method' =>
                        'wallet',

                    'paid_at' =>
                        now(),
                ]);

                return $order->fresh();
            }
        );
    }

    public function finalizeGatewayPayment(
        Order $order,
        ?string $refId,
        string $gateway = 'zarinpal'
    ): Order {

        return DB::transaction(
            function () use (
                $order,
                $refId,
                $gateway
            ) {

                $order = Order::query()
                    ->whereKey($order->id)
                    ->lockForUpdate()
                    ->firstOrFail();

                if ($order->status === 'paid') {
                    return $order;
                }

                $walletAmount =
                    (int) $order->wallet_amount;

                $gatewayAmount =
                    (int) $order->gateway_amount;

                if (
                    $walletAmount === 0
                ) {

                    $this->markPaid(
                        $order,
                        $refId,
                        $gateway
                    );

                    return $order->fresh();
                }

                if (
                    $walletAmount > 0
                ) {

                    $wallet =
                        Wallet::where(
                            'user_id',
                            $order->user_id
                        )
                        ->lockForUpdate()
                        ->first();

                    if (
                        !$wallet ||
                        !$wallet->is_active ||
                        $wallet->balance <
                            $walletAmount
                    ) {
                        throw new RuntimeException(
                            'موجودی کیف پول برای تکمیل پرداخت کافی نیست.'
                        );
                    }

                    $this->walletService->debit(
                        $wallet,
                        $walletAmount,
                        'پرداخت بخشی از سفارش ' .
                            $order->order_number,
                        Order::class,
                        $order->id,
                        [
                            'order_number' =>
                                $order->order_number,

                            'gateway_amount' =>
                                $gatewayAmount,
                        ]
                    );
                }

                $this->markPaid(
                    $order,
                    $refId,
                    $gateway
                );

                return $order->fresh();
            }
        );
    }

    private function markPaid(
        Order $order,
        ?string $refId,
        string $gateway
    ): void {

        $order->update([
            'status' =>
                'paid',

            'payment_ref_id' =>
                $refId,

            'paid_at' =>
                now(),
        ]);

        Payment::updateOrCreate(
            [
                'order_id' =>
                    $order->id,
            ],
            [
                'gateway' =>
                    $gateway,

                'transaction_id' =>
                    $refId,

                'amount' =>
                    (int) $order->gateway_amount,

                'status' =>
                    'paid',

                'paid_at' =>
                    now(),
            ]
        );
    }
}
