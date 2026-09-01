<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Facades\Http;

class ZarinPalGateway implements PaymentGateway
{
    public function pay(
        Order $order
    ): ?string {

        $amount = (int) $order->gateway_amount;

        if ($amount <= 0) {
            return null;
        }

        $response = Http::timeout(20)->post(
            $this->url('request'),
            [
                'merchant_id' => config(
                    'zarinpal.merchant_id'
                ),

                'amount' => $this->amount(
                    $amount
                ),

                'callback_url' => route(
                    'payment.callback'
                ),

                'description' =>
                    'خرید ' .
                    $order->order_number,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (
            ($data['data']['code'] ?? 0) != 100
        ) {
            return null;
        }

        $authority =
            $data['data']['authority'] ?? null;

        if (!$authority) {
            return null;
        }

        $order->update([
            'payment_authority' => $authority,
        ]);

        return $this->gatewayUrl(
            $authority
        );
    }

    public function verify(
        Order $order,
        array $data
    ) {
        if (
            $order->status === 'paid'
        ) {
            return [
                'already_paid' => true,
                'ref_id' =>
                    $order->payment_ref_id,
            ];
        }

        if (
            empty($order->payment_authority)
        ) {
            return false;
        }

        $response = Http::timeout(20)->post(
            $this->url('verify'),
            [
                'merchant_id' => config(
                    'zarinpal.merchant_id'
                ),

                'amount' =>
                    $this->amount(
                        $order->gateway_amount
                        ?: $order->total
                    ),

                'authority' =>
                    $order->payment_authority,
            ]
        );

        if (!$response->successful()) {
            return false;
        }

        $result = $response->json();

        if (
            ($result['data']['code'] ?? 0) != 100
        ) {
            return false;
        }

        $refId =
            $result['data']['ref_id'] ?? null;

        return [
            'ref_id' => $refId,
        ];
    }

    public function payAmount(
        int $amount,
        string $description,
        string $callbackUrl
    ): ?string {

        if ($amount <= 0) {
            return null;
        }

        $response = Http::timeout(20)->post(
            $this->url('request'),
            [
                'merchant_id' => config(
                    'zarinpal.merchant_id'
                ),

                'amount' =>
                    $this->amount($amount),

                'callback_url' =>
                    $callbackUrl,

                'description' =>
                    $description,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        if (
            ($data['data']['code'] ?? 0) != 100
        ) {
            return null;
        }

        return $data['data']['authority']
            ?? null;
    }

    public function verifyAmount(
        int $amount,
        string $authority
    ): ?string {

        if (
            $amount <= 0 ||
            !$authority
        ) {
            return null;
        }

        $response = Http::timeout(20)->post(
            $this->url('verify'),
            [
                'merchant_id' => config(
                    'zarinpal.merchant_id'
                ),

                'amount' =>
                    $this->amount($amount),

                'authority' =>
                    $authority,
            ]
        );

        if (!$response->successful()) {
            return null;
        }

        $data = $response->json();

        $code =
            $data['data']['code'] ?? 0;

        if (
            !in_array(
                (int) $code,
                [100, 101],
                true
            )
        ) {
            return null;
        }

        return isset($data['data']['ref_id'])
            ? (string) $data['data']['ref_id']
            : null;
    }

    public function gatewayUrl(
        string $authority
    ): string {
        return $this->url('gateway') .
            $authority;
    }

    private function amount(
        int $price
    ): int {
        return config(
            'zarinpal.currency'
        ) === 'toman'
            ? $price * 10
            : $price;
    }

    private function url(
        string $type
    ): string {

        $sandbox =
            config('zarinpal.sandbox');

        $base = $sandbox
            ? 'https://sandbox.zarinpal.com/pg/v4/payment/'
            : 'https://api.zarinpal.com/pg/v4/payment/';

        if (
            $type === 'gateway'
        ) {
            return $sandbox
                ? 'https://sandbox.zarinpal.com/pg/StartPay/'
                : 'https://www.zarinpal.com/pg/StartPay/';
        }

        return $base .
            $type .
            '.json';
    }
}
