<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use View;
use App\Models\Classe;
use App\Models\DS;
use App\Models\ExercisesSheet;
use App\Models\Content;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        View::composer('layouts.app', function ($view) {
            $view->with('classes', Classe::all());
            // count dsNotStarted for user connected
            $dsNotStarted = DS::where('user_id', auth()->id())->where('status', 'not_started')->count();
            $view->with('dsNotStarted', $dsNotStarted);
            // get exercices sheet from user connected where status = not_started and count them
            $exercisesSheetNotStarted = ExercisesSheet::where('user_id', auth()->id())->where('status', 'not_started')->count();
            $view->with('exercisesSheetNotStarted', $exercisesSheetNotStarted);
            $introContent = Content::where('section', 'home_guest_intro')->first();
            $whoamiContent = Content::where('section', 'home_guest_whoami')->first();
            $view->with('introContent', $introContent);
            $view->with('whoamiContent', $whoamiContent);
        });
    }
}
