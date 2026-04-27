<?php

namespace App\Http\Controllers;

use App\Support\SchoolApi;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ResourceController extends Controller
{
    public function store(Request $request, string $resource): RedirectResponse
    {
        return $this->send($request, $resource, 'post');
    }

    public function update(Request $request, string $resource, string $id): RedirectResponse
    {
        return $this->send($request, $resource, 'put', $id);
    }

    public function destroy(string $resource, string $id): RedirectResponse
    {
        if (!SchoolApi::isAllowedResource($resource)) {
            return redirect()->route('app.dashboard')->with('error', 'Recurs no permès.');
        }

        try {
            $response = SchoolApi::client()->delete('/api/' . $resource . '/' . $id);
            $body = $response->json();

            if (!$response->successful()) {
                return redirect()
                    ->route('app.dashboard', ['resource' => $resource])
                    ->with('error', $body['message'] ?? 'No s’ha pogut eliminar el registre.');
            }

            return redirect()
                ->route('app.dashboard', ['resource' => $resource])
                ->with('success', $body['message'] ?? 'Registre eliminat correctament.');
        } catch (ConnectionException) {
            return redirect()
                ->route('app.dashboard', ['resource' => $resource])
                ->with('error', 'No es pot connectar amb l’API backend.');
        }
    }

    private function send(Request $request, string $resource, string $method, ?string $id = null): RedirectResponse
    {
        if (!SchoolApi::isAllowedResource($resource)) {
            return redirect()->route('app.dashboard')->with('error', 'Recurs no permès.');
        }

        $payload = SchoolApi::sanitizePayload($resource, $request->all());
        $endpoint = '/api/' . $resource . ($id ? '/' . $id : '');

        try {
            $response = $method === 'put'
                ? SchoolApi::client()->put($endpoint, $payload)
                : SchoolApi::client()->post($endpoint, $payload);

            $body = $response->json();

            if (!$response->successful()) {
                return redirect()
                    ->route('app.dashboard', ['resource' => $resource, 'edit' => $id])
                    ->withInput()
                    ->with('error', $body['message'] ?? 'No s’ha pogut guardar el registre.');
            }

            return redirect()
                ->route('app.dashboard', ['resource' => $resource])
                ->with('success', $body['message'] ?? 'Canvis guardats correctament.');
        } catch (ConnectionException) {
            return redirect()
                ->route('app.dashboard', ['resource' => $resource])
                ->withInput()
                ->with('error', 'No es pot connectar amb l’API backend.');
        }
    }
}
