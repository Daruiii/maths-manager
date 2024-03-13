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
        return Socialite::driver($provider)
                    ->with(['prompt' => 'select_account'])
                    ->redirect();
    }

    public function callback($provider)
{
    try {
        $SocialUser = Socialite::driver($provider)->user();

        // Recherchez d'abord un utilisateur avec le même provider et provider_id.
        $user = User::where('provider', $provider)
                    ->where('provider_id', $SocialUser->id)
                    ->first();

        // Si aucun utilisateur n'est trouvé par provider et provider_id, vérifiez par email.
        if (!$user) {
            $user = User::where('email', $SocialUser->email)->first();
            // Si un utilisateur est trouvé par email mais a utilisé une méthode différente pour s'inscrire, renvoyez une erreur.
            if ($user && ($user->provider !== $provider)) {
                return redirect('/login')->withErrors(['email' => 'This email uses different method to login.']);
            }

            // Si aucun utilisateur n'est trouvé par email, créez un nouveau compte.
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
        }

        // Connectez l'utilisateur
        Auth::login($user);
        return redirect('/home');
    } catch (\Exception $e) {
        return redirect('/login');
    }
}
}
