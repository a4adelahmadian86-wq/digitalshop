<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Log;

class TestSmsProvider implements SmsProviderInterface
{
    public function sendOtp(
        string $phone,
        string $code
    ): bool {

        $message =
            PHP_EOL .
            '========================================' .
            PHP_EOL .
            ' DIGITALSHOP TEST SMS' .
            PHP_EOL .
            '----------------------------------------' .
            PHP_EOL .
            ' Phone: ' . $phone .
            PHP_EOL .
            ' OTP:   ' . $code .
            PHP_EOL .
            ' Time:  ' . now()->toDateTimeString() .
            PHP_EOL .
            '========================================' .
            PHP_EOL;

        Log::info(
            'DIGITALSHOP TEST SMS',
            [
                'phone' => $phone,
                'otp' => $code,
            ]
        );

        /*
         * برای محیط توسعه
         */
        file_put_contents(
            storage_path(
                'logs/test-sms.log'
            ),
            $message,
            FILE_APPEND
        );

        return true;
    }
}