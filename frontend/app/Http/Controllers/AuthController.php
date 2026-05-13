<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Support\SchoolApi;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (!Auth::attempt($credentials, true)) {
            return back()
                ->withInput($request->only('email'))
                ->withErrors([
                    'login' => 'Credencials incorrectes. Revisa l’email i la contrasenya.',
                ]);
        }

        $request->session()->regenerate();

        return redirect()->route('app.dashboard');
    }

    public function register(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
        ]);

        $user = User::query()->create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => now(),
        ]);

        Auth::login($user, true);
        $request->session()->regenerate();

        return redirect()->route('app.dashboard');
    }

    public function redirectToGoogle(): RedirectResponse
    {
        return redirect()->away(rtrim((string) config('school-api.base_url'), '/') . '/auth/google');
    }

    public function oauthCallback(Request $request): RedirectResponse
    {
        $token = (string) $request->query('token', '');

        if ($token === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'No s’ha rebut cap token d’OAuth.',
            ]);
        }

        try {
            $response = SchoolApi::client()
                ->withToken($token)
                ->get('/auth/me');
        } catch (ConnectionException) {
            return redirect()->route('login')->withErrors([
                'login' => 'No es pot connectar amb el backend OAuth.',
            ]);
        }

        $body = $response->json();

        if (!$response->successful() || !is_array($body['data'] ?? null)) {
            return redirect()->route('login')->withErrors([
                'login' => $body['message'] ?? 'El token d’OAuth no és vàlid.',
            ]);
        }

        $oauthUser = $body['data'];
        $email = (string) ($oauthUser['email'] ?? '');

        if ($email === '') {
            return redirect()->route('login')->withErrors([
                'login' => 'Google no ha retornat un email vàlid.',
            ]);
        }

        $user = User::query()->firstOrNew(['email' => $email]);
        $user->name = (string) ($oauthUser['name'] ?? $email);
        $user->email = $email;
        $user->password = $user->password ?: Hash::make(Str::random(32));
        $user->oauth_provider = (string) ($oauthUser['provider'] ?? 'google');
        $user->oauth_id = (string) ($oauthUser['sub'] ?? '');
        $user->avatar = isset($oauthUser['avatar']) ? (string) $oauthUser['avatar'] : $user->avatar;
        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->save();

        Auth::login($user, true);
        $request->session()->regenerate();
        $request->session()->put('api_token', $token);

        return redirect()->route('app.dashboard')->with('success', 'Sessió iniciada amb Google correctament.');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->forget('api_token');
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
