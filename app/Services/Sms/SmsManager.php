<?php

namespace App\Services\Sms;

class SmsManager
{
    public function __construct(
        private SmsProviderInterface $provider
    ) {
    }

    public function sendOtp(
        string $phone,
        string $code
    ): bool {
        return $this->provider->sendOtp(
            $phone,
            $code
        );
    }
}