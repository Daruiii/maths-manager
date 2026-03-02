<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsTeacher
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user || $user->role !== 'teacher' || $user->status !== 'active') {
            abort(403, 'Accès réservé aux professeurs validés.');
        }

        return $next($request);
    }
}
