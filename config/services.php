<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env(
            'MAILGUN_ENDPOINT',
            'api.mailgun.net'
        ),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env(
            'AWS_DEFAULT_REGION',
            'us-east-1'
        ),
    ],

    /*
    |--------------------------------------------------------------------------
    | SMS
    |--------------------------------------------------------------------------
    */

    'sms_provider' => env(
        'SMS_PROVIDER',
        'test'
    ),

    'kpanel' => [

        'from' => env(
            'KPANEL_FROM',
            '+983000505'
        ),

        'pattern' => env(
            'KPANEL_PATTERN',
            '9t0mltdj1yl41fz'
        ),

    ],

];