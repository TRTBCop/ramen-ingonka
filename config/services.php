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

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],
    'messageme' => [ // sms 발송
        'api_key' => env('MESSAGEME_API_KEY ', 'AUUXQ9J49UW0125'),
        'callback' => env('MESSAGEME_CALLBACK ', '18005039')
    ],
    'naver' => [
        'client_id' => env('NAVER_CLIENT_ID', 'PFpSyBfyN4I_hZrC858V'),
        'client_secret' => env('NAVER_CLIENT_SECRET', 'QqkU9rPXTu'),
        'redirect' => env('NAVER_REDIRECT', env('APP_URL').'/auth/social/naver/callback')
    ],
    'kakao' => [
        'client_id' => env('KAKAO_CLIENT_ID', '5591f1367f5054187ae52fd2e38f0f65'),
        'client_secret' => env('KAKAO_CLIENT_SECRET', ''),
        'redirect' => env('KAKAO_REDIRECT', env('APP_URL').'/auth/social/kakao/callback')
    ]
];
