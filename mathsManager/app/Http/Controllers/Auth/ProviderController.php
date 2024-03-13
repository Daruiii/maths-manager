<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function callback($provider)
    {
        try {
            $SocialUser = Socialite::driver($provider)->user();
            if (User::where('email', $SocialUser->email)->exists()) {
                return redirect('/login')->withErrors(['email' => 'This email uses different method to login']);
            }
            $user = User::where([
                'provider' => $provider,
                'provider_id' => $SocialUser->id
            ])->first();
            if (!$user) {
                $user = User::create([
                    'name' => $SocialUser->getName(),
                    'email' => $SocialUser->getEmail(),
                    'provider' => $provider,
                    'provider_id' => $SocialUser->getId(),
                    'provider_token' => $SocialUser->token,
                    'email_verified_at' => now()
                ]);
            }
            Auth::login($user);
            return redirect('/home');
        } catch (\Exception $e) {
            return redirect('/login');
        }
    }
}
