<?php

declare(strict_types=1);

namespace App\Auth\Application\Login;

final class LoginCommand
{
    public function __construct(
        public readonly string $name,
        public readonly string $email,
        public readonly string $provider,
        public readonly string $providerId,
        public readonly ?string $avatar = null
    ) {}
}
