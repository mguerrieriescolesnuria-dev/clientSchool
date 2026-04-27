<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Infrastructure\Persistence\SchoolApiStore;

class StudentController
{
    public function __construct(
        private readonly SchoolApiStore $store = new SchoolApiStore()
    ) {}

    public function index(): void
    {
        json_response($this->store->all('students'), 200);
    }

    public function show(string $id): void
    {
        $student = $this->store->find('students', $id);

        if ($student === null) {
            error_response('Student not found', 404);
            return;
        }

        json_response($student, 200);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $student = $this->store->create('students', $data ?? []);
            json_response($student, 201, 'Student created successfully');
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

        $student = $this->store->update('students', $id, $data);

        if ($student === null) {
            error_response('Student not found', 404);
            return;
        }

        json_response($student, 200, 'Student updated successfully');
    }

    public function destroy(string $id): void
    {
        if (!$this->store->delete('students', $id)) {
            error_response('Student not found', 404);
            return;
        }

        json_response([], 200, 'Student deleted successfully');
    }
}
