<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Domain\Teacher\Teacher;
use App\Domain\Teacher\TeacherId;

class TeacherController
{
    public function index(): void
    {
        // TODO: Implement list teachers
        json_response([
            ['id' => '1', 'name' => 'John Doe', 'email' => 'john@school.com'],
            ['id' => '2', 'name' => 'Jane Smith', 'email' => 'jane@school.com'],
        ], 200);
    }

    public function show(string $id): void
    {
        // TODO: Implement show teacher
        json_response([
            'id' => $id,
            'name' => 'John Doe',
            'email' => 'john@school.com',
            'subjects' => []
        ], 200);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['name']) || empty($data['email'])) {
            error_response('Name and email are required', 422, [
                'name' => empty($data['name'] ?? null) ? ['Name is required'] : [],
                'email' => empty($data['email'] ?? null) ? ['Email is required'] : []
            ]);
        }

        // TODO: Create teacher with repository
        json_response([
            'id' => uniqid(),
            'name' => $data['name'],
            'email' => $data['email'],
        ], 201, 'Teacher created successfully');
    }

    public function update(string $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            error_response('Invalid data', 422);
        }

        // TODO: Update teacher with repository
        json_response([
            'id' => $id,
            'name' => $data['name'] ?? 'John Doe',
            'email' => $data['email'] ?? 'john@school.com',
        ], 200, 'Teacher updated successfully');
    }

    public function destroy(string $id): void
    {
        // TODO: Delete teacher with repository
        json_response([], 200, 'Teacher deleted successfully');
    }
}
