<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\Order\FinalizeOrderService;
use App\Services\Checkout\CheckoutPaymentService;
use App\Services\Payment\ZarinPalGateway;
use Illuminate\Http\Request;
use Throwable;

class PaymentController extends Controller
{
    public function pay(Order $order)
    {
        abort_unless(
            $order->user_id === auth()->id(),
            403
        );

        if (
            $order->status === 'paid'
        ) {
            return redirect()->route(
                'checkout.success',
                $order
            );
        }

        if (
            (int) $order->gateway_amount <= 0
        ) {
            return back()->with(
                'error',
                'مبلغ قابل پرداخت از درگاه وجود ندارد.'
            );
        }

        $gateway =
            $this->gateway();

        $url =
            $gateway->pay($order);

        if (!$url) {
            return back()->with(
                'error',
                'خطا در اتصال به درگاه پرداخت.'
            );
        }

        return redirect($url);
    }

public function callback(
    Request $request,
    FinalizeOrderService $finalizer
) {
    $authority =
        $request->query('Authority');

    if (!$authority) {
        return redirect('/')
            ->with(
                'error',
                'شناسه پرداخت نامعتبر است.'
            );
    }

    $order = Order::where(
        'payment_authority',
        $authority
    )->first();

    if (!$order) {
        return redirect('/')
            ->with(
                'error',
                'سفارش پیدا نشد.'
            );
    }

    /*
     * Idempotency:
     * اگر قبلاً نهایی شده، دوباره پرداخت/Download
     * و Invoice را پردازش نکن.
     */
    if ($order->status === 'paid') {

        return redirect()->route(
            'checkout.success',
            $order
        );
    }

    if (
        $request->query('Status') !== 'OK'
    ) {

        $order->update([
            'status' => 'failed',
        ]);

        return redirect()->route(
            'checkout.success',
            $order
        )->with(
            'error',
            'پرداخت توسط درگاه انجام نشد.'
        );
    }

    $result =
        $this->gateway()->verify(
            $order,
            $request->query()
        );

    if (!$result) {

        return redirect()->route(
            'checkout.success',
            $order
        )->with(
            'error',
            'پرداخت تأیید نشد.'
        );
    }

    $finalizer->finalize(
        $order,
        [
            'gateway' =>
                'zarinpal',

            'transaction_id' =>
                $result['ref_id']
                ?? null,

            'ref_id' =>
                $result['ref_id']
                ?? null,

            'amount' =>
                $order->gateway_amount
                ?: $order->total,

            'payment_method' =>
                $order->payment_method
                ?: 'gateway',
        ]
    );

    return redirect()->route(
        'checkout.success',
        $order
    )->with(
        'success',
        'پرداخت با موفقیت انجام شد.'
    );
}
    private function gateway()
    {
        return new ZarinPalGateway;
    }
}