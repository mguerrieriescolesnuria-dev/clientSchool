<?php

namespace App\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class SchoolApi
{
    /**
     * @var array<string, array<int, string>>
     */
    private const RESOURCE_FIELDS = [
        'students' => ['name', 'email'],
        'teachers' => ['name', 'email'],
        'subjects' => ['name', 'course', 'teacher'],
    ];

    public static function client(): PendingRequest
    {
        return Http::baseUrl((string) config('school-api.base_url'))
            ->acceptJson()
            ->asJson()
            ->timeout((int) config('school-api.timeout'));
    }

    /**
     * @return array<int, string>
     */
    public static function fieldsFor(string $resource): array
    {
        return self::RESOURCE_FIELDS[$resource] ?? [];
    }

    public static function isAllowedResource(string $resource): bool
    {
        return array_key_exists($resource, self::RESOURCE_FIELDS);
    }

    /**
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public static function sanitizePayload(string $resource, array $payload): array
    {
        $sanitized = [];

        foreach (self::fieldsFor($resource) as $field) {
            if (!array_key_exists($field, $payload)) {
                continue;
            }

            $value = is_string($payload[$field]) ? trim($payload[$field]) : $payload[$field];

            if ($value === '') {
                continue;
            }

            $sanitized[$field] = $value;
        }

        return $sanitized;
    }
}
