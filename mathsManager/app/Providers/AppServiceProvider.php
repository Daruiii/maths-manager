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
        // Vérifier si les tables existent avant d'exécuter les requêtes
        try {
            // Vérifier si la table contents existe
            if ($this->tableExists('contents')) {
                $introContent = Content::where('section', 'home_guest_intro')->first();
                $whoamiContent = Content::where('section', 'home_guest_whoami')->first();

                View::composer('home', function ($view) use ($introContent, $whoamiContent) {
                    $view->with('introContent', $introContent);
                    $view->with('whoamiContent', $whoamiContent);
                });
            }

            // Toujours composer les vues avec des valeurs par défaut
            View::composer('layouts.app', function ($view) {
                // Initialiser avec des valeurs par défaut
                $classes = collect();
                $dsNotStarted = 0;
                $exercisesSheetNotStarted = 0;
                
                // Charger les données seulement si les tables existent
                if ($this->tableExists('classes') && $this->tableExists('DS') && $this->tableExists('exercises_sheet')) {
                    try {
                        $classes = Classe::orderBy('display_order')->get();
                        
                        // Compter seulement si l'utilisateur est connecté
                        if (auth()->check() && auth()->id()) {
                            $dsNotStarted = DS::where('user_id', auth()->id())->where('status', 'not_started')->count();
                            $exercisesSheetNotStarted = ExercisesSheet::where('user_id', auth()->id())->where('status', 'not_started')->count();
                        }
                    } catch (\Exception $e) {
                        // Ignorer les erreurs et garder les valeurs par défaut
                    }
                }
                
                $view->with('classes', $classes);
                $view->with('dsNotStarted', $dsNotStarted);
                $view->with('exercisesSheetNotStarted', $exercisesSheetNotStarted);
            });
        } catch (\Exception $e) {
            // Ignorer les erreurs de base de données pendant l'installation
        }
    }

    /**
     * Check if a table exists in the database
     */
    private function tableExists(string $table): bool
    {
        try {
            return \Schema::hasTable($table);
        } catch (\Exception $e) {
            return false;
        }
    }
}
