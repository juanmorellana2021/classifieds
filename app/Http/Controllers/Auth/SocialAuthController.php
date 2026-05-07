<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirectToGoogle(): RedirectResponse
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback(): RedirectResponse
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('google_id', $googleUser->id)
            ->orWhere('email', $googleUser->email)
            ->first();

        if (! $user) {
            $user = User::create([
                'name' => $googleUser->name ?: 'Google User',
                'email' => $googleUser->email,
                'email_verified_at' => now(),
                'password' => Hash::make(Str::random(40)),
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
            ]);
        } else {
            $user->update([
                'google_id' => $googleUser->id,
                'avatar' => $googleUser->avatar,
                'email_verified_at' => $user->email_verified_at ?: now(),
            ]);
        }

        Auth::login($user, true);

        return redirect()->intended(route('dashboard', absolute: false));
    }
}
