<?php

namespace App\Services\Sms;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use RuntimeException;
use Throwable;

class KPanelSmsProvider implements SmsProviderInterface
{
    public function sendOtp(
        string $phone,
        string $code
    ): bool {
        $apiKey = config(
            'ippanel.api_key'
        );

        $baseUrl = rtrim(
            config(
                'ippanel.base_url',
                'https://edge.ippanel.com/v1/api'
            ),
            '/'
        );

        $from = config(
            'services.kpanel.from'
        );

        $pattern = config(
            'services.kpanel.pattern'
        );

        if (
            !$apiKey ||
            !$from ||
            !$pattern
        ) {
            Log::error(
                'KPanel SMS configuration is incomplete.',
                [
                    'has_api_key' => !empty($apiKey),
                    'has_from' => !empty($from),
                    'has_pattern' => !empty($pattern),
                ]
            );

            throw new RuntimeException(
                'ارسال پیامک در حال حاضر امکان‌پذیر نیست.'
            );
        }

        $url = $baseUrl . '/send';

        $payload = [
            'sending_type' => 'pattern',

            'from_number' => $from,

            'code' => $pattern,

            'recipients' => [
                $phone,
            ],

            'params' => [
                'code' => $code,
            ],
        ];

        try {
            $response = Http::timeout(15)
                ->withHeaders([
                    'Content-Type' => 'application/json',
                    'Authorization' => $apiKey,
                ])
                ->post(
                    $url,
                    $payload
                );
        } catch (Throwable $e) {

            Log::error(
                'KPanel SMS HTTP request failed.',
                [
                    'exception' => $e->getMessage(),
                    'url' => $url,
                ]
            );

            throw new RuntimeException(
                'ارسال پیامک در حال حاضر امکان‌پذیر نیست.'
            );
        }

        /*
         * خطای واقعی سرویس فقط در لاگ ثبت می‌شود
         * و هرگز به کاربر نمایش داده نمی‌شود.
         */
        if (!$response->successful()) {

            Log::error(
                'KPanel SMS API returned an HTTP error.',
                [
                    'http_status' => $response->status(),
                    'response' => $response->body(),
                    'url' => $url,
                    'from' => $from,
                    'pattern' => $pattern,
                ]
            );

            throw new RuntimeException(
                'ارسال پیامک در حال حاضر امکان‌پذیر نیست.'
            );
        }

        $data = $response->json();

        /*
         * پاسخ موفق استاندارد IPPanel/KPanel
         */
        if (
            is_array($data) &&
            (($data['meta']['status'] ?? false) === true)
        ) {
            return true;
        }

        /*
         * پشتیبانی از ساختارهای دیگری که ممکن است API برگرداند.
         */
        if (
            is_array($data) &&
            (($data['status'] ?? false) === true)
        ) {
            return true;
        }

        /*
         * پاسخ HTTP موفق ولی عملیات SMS ناموفق بوده است.
         */
        Log::error(
            'KPanel SMS API returned an unsuccessful response.',
            [
                'http_status' => $response->status(),
                'response' => $response->body(),
                'url' => $url,
                'from' => $from,
                'pattern' => $pattern,
            ]
        );

        throw new RuntimeException(
            'ارسال پیامک در حال حاضر امکان‌پذیر نیست.'
        );
    }
}