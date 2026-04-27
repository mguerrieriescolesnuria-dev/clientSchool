<?php

namespace App\Http\Controllers;

use App\Support\SchoolApi;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ApiProxyController extends Controller
{
    public function index(string $resource): JsonResponse
    {
        return $this->forward('get', $resource);
    }

    public function show(string $resource, string $id): JsonResponse
    {
        return $this->forward('get', $resource, $id);
    }

    public function store(Request $request, string $resource): JsonResponse
    {
        return $this->forward('post', $resource, null, $request->all());
    }

    public function update(Request $request, string $resource, string $id): JsonResponse
    {
        return $this->forward('put', $resource, $id, $request->all());
    }

    public function destroy(string $resource, string $id): JsonResponse
    {
        return $this->forward('delete', $resource, $id);
    }

    /**
     * @param array<string, mixed> $payload
     */
    private function forward(string $method, string $resource, ?string $id = null, array $payload = []): JsonResponse
    {
        if (!SchoolApi::isAllowedResource($resource)) {
            return response()->json([
                'status' => 404,
                'message' => 'Resource not found',
            ], 404);
        }

        $endpoint = '/api/' . $resource . ($id ? '/' . $id : '');
        $sanitizedPayload = SchoolApi::sanitizePayload($resource, $payload);

        $response = match ($method) {
            'post' => SchoolApi::client()->post($endpoint, $sanitizedPayload),
            'put' => SchoolApi::client()->put($endpoint, $sanitizedPayload),
            'delete' => SchoolApi::client()->delete($endpoint),
            default => SchoolApi::client()->get($endpoint),
        };

        return $this->toJsonResponse($response);
    }

    private function toJsonResponse(Response $response): JsonResponse
    {
        return response()->json($response->json(), $response->status());
    }
}
