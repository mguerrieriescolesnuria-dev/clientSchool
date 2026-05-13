<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Token;

use App\Auth\Domain\User;

final class JwtTokenGenerator
{
    /**
     * @param array{secret:string,issuer:string,ttl:int} $config
     */
    public function __construct(private readonly array $config)
    {
    }

    public function generate(User $user): string
    {
        $issuedAt = time();
        $expiresAt = $issuedAt + $this->config['ttl'];

        $header = ['alg' => 'HS256', 'typ' => 'JWT'];
        $payload = [
            'iss' => $this->config['issuer'],
            'sub' => $user->id()->value(),
            'email' => $user->email(),
            'name' => $user->name(),
            'provider' => $user->provider(),
            'exp' => $expiresAt,
            'iat' => $issuedAt,
        ];

        $encodedHeader = base64_url_encode(json_encode($header, JSON_UNESCAPED_SLASHES));
        $encodedPayload = base64_url_encode(json_encode($payload, JSON_UNESCAPED_SLASHES));
        $signature = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->config['secret'], true);

        return $encodedHeader . '.' . $encodedPayload . '.' . base64_url_encode($signature);
    }
}
