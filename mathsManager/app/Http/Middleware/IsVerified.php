<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class IsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Skip verification check for the error page to avoid redirect loop
        if ($request->is('isntValid')) {
            return $next($request);
        }

        if (Auth::check() && Auth::user()->verified == 0) {
            // Redirigez les non-administrateurs vers la page d'erreur
            return redirect('/isntValid');
        }

        return $next($request);
    }
}
