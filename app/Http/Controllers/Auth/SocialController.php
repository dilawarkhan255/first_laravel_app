<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Http\RedirectResponse;

class SocialController extends Controller
{
    public function redirect($provider)
    {
     return Socialite::driver($provider)->redirect();
    }

    public function callBack($provider){
        $userSocial =   Socialite::driver($provider)->stateless()->user();
        $users       =   User::where(['email' => $userSocial->getEmail()])->first();
        if($users){
                    Auth::login($users);
                    // return redirect('/jobs/home');
                }
                else
                {
                    $users = User::create([
                        'name' =>  $userSocial->getName(),
                        'email' => $userSocial->getEmail(),
                        'provider' => $provider,
                        'provider_id' =>  $userSocial->getId(),
                        'password' => Hash::make(Str::random(24)),
                    ]);
                    Auth::login($users);
                }
                return redirect()->route('jobs.home');
            }
}
