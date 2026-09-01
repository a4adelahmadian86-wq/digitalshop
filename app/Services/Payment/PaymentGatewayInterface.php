<?php

namespace App\Services\Payment;

interface PaymentGatewayInterface
{
    public function payOrder(
        $order
    ): ?string;

    public function verifyOrder(
        $order,
        array $data
    ): array|false;

    public function payTopup(
        $topup
    ): ?string;

    public function verifyTopup(
        $topup,
        array $data
    ): array|false;
}