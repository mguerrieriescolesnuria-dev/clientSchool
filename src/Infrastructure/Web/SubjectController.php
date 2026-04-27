<?php

declare(strict_types=1);

namespace App\Infrastructure\Web;

use App\Infrastructure\Persistence\SchoolApiStore;

class SubjectController
{
    public function __construct(
        private readonly SchoolApiStore $store = new SchoolApiStore()
    ) {}

    public function index(): void
    {
        json_response($this->store->all('subjects'), 200);
    }

    public function show(string $id): void
    {
        $subject = $this->store->find('subjects', $id);

        if ($subject === null) {
            error_response('Subject not found', 404);
            return;
        }

        json_response($subject, 200);
    }

    public function store(): void
    {
        $data = json_decode(file_get_contents('php://input'), true);

        try {
            $subject = $this->store->create('subjects', $data ?? []);
            json_response($subject, 201, 'Subject created successfully');
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

        $subject = $this->store->update('subjects', $id, $data);

        if ($subject === null) {
            error_response('Subject not found', 404);
            return;
        }

        json_response($subject, 200, 'Subject updated successfully');
    }

    public function destroy(string $id): void
    {
        if (!$this->store->delete('subjects', $id)) {
            error_response('Subject not found', 404);
            return;
        }

        json_response([], 200, 'Subject deleted successfully');
    }
}
