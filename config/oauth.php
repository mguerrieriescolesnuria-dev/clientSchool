<?php

declare(strict_types=1);

return [
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID', ''),
        'client_secret' => env('GOOGLE_CLIENT_SECRET', ''),
        'redirect_uri' => env('GOOGLE_REDIRECT_URI', 'http://127.0.0.1:8001/auth/callback'),
    ],
    'jwt' => [
        'secret' => env('JWT_SECRET', 'change-this-secret'),
        'issuer' => env('JWT_ISSUER', 'clientschool-api'),
        'ttl' => (int) env('JWT_TTL', 3600),
    ],
    'frontend_success_redirect' => env('OAUTH_FRONTEND_SUCCESS_REDIRECT', ''),
];
