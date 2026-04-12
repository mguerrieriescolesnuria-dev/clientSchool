<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Domain\Student\Student;
use App\Models\Domain\Student\StudentId;

class StudentController
{
    public function index(): void
    {
        json_response([
            ['id' => '1', 'name' => 'Alice Johnson', 'email' => 'alice@student.com'],
            ['id' => '2', 'name' => 'Bob Wilson', 'email' => 'bob@student.com'],
        ], 200);
    }

    public function show(string $id): void
    {
        json_response([
            'id' => $id,
            'name' => 'Alice Johnson',
            'email' => 'alice@student.com',
            'enrollments' => []
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

        json_response([
            'id' => uniqid(),
            'name' => $data['name'],
            'email' => $data['email'],
        ], 201, 'Student created successfully');
    }

    public function update(string $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            error_response('Invalid data', 422);
        }

        json_response([
            'id' => $id,
            'name' => $data['name'] ?? 'Alice Johnson',
            'email' => $data['email'] ?? 'alice@student.com',
        ], 200, 'Student updated successfully');
    }

    public function destroy(string $id): void
    {
        json_response([], 200, 'Student deleted successfully');
    }
}
