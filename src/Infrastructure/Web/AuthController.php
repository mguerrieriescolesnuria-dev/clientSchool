<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Auth\Application\Login\LoginCommand;
use App\Auth\Application\Login\LoginHandler;
use App\Auth\Infrastructure\Persistence\Doctrine\DoctrineAuthRepository;
use App\Infrastructure\Auth\OAuth\GoogleOAuthClient;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;

final class AuthController
{
    /**
     * @param array<string, mixed>|null $config
     */
    public function __construct(private readonly ?array $config = null)
    {
    }

    public function redirectToGoogle(): void
    {
        $config = $this->config();
        $google = $config['google'];

        if ($google['client_id'] === '' || $google['client_secret'] === '') {
            error_response('Google OAuth is not configured', 500);
        }

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $state = bin2hex(random_bytes(16));
        $_SESSION['oauth_state'] = $state;

        $client = new GoogleOAuthClient($google);
        redirect_response($client->authorizationUrl($state));
    }

    public function callback(): void
    {
        $config = $this->config();

        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $expectedState = $_SESSION['oauth_state'] ?? null;
        $state = $_GET['state'] ?? null;
        $code = $_GET['code'] ?? null;

        if (!is_string($expectedState) || !is_string($state) || $state !== $expectedState) {
            error_response('Invalid OAuth state', 401);
        }

        if (!is_string($code) || $code === '') {
            error_response('Authorization code missing', 422);
        }

        unset($_SESSION['oauth_state']);

        $googleClient = new GoogleOAuthClient($config['google']);
        $googleUser = $googleClient->exchangeCode($code);

        $handler = new LoginHandler(
            new DoctrineAuthRepository(),
            new JwtTokenGenerator($config['jwt'])
        );

        $result = $handler->handle(new LoginCommand(
            $googleUser->name,
            $googleUser->email,
            'google',
            $googleUser->id,
            $googleUser->avatar
        ));

        if (($config['frontend_success_redirect'] ?? '') !== '') {
            $separator = str_contains($config['frontend_success_redirect'], '?') ? '&' : '?';
            redirect_response($config['frontend_success_redirect'] . $separator . http_build_query([
                'token' => $result['token'],
                'email' => $result['user']['email'],
            ]));
        }

        html_response(
            '<h1>OAuth completat</h1><p>Copia aquest token per provar els endpoints protegits.</p>'
            . '<textarea style="width:100%;min-height:140px;">' . htmlspecialchars($result['token'], ENT_QUOTES, 'UTF-8') . '</textarea>'
            . '<p><strong>Email:</strong> ' . htmlspecialchars((string) $result['user']['email'], ENT_QUOTES, 'UTF-8') . '</p>'
        );
    }

    public function me(): void
    {
        $claims = current_auth_claims();

        if ($claims === null) {
            error_response('Unauthorized', 401);
        }

        json_response($claims, 200, 'Authenticated user');
    }

    /**
     * @return array<string, mixed>
     */
    private function config(): array
    {
        return $this->config ?? require __DIR__ . '/../../../config/oauth.php';
    }
}
