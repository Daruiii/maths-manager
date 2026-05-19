<?php

namespace App\Http\Controllers\Auth;

use Laravel\Socialite\Facades\Socialite;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\RedirectResponse as SymfonyRedirectResponse;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ProviderController extends Controller
{
    public function redirect(Request $request, string $provider): SymfonyRedirectResponse
    {
        // Stocker le redirect pour après le callback OAuth (clé explicite pour survivre au flow OAuth)
        if ($request->query('redirect')) {
            session(['oauth_redirect' => $request->query('redirect')]);
        }

        return Socialite::driver($provider)
                    ->with(['prompt' => 'select_account'])
                    ->redirect();
    }

    public function callback(string $provider): RedirectResponse
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
                if ($provider === 'google') {
                    $avatar = $SocialUser->avatar_original;
                } else {
                    $avatar = $SocialUser->avatar;
                }

                // Split name safely
                $fullName = $SocialUser->getName() ?? $SocialUser->getNickname() ?? 'Unknown User';
                $nameParts = explode(' ', $fullName, 2);
                $firstName = $nameParts[0];
                $lastName = $nameParts[1] ?? '';

                $user = User::create([
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $SocialUser->getEmail(),
                    'avatar' => $avatar,
                    'provider' => $provider,
                    'provider_id' => $SocialUser->getId(),
                    'provider_token' => $SocialUser->token,
                    'email_verified_at' => now()
                ]);
            }
        }

        // Connectez l'utilisateur
        Auth::login($user);

        // Récupérer le redirect stocké avant le flow OAuth
        $redirectUrl = session()->pull('oauth_redirect', '/home');

        return redirect($redirectUrl);
    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Socialite Login Error: ' . $e->getMessage());
        return redirect('/login')->withErrors(['email' => 'Authentication failed. Please try again.']); // Give feedback
    }
}
}
