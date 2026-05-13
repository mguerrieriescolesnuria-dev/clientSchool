<?php

declare(strict_types=1);

namespace App\Infrastructure\Web\Middleware;

use App\Infrastructure\Auth\Token\TokenValidator;

final class AuthMiddleware
{
    private readonly TokenValidator $tokenValidator;

    public function __construct(?TokenValidator $tokenValidator = null)
    {
        $config = require __DIR__ . '/../../../../config/oauth.php';
        $this->tokenValidator = $tokenValidator ?? new TokenValidator($config['jwt']);
    }

    public function handle(): void
    {
        $header = authorization_header();

        if ($header === null || !preg_match('/Bearer\s+(.+)/i', $header, $matches)) {
            error_response('Authorization token missing', 401);
        }

        $claims = $this->tokenValidator->validate(trim($matches[1]));

        if ($claims === null) {
            error_response('Invalid or expired token', 401);
        }

        $_SERVER['AUTH_USER'] = json_encode($claims);
    }
}
