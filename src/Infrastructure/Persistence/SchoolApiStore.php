<?php

declare(strict_types=1);

namespace App\Infrastructure\Persistence;

use PDO;

final class SchoolApiStore
{
    private PDO $pdo;

    /**
     * @var array<string, array<string, mixed>>
     */
    private array $resources = [
        'students' => [
            'table' => 'students',
            'fields' => ['name', 'email'],
            'required' => ['name', 'email'],
            'defaults' => [
                ['id' => 'student_1', 'name' => 'Alice Johnson', 'email' => 'alice@student.com'],
                ['id' => 'student_2', 'name' => 'Bob Wilson', 'email' => 'bob@student.com'],
            ],
        ],
        'teachers' => [
            'table' => 'teachers',
            'fields' => ['name', 'email'],
            'required' => ['name', 'email'],
            'defaults' => [
                ['id' => 'teacher_1', 'name' => 'John Doe', 'email' => 'john@school.com'],
                ['id' => 'teacher_2', 'name' => 'Jane Smith', 'email' => 'jane@school.com'],
            ],
        ],
        'subjects' => [
            'table' => 'subjects',
            'fields' => ['name', 'course', 'teacher'],
            'required' => ['name'],
            'defaults' => [
                ['id' => 'subject_1', 'name' => 'Mathematics', 'course' => 'A1', 'teacher' => 'John Doe'],
                ['id' => 'subject_2', 'name' => 'Physics', 'course' => 'A1', 'teacher' => 'Jane Smith'],
            ],
        ],
    ];

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
        $this->seedDefaults();
    }

    /**
     * @return array<int, array<string, mixed>>
     */
    public function all(string $resource): array
    {
        $config = $this->config($resource);
        $statement = $this->pdo->query(sprintf('SELECT * FROM %s ORDER BY created_at ASC', $config['table']));

        return $statement->fetchAll();
    }

    /**
     * @return array<string, mixed>|null
     */
    public function find(string $resource, string $id): ?array
    {
        $config = $this->config($resource);
        $statement = $this->pdo->prepare(sprintf('SELECT * FROM %s WHERE id = :id LIMIT 1', $config['table']));
        $statement->execute(['id' => $id]);
        $row = $statement->fetch();

        return $row === false ? null : $row;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function create(string $resource, array $payload): array
    {
        $config = $this->config($resource);
        $data = $this->sanitize($resource, $payload, false);
        $data['id'] = uniqid(rtrim($resource, 's') . '_', true);

        $columns = array_keys($data);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);

        $statement = $this->pdo->prepare(sprintf(
            'INSERT INTO %s (%s) VALUES (%s)',
            $config['table'],
            implode(', ', $columns),
            implode(', ', $placeholders)
        ));
        $statement->execute($data);

        return $this->find($resource, $data['id']) ?? $data;
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>|null
     */
    public function update(string $resource, string $id, array $payload): ?array
    {
        $config = $this->config($resource);
        $existing = $this->find($resource, $id);

        if ($existing === null) {
            return null;
        }

        $data = $this->sanitize($resource, $payload, true);

        if ($data === []) {
            return $existing;
        }

        $assignments = array_map(static fn (string $field): string => $field . ' = :' . $field, array_keys($data));
        $data['id'] = $id;

        $statement = $this->pdo->prepare(sprintf(
            'UPDATE %s SET %s WHERE id = :id',
            $config['table'],
            implode(', ', $assignments)
        ));
        $statement->execute($data);

        return $this->find($resource, $id);
    }

    public function delete(string $resource, string $id): bool
    {
        $config = $this->config($resource);
        $statement = $this->pdo->prepare(sprintf('DELETE FROM %s WHERE id = :id', $config['table']));
        $statement->execute(['id' => $id]);

        return $statement->rowCount() > 0;
    }

    /**
     * @return array<string, mixed>
     */
    private function config(string $resource): array
    {
        if (!isset($this->resources[$resource])) {
            throw new \InvalidArgumentException('Resource not supported');
        }

        return $this->resources[$resource];
    }

    private function ensureSchema(): void
    {
        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS students (
                id VARCHAR(100) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS teachers (
                id VARCHAR(100) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                email VARCHAR(255) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );

        $this->pdo->exec(
            'CREATE TABLE IF NOT EXISTS subjects (
                id VARCHAR(100) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                course VARCHAR(255) NOT NULL DEFAULT "A1",
                teacher VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4'
        );
    }

    private function seedDefaults(): void
    {
        foreach ($this->resources as $resource => $config) {
            $count = (int) $this->pdo->query(sprintf('SELECT COUNT(*) FROM %s', $config['table']))->fetchColumn();

            if ($count > 0) {
                continue;
            }

            foreach ($config['defaults'] as $row) {
                $this->insertDefaultRow($config['table'], $row);
            }
        }
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    private function sanitize(string $resource, array $payload, bool $isUpdate): array
    {
        $config = $this->config($resource);
        $data = [];

        foreach ($config['fields'] as $field) {
            if (!array_key_exists($field, $payload)) {
                continue;
            }

            $value = is_string($payload[$field]) ? trim($payload[$field]) : $payload[$field];

            if ($value === '' && !$isUpdate && in_array($field, $config['required'], true)) {
                throw new \InvalidArgumentException(ucfirst($field) . ' is required');
            }

            if ($value === '' && $field === 'teacher') {
                $value = null;
            }

            if ($value === '' && $field === 'course') {
                $value = 'A1';
            }

            if ($value !== '' || $value === null) {
                $data[$field] = $value;
            }
        }

        foreach ($config['required'] as $field) {
            if (!$isUpdate && !isset($data[$field])) {
                throw new \InvalidArgumentException(ucfirst($field) . ' is required');
            }
        }

        return $data;
    }

    /**
     * @param array<string, mixed> $row
     */
    private function insertDefaultRow(string $table, array $row): void
    {
        $columns = array_keys($row);
        $placeholders = array_map(static fn (string $column): string => ':' . $column, $columns);
        $assignments = array_map(static fn (string $column): string => $column . ' = ' . $column, $columns);

        $statement = $this->pdo->prepare(sprintf(
            'INSERT INTO %s (%s) VALUES (%s) ON DUPLICATE KEY UPDATE %s',
            $table,
            implode(', ', $columns),
            implode(', ', $placeholders),
            implode(', ', $assignments)
        ));

        $statement->execute($row);
    }
}
