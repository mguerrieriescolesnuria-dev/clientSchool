<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\Token;

final class TokenValidator
{
    /**
     * @param array{secret:string,issuer:string,ttl:int} $config
     */
    public function __construct(private readonly array $config)
    {
    }

    /**
     * @return array<string, mixed>|null
     */
    public function validate(string $token): ?array
    {
        $parts = explode('.', $token);

        if (count($parts) !== 3) {
            return null;
        }

        [$encodedHeader, $encodedPayload, $encodedSignature] = $parts;
        $expected = hash_hmac('sha256', $encodedHeader . '.' . $encodedPayload, $this->config['secret'], true);

        if (!hash_equals($expected, base64_url_decode($encodedSignature))) {
            return null;
        }

        $payload = json_decode(base64_url_decode($encodedPayload), true);

        if (!is_array($payload)) {
            return null;
        }

        if (($payload['iss'] ?? null) !== $this->config['issuer']) {
            return null;
        }

        if (!isset($payload['exp']) || (int) $payload['exp'] < time()) {
            return null;
        }

        return $payload;
    }
}
