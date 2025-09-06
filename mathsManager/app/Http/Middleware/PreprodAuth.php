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

        // Vérifier si l'utilisateur est déjà authentifié pour la preprod
        if (session('preprod_authenticated')) {
            return $next($request);
        }

        // Si c'est une tentative d'authentification
        if ($request->has('preprod_password')) {
            $password = config('app.preprod_password', 'mathsdev2024');
            
            if ($request->input('preprod_password') === $password) {
                session(['preprod_authenticated' => true]);
                return redirect($request->url());
            } else {
                return response()->view('preprod-auth', ['error' => 'Mot de passe incorrect'])
                    ->setStatusCode(401);
            }
        }

        // Afficher le formulaire d'authentification
        return response()->view('preprod-auth')->setStatusCode(401);
    }
}
