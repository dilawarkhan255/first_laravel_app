<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;

class SocialController extends Controller
{
    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        try {
            $user = Socialite::driver($provider)->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors('Unable to authenticate.');
        }

        $existingUser = User::where('email', $user->getEmail())->first();
        if ($existingUser) {
            // Update provider ID and provider name if needed
            $existingUser->update([
                'provider_id' => $user->getId(),
                'provider' => $provider,
            ]);
            Auth::login($existingUser);
        } else {
            // Create a new user
            $newUser = User::create([
                'name' => $user->getName(),
                'email' => $user->getEmail(),
                'provider_id' => $user->getId(),
                'provider' => $provider,
            ]);
            Auth::login($newUser);
        }

        return redirect()->intended('/home');
    }
}
