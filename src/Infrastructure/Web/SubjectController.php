<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Domain\Subject\Subject;
use App\Domain\Subject\SubjectId;

class SubjectController
{
    public function index(): void
    {
        json_response([
            ['id' => '1', 'name' => 'Mathematics', 'course' => 'A1', 'teacher' => 'John Doe'],
            ['id' => '2', 'name' => 'Physics', 'course' => 'A1', 'teacher' => 'Jane Smith'],
        ], 200);
    }

    public function show(string $id): void
    {
        // Mock data - check if subject exists
        $subjects = [
            '1' => ['id' => '1', 'name' => 'Mathematics', 'course' => 'A1', 'teacher' => ['id' => '1', 'name' => 'John Doe']],
            '2' => ['id' => '2', 'name' => 'Physics', 'course' => 'A1', 'teacher' => ['id' => '2', 'name' => 'Jane Smith']],
        ];
        
        if (!isset($subjects[$id])) {
            error_response('Subject not found', 404);
            return;
        }
        
        json_response($subjects[$id], 200);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data || empty($data['name'])) {
            error_response('Name is required', 422, [
                'name' => empty($data['name'] ?? null) ? ['Name is required'] : []
            ]);
        }

        json_response([
            'id' => uniqid(),
            'name' => $data['name'],
            'course' => $data['course'] ?? 'A1',
            'teacher' => $data['teacher'] ?? null,
        ], 201, 'Subject created successfully');
    }

    public function update(string $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            error_response('Invalid data', 422);
        }

        json_response([
            'id' => $id,
            'name' => $data['name'] ?? 'Mathematics',
            'course' => $data['course'] ?? 'A1',
        ], 200, 'Subject updated successfully');
    }

    public function destroy(string $id): void
    {
        json_response([], 200, 'Subject deleted successfully');
    }
}
