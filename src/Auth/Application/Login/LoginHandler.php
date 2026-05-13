<?php

declare(strict_types=1);

namespace App\Auth\Application\Login;

use App\Auth\Domain\AuthRepository;
use App\Auth\Domain\User;
use App\Auth\Domain\UserId;
use App\Infrastructure\Auth\Token\JwtTokenGenerator;

final class LoginHandler
{
    public function __construct(
        private readonly AuthRepository $authRepository,
        private readonly JwtTokenGenerator $tokenGenerator
    ) {}

    /**
     * @return array{token:string,user:array<string,mixed>}
     */
    public function handle(LoginCommand $command): array
    {
        $user = $this->authRepository->findByProvider($command->provider, $command->providerId)
            ?? $this->authRepository->findByEmail($command->email);

        if ($user === null) {
            $user = new User(
                UserId::generate(),
                $command->name,
                $command->email,
                $command->provider,
                $command->providerId,
                $command->avatar
            );
        } else {
            $user->syncProfile(
                $command->name,
                $command->email,
                $command->provider,
                $command->providerId,
                $command->avatar
            );
        }

        $this->authRepository->save($user);

        return [
            'token' => $this->tokenGenerator->generate($user),
            'user' => [
                'id' => $user->id()->value(),
                'name' => $user->name(),
                'email' => $user->email(),
                'provider' => $user->provider(),
                'provider_id' => $user->providerId(),
                'avatar' => $user->avatar(),
            ],
        ];
    }
}
