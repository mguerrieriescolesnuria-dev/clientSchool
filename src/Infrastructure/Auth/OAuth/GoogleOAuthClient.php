<?php

declare(strict_types=1);

namespace App\Infrastructure\Auth\OAuth;

final class GoogleOAuthClient
{
    /**
     * @param array{client_id:string,client_secret:string,redirect_uri:string} $config
     */
    public function __construct(private readonly array $config)
    {
    }

    public function authorizationUrl(string $state): string
    {
        $query = http_build_query([
            'client_id' => $this->config['client_id'],
            'redirect_uri' => $this->config['redirect_uri'],
            'response_type' => 'code',
            'scope' => 'openid email profile',
            'access_type' => 'offline',
            'prompt' => 'consent',
            'state' => $state,
        ]);

        return 'https://accounts.google.com/o/oauth2/v2/auth?' . $query;
    }

    public function exchangeCode(string $code): GoogleUser
    {
        $tokenResponse = $this->postJson(
            'https://oauth2.googleapis.com/token',
            [
                'code' => $code,
                'client_id' => $this->config['client_id'],
                'client_secret' => $this->config['client_secret'],
                'redirect_uri' => $this->config['redirect_uri'],
                'grant_type' => 'authorization_code',
            ]
        );

        $accessToken = $tokenResponse['access_token'] ?? null;

        if (!is_string($accessToken) || $accessToken === '') {
            throw new \RuntimeException('Google did not return a valid access token');
        }

        $userInfo = $this->getJson(
            'https://www.googleapis.com/oauth2/v2/userinfo',
            [
                'Authorization: Bearer ' . $accessToken,
            ]
        );

        if (!isset($userInfo['id'], $userInfo['name'], $userInfo['email'])) {
            throw new \RuntimeException('Google user information is incomplete');
        }

        return new GoogleUser(
            (string) $userInfo['id'],
            (string) $userInfo['name'],
            (string) $userInfo['email'],
            isset($userInfo['picture']) ? (string) $userInfo['picture'] : null
        );
    }

    /**
     * @param array<string, string> $payload
     * @return array<string, mixed>
     */
    private function postJson(string $url, array $payload): array
    {
        $options = [
            'http' => [
                'method' => 'POST',
                'header' => "Content-Type: application/x-www-form-urlencoded\r\nAccept: application/json\r\n",
                'content' => http_build_query($payload),
                'ignore_errors' => true,
                'timeout' => 15,
            ],
        ];

        $response = file_get_contents($url, false, stream_context_create($options));

        if ($response === false) {
            throw new \RuntimeException('Unable to connect to Google token endpoint');
        }

        /** @var array<string, mixed>|null $decoded */
        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid response from Google token endpoint');
        }

        if (isset($decoded['error'])) {
            throw new \RuntimeException('Google token error: ' . (string) $decoded['error']);
        }

        return $decoded;
    }

    /**
     * @param array<int, string> $headers
     * @return array<string, mixed>
     */
    private function getJson(string $url, array $headers = []): array
    {
        $options = [
            'http' => [
                'method' => 'GET',
                'header' => implode("\r\n", array_merge(['Accept: application/json'], $headers)) . "\r\n",
                'ignore_errors' => true,
                'timeout' => 15,
            ],
        ];

        $response = file_get_contents($url, false, stream_context_create($options));

        if ($response === false) {
            throw new \RuntimeException('Unable to connect to Google user endpoint');
        }

        /** @var array<string, mixed>|null $decoded */
        $decoded = json_decode($response, true);

        if (!is_array($decoded)) {
            throw new \RuntimeException('Invalid response from Google user endpoint');
        }

        if (isset($decoded['error'])) {
            $message = is_array($decoded['error']) ? json_encode($decoded['error']) : (string) $decoded['error'];
            throw new \RuntimeException('Google user error: ' . $message);
        }

        return $decoded;
    }
}
