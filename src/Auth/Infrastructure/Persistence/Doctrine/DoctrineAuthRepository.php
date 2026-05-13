<?php

declare(strict_types=1);

namespace App\Auth\Infrastructure\Persistence\Doctrine;

use App\Auth\Domain\AuthRepository;
use App\Auth\Domain\User;
use App\Auth\Domain\UserId;
use PDO;

final class DoctrineAuthRepository implements AuthRepository
{
    private PDO $pdo;

    public function __construct()
    {
        $host = $_ENV['DB_HOST'] ?? '127.0.0.1';
        $port = $_ENV['DB_PORT'] ?? '3306';
        $database = $_ENV['DB_DATABASE'] ?? 'clientschool_frontend';
        $username = $_ENV['DB_USERNAME'] ?? 'clientschool';
        $password = $_ENV['DB_PASSWORD'] ?? '1234';

        $dsn = sprintf('mysql:host=%s;port=%s;dbname=%s;charset=utf8mb4', $host, $port, $database);

        $this->pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $this->ensureSchema();
    }

    public function findByProvider(string $provider, string $providerId): ?User
    {
        $statement = $this->pdo->prepare(
            'SELECT * FROM auth_users WHERE provider = :provider AND provider_id = :provider_id LIMIT 1'
        );
        $statement->execute([
            'provider' => $provider,
            'provider_id' => $providerId,
        ]);

        $row = $statement->fetch();

        return is_array($row) ? $this->hydrate($row) : null;
    }

    public function findByEmail(string $email): ?User
    {
        $statement = $this->pdo->prepare('SELECT * FROM auth_users WHERE email = :email LIMIT 1');
        $statement->execute(['email' => $email]);
        $row = $statement->fetch();

        return is_array($row) ? $this->hydrate($row) : null;
    }

    public function findById(string $id): ?User
    {
        $statement = $this->pdo->prepare('SELECT * FROM auth_users WHERE id = :id LIMIT 1');
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return is_array($row) ? $this->hydrate($row) : null;
    }

    public function save(User $user): void
    {
        $statement = $this->pdo->prepare(
            'INSERT INTO auth_users (id, name, email, provider, provider_id, avatar)
             VALUES (:id, :name, :email, :provider, :provider_id, :avatar)
             ON DUPLICATE KEY UPDATE
                name = VALUES(name),
                email = VALUES(email),
                provider = VALUES(provider),
                provider_id = VALUES(provider_id),
                avatar = VALUES(avatar)'
        );

        $statement->execute([
            'id' => $user->id()->value(),
            'name' => $user->name(),
            'email' => $user->email(),
            'provider' => $user->provider(),
            'provider_id' => $user->providerId(),
            'avatar' => $user->avatar(),
        ]);
    }

    private function ensureSchema(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS auth_users (
                id VARCHAR(100) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                provider VARCHAR(50) NOT NULL,
                provider_id VARCHAR(255) NOT NULL UNIQUE,
                avatar VARCHAR(500) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    /**
     * @param array<string, mixed> $row
     */
    private function hydrate(array $row): User
    {
        return new User(
            new UserId((string) $row['id']),
            (string) $row['name'],
            (string) $row['email'],
            (string) $row['provider'],
            (string) $row['provider_id'],
            isset($row['avatar']) ? (string) $row['avatar'] : null
        );
    }
}
