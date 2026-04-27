<?php

namespace App\Http\Controllers;

use App\Support\SchoolApi;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function landing(): View|RedirectResponse
    {
        if (auth()->check()) {
            return redirect()->route('app.dashboard');
        }

        return view('app.index', [
            'user' => null,
            'apiBaseUrl' => (string) config('school-api.base_url'),
        ]);
    }

    public function index(Request $request): View
    {
        $resource = SchoolApi::isAllowedResource((string) $request->query('resource'))
            ? (string) $request->query('resource')
            : 'students';

        $resources = [
            'students' => $this->fetchResource('students'),
            'teachers' => $this->fetchResource('teachers'),
            'subjects' => $this->fetchResource('subjects'),
        ];

        $editingId = (string) $request->query('edit', '');
        $editing = null;

        foreach ($resources[$resource]['rows'] as $row) {
            if (($row['id'] ?? null) === $editingId) {
                $editing = $row;
                break;
            }
        }

        return view('app.index', [
            'user' => auth()->user(),
            'apiBaseUrl' => (string) config('school-api.base_url'),
            'resource' => $resource,
            'resources' => $resources,
            'fields' => SchoolApi::fieldsFor($resource),
            'editing' => $editing,
        ]);
    }

    public function user(): JsonResponse
    {
        return response()->json([
            'status' => 200,
            'data' => [
                'name' => auth()->user()?->name,
                'email' => auth()->user()?->email,
                'avatar' => auth()->user()?->avatar,
            ],
        ]);
    }

    /**
     * @return array{rows: array<int, array<string, mixed>>, error: string|null}
     */
    private function fetchResource(string $resource): array
    {
        try {
            $response = SchoolApi::client()->get('/api/' . $resource);
            $body = $response->json();

            if (!$response->successful()) {
                return [
                    'rows' => [],
                    'error' => $body['message'] ?? 'No s’han pogut carregar les dades.',
                ];
            }

            return [
                'rows' => $body['data'] ?? [],
                'error' => null,
            ];
        } catch (ConnectionException) {
            return [
                'rows' => [],
                'error' => 'No es pot connectar amb l’API backend.',
            ];
        }
    }
}
