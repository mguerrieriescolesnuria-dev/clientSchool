<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class AuthController extends Controller
{
    public function redirect(): RedirectResponse
    {
        return Socialite::driver('github')
            ->scopes(['read:user', 'user:email'])
            ->redirect();
    }

    public function callback(): RedirectResponse
    {
        $githubUser = Socialite::driver('github')->user();

        $user = User::query()->updateOrCreate(
            [
                'oauth_provider' => 'github',
                'oauth_id' => (string) $githubUser->getId(),
            ],
            [
                'name' => $githubUser->getName() ?: $githubUser->getNickname() ?: 'GitHub User',
                'email' => $githubUser->getEmail() ?: 'github-' . $githubUser->getId() . '@local.test',
                'avatar' => $githubUser->getAvatar(),
                'email_verified_at' => now(),
                'password' => Str::password(32),
            ]
        );

        Auth::login($user, true);
        request()->session()->regenerate();

        return redirect()->route('app.dashboard');
    }

    public function logout(): RedirectResponse
    {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();

        return redirect()->route('login');
    }
}
