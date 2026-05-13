<?php

declare(strict_types=1);

namespace Tests\Application\Auth;

use App\Auth\Domain\User;
use App\Auth\Domain\UserId;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;
use App\Infrastructure\Auth\Token\TokenValidator;
use PHPUnit\Framework\TestCase;

final class TokenValidatorTest extends TestCase
{
    public function test_generated_token_is_valid(): void
    {
        $config = [
            'secret' => 'another-secret',
            'issuer' => 'clientschool-tests',
            'ttl' => 3600,
        ];

        $user = new User(
            new UserId('auth-user-1'),
            'Laura Garcia',
            'laura@example.com',
            'google',
            'google-123',
            null
        );

        $token = (new JwtTokenGenerator($config))->generate($user);
        $claims = (new TokenValidator($config))->validate($token);

        $this->assertNotNull($claims);
        $this->assertSame('auth-user-1', $claims['sub']);
        $this->assertSame('laura@example.com', $claims['email']);
    }

    public function test_tampered_token_is_rejected(): void
    {
        $config = [
            'secret' => 'another-secret',
            'issuer' => 'clientschool-tests',
            'ttl' => 3600,
        ];

        $user = new User(
            new UserId('auth-user-2'),
            'Carlos Martinez',
            'carlos@example.com',
            'google',
            'google-456',
            null
        );

        $token = (new JwtTokenGenerator($config))->generate($user);
        $tamperedToken = substr($token, 0, -2) . 'xx';

        $this->assertNull((new TokenValidator($config))->validate($tamperedToken));
    }
}
