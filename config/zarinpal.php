<?php

return [

    'merchant_id' => env('ZARINPAL_MERCHANT_ID'),

    'sandbox' => env('ZARINPAL_SANDBOX', true),

    'currency' => env('ZARINPAL_CURRENCY', 'toman'),

    'request' => env(
        'ZARINPAL_SANDBOX',
        true
    )
        ? 'https://sandbox.zarinpal.com/pg/v4/payment/request.json'
        : 'https://api.zarinpal.com/pg/v4/payment/request.json',

    'verify' => env(
        'ZARINPAL_SANDBOX',
        true
    )
        ? 'https://sandbox.zarinpal.com/pg/v4/payment/verify.json'
        : 'https://api.zarinpal.com/pg/v4/payment/verify.json',

    'gateway' => env(
        'ZARINPAL_SANDBOX',
        true
    )
        ? 'https://sandbox.zarinpal.com/pg/StartPay/'
        : 'https://www.zarinpal.com/pg/StartPay/',
];