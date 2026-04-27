<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;

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

    public function index(): View
    {
        return view('app.index', [
            'user' => auth()->user(),
            'apiBaseUrl' => (string) config('school-api.base_url'),
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
}
