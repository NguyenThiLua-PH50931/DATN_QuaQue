<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
    ],
        // ================== MOMO ==================
'momo' => [
    'partner_code' => env('MOMO_PARTNER_CODE'),
    'access_key'   => env('MOMO_ACCESS_KEY'),
    'secret_key'   => env('MOMO_SECRET_KEY'),
    'refund_url'   => env('MOMO_REFUND_URL', 'https://test-payment.momo.vn/v2/gateway/api/refund'),
    'query_url'    => env('MOMO_QUERY_URL',  'https://test-payment.momo.vn/v2/gateway/api/query'), // <-- thêm
],

    // ================== ZaloPay ==================
'zlp' => [
  'app_id'           => env('ZLP_APP_ID'),
  'key1'             => env('ZLP_KEY1'),
  'key2'             => env('ZLP_KEY2'),
  'refund_url'       => env('ZLP_REFUND_URL', 'https://sb-openapi.zalopay.vn/v2/refund'),
  'query_refund_url' => env('ZLP_QUERY_REFUND_URL', 'https://sb-openapi.zalopay.vn/v2/query_refund'),
  'query_order_url'  => env('ZLP_QUERY_ORDER_URL',  'https://sb-openapi.zalopay.vn/v2/query'),
],

];
