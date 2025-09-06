<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PreprodAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Activer uniquement en preprod (APP_ENV=staging)
        if (config('app.env') !== 'staging') {
            return $next($request);
        }

        // Vérifier si l'utilisateur est déjà authentifié pour la preprod via cookie
        $isAuthenticated = $request->cookie('preprod_auth') === 'authenticated';
        
        if ($isAuthenticated) {
            return $next($request);
        }

        // Si c'est une tentative d'authentification
        if ($request->has('preprod_password')) {
            $password = config('app.preprod_password', 'mathsdev2024');
            $inputPassword = $request->input('preprod_password');
            
            if ($inputPassword === $password) {
                // Créer une redirection avec cookie au lieu de session
                $cleanUrl = strtok($request->url(), '?');
                return redirect($cleanUrl)->cookie('preprod_auth', 'authenticated', 60 * 24 * 7); // 7 jours
            } else {
                return response()->view('preprod-auth', ['error' => 'Mot de passe incorrect'])
                    ->setStatusCode(401);
            }
        }

        // Afficher le formulaire d'authentification
        return response()->view('preprod-auth')->setStatusCode(401);
    }
}
