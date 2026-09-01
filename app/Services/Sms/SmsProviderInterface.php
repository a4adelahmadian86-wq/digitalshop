<?php

namespace App\Services\Sms;

interface SmsProviderInterface
{
    public function sendOtp(
        string $phone,
        string $code
    ): bool;
}