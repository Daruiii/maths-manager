<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckOnboarding
{
    /**
     * Routes exclues de la vérification d'onboarding.
     * Ces routes sont accessibles même si l'onboarding n'est pas complété.
     */
    private const EXCLUDED_ROUTES = [
        'onboarding.*',
        'profile.*',
        'logout',
        'password.*',
        'verification.*',
        'invitation.*',  // Permettre aux users sans rôle de rejoindre via invitation
    ];

    public function handle(Request $request, Closure $next): mixed
    {
        if (! Auth::check()) {
            return $next($request);
        }

        $user = Auth::user();

        // Exclure les routes onboarding + profil + logout pour éviter les redirect loops
        foreach (self::EXCLUDED_ROUTES as $pattern) {
            if ($request->routeIs($pattern)) {
                return $next($request);
            }
        }

        // Utilisateur banni → logout forcé
        if ($user->isBanned()) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return redirect()->route('login')->withErrors([
                'email' => 'Votre compte a été banni. Contactez un administrateur.',
            ]);
        }

        // Utilisateur sans rôle → choisir son rôle
        if (! $user->role) {
            return redirect()->route('onboarding.role');
        }

        // Professeur en attente de validation
        if ($user->isTeacher() && $user->isPendingApproval()) {
            return redirect()->route('onboarding.pending');
        }

        // Professeur rejeté
        if ($user->isTeacher() && $user->isRejected()) {
            return redirect()->route('onboarding.rejected');
        }

        return $next($request);
    }
}
