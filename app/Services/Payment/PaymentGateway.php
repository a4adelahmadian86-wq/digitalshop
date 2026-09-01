<?php

namespace App\Services\Payment;

use App\Models\Order;

interface PaymentGateway
{
    public function pay(
        Order $order
    ): ?string;

    public function verify(
        Order $order,
        array $data
    ): array|false;

    public function payAmount(
        int $amount,
        string $description,
        string $callbackUrl
    ): ?string;

    public function verifyAmount(
        int $amount,
        string $authority
    ): ?string;

    public function gatewayUrl(
        string $authority
    ): string;
}