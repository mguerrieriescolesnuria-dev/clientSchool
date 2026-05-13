<?php

declare(strict_types=1);

namespace App\Auth\Domain;

final class User
{
    public function __construct(
        private readonly UserId $id,
        private string $name,
        private string $email,
        private string $provider,
        private string $providerId,
        private ?string $avatar = null
    ) {}

    public function id(): UserId
    {
        return $this->id;
    }

    public function name(): string
    {
        return $this->name;
    }

    public function email(): string
    {
        return $this->email;
    }

    public function provider(): string
    {
        return $this->provider;
    }

    public function providerId(): string
    {
        return $this->providerId;
    }

    public function avatar(): ?string
    {
        return $this->avatar;
    }

    public function syncProfile(
        string $name,
        string $email,
        string $provider,
        string $providerId,
        ?string $avatar
    ): void
    {
        $this->name = $name;
        $this->email = $email;
        $this->provider = $provider;
        $this->providerId = $providerId;
        $this->avatar = $avatar;
    }
}
