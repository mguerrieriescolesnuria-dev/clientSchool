<?php

declare(strict_types=1);

namespace App\Auth\Domain;

final class UserId
{
    public function __construct(private readonly string $value)
    {
        if ($this->value === '') {
            throw new \InvalidArgumentException('User id cannot be empty');
        }
    }

    public static function generate(): self
    {
        return new self(uniqid('auth_user_', true));
    }

    public function value(): string
    {
        return $this->value;
    }
}
