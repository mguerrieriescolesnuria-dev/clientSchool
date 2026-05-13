<?php

declare(strict_types=1);

namespace App\Auth\Domain;

interface AuthRepository
{
    public function findByProvider(string $provider, string $providerId): ?User;

    public function findByEmail(string $email): ?User;

    public function findById(string $id): ?User;

    public function save(User $user): void;
}
