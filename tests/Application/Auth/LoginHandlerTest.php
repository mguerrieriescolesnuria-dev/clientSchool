<?php

declare(strict_types=1);

namespace Tests\Application\Auth;

use App\Auth\Application\Login\LoginCommand;
use App\Auth\Application\Login\LoginHandler;
use App\Auth\Domain\AuthRepository;
use App\Auth\Domain\User;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;
use App\Infrastructure\Auth\Token\TokenValidator;
use PHPUnit\Framework\TestCase;

final class LoginHandlerTest extends TestCase
{
    public function test_google_login_creates_user_and_returns_jwt(): void
    {
        $repository = new InMemoryAuthRepository();
        $jwtConfig = [
            'secret' => 'test-secret',
            'issuer' => 'clientschool-tests',
            'ttl' => 3600,
        ];

        $handler = new LoginHandler($repository, new JwtTokenGenerator($jwtConfig));

        $result = $handler->handle(new LoginCommand(
            'Mauro Guerrieri',
            'mauro@example.com',
            'google',
            'google-user-1',
            'https://avatar.example.com/mauro.png'
        ));

        $this->assertArrayHasKey('token', $result);
        $this->assertSame('mauro@example.com', $result['user']['email']);

        $claims = (new TokenValidator($jwtConfig))->validate($result['token']);

        $this->assertNotNull($claims);
        $this->assertSame('mauro@example.com', $claims['email']);
        $this->assertSame('google', $claims['provider']);
    }

    public function test_google_login_updates_existing_user_with_same_email(): void
    {
        $repository = new InMemoryAuthRepository();
        $jwtConfig = [
            'secret' => 'test-secret',
            'issuer' => 'clientschool-tests',
            'ttl' => 3600,
        ];

        $handler = new LoginHandler($repository, new JwtTokenGenerator($jwtConfig));

        $first = $handler->handle(new LoginCommand(
            'Mauro',
            'mauro@example.com',
            'google',
            'google-user-1'
        ));

        $second = $handler->handle(new LoginCommand(
            'Mauro Actualitzat',
            'mauro@example.com',
            'google',
            'google-user-1',
            'https://avatar.example.com/new.png'
        ));

        $this->assertSame($first['user']['id'], $second['user']['id']);
        $this->assertSame('Mauro Actualitzat', $second['user']['name']);
        $this->assertCount(1, $repository->all());
    }
}

final class InMemoryAuthRepository implements AuthRepository
{
    /** @var array<string, User> */
    private array $users = [];

    public function findByProvider(string $provider, string $providerId): ?User
    {
        foreach ($this->users as $user) {
            if ($user->provider() === $provider && $user->providerId() === $providerId) {
                return $user;
            }
        }

        return null;
    }

    public function findByEmail(string $email): ?User
    {
        foreach ($this->users as $user) {
            if ($user->email() === $email) {
                return $user;
            }
        }

        return null;
    }

    public function findById(string $id): ?User
    {
        return $this->users[$id] ?? null;
    }

    public function save(User $user): void
    {
        $this->users[$user->id()->value()] = $user;
    }

    /**
     * @return array<string, User>
     */
    public function all(): array
    {
        return $this->users;
    }
}
