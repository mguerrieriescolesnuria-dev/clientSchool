<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Infrastructure\Persistence\SchoolApiStore;

class TeacherController
{
    public function __construct(
        private readonly SchoolApiStore $store = new SchoolApiStore()
    ) {}

    public function index(): void
    {
        json_response($this->store->all('teachers'), 200);
    }

    public function show(string $id): void
    {
        $teacher = $this->store->find('teachers', $id);

        if ($teacher === null) {
            error_response('Teacher not found', 404);
            return;
        }

        json_response($teacher, 200);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $teacher = $this->store->create('teachers', $data ?? []);
            json_response($teacher, 201, 'Teacher created successfully');
        } catch (\InvalidArgumentException $exception) {
            error_response($exception->getMessage(), 422);
        }
    }

    public function update(string $id): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        if (!$data) {
            error_response('Invalid data', 422);
        }

        $teacher = $this->store->update('teachers', $id, $data);

        if ($teacher === null) {
            error_response('Teacher not found', 404);
            return;
        }

        json_response($teacher, 200, 'Teacher updated successfully');
    }

    public function destroy(string $id): void
    {
        if (!$this->store->delete('teachers', $id)) {
            error_response('Teacher not found', 404);
            return;
        }

        json_response([], 200, 'Teacher deleted successfully');
    }
}
