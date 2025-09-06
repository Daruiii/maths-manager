<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\IsAdmin;
use App\Http\Middleware\IsVerified;
use App\Http\Middleware\PreprodAuth;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        // then: function (){
        //     require base_path('routes/chapter.php');
        // }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(
            IsAdmin::class
        );
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(
            IsVerified::class
        );
    })
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(
            PreprodAuth::class
        );
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
