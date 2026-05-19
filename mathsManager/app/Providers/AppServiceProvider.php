<?php

namespace App\Providers;

use App\Models\Dm;
use App\Models\Exercise;
use App\Models\Problem;
use App\Models\PrivateExercise;
use App\Models\TeacherTag;
use App\Models\User;
use App\Models\StudentGroup;
use App\Observers\ExerciseObserver;
use App\Observers\ProblemObserver;
use App\Observers\PrivateExerciseObserver;
use App\Policies\DmPolicy;
use App\Policies\PrivateExercisePolicy;
use App\Policies\StudentGroupPolicy;
use App\Policies\TeacherStudentPolicy;
use App\Policies\TeacherTagPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;
use RuntimeException;
use View;
use App\Models\Classe;
use App\Models\DS;
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
        $this->guardAgainstUnsafeDatabaseCommands();

        // ─── Observers ─────────────────────────────────────────────────────────
        PrivateExercise::observe(PrivateExerciseObserver::class);
        Exercise::observe(ExerciseObserver::class);
        Problem::observe(ProblemObserver::class);

        // ─── Policies ──────────────────────────────────────────────────────────
        Gate::policy(Dm::class, DmPolicy::class);
        Gate::policy(StudentGroup::class, StudentGroupPolicy::class);
        Gate::policy(User::class, TeacherStudentPolicy::class);
        Gate::policy(PrivateExercise::class, PrivateExercisePolicy::class);
        Gate::policy(TeacherTag::class, TeacherTagPolicy::class);

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
                $tdNotStarted = 0;

                // Charger les données seulement si les tables existent
                if ($this->tableExists('classes') && $this->tableExists('ds') && $this->tableExists('td')) {
                    try {
                        $classes = Classe::orderBy('display_order')->get();

                        // Compter seulement si l'utilisateur est connecté
                        if (auth()->check() && auth()->id()) {
                            $dsNotStarted = DS::where('user_id', auth()->id())->where('status', 'not_started')->count();
                            $tdNotStarted = \App\Models\Td::where('user_id', auth()->id())->where('correction_unlocked', false)->count();
                        }
                    } catch (\Exception $e) {
                        // Ignorer les erreurs et garder les valeurs par défaut
                    }
                }

                $view->with('classes', $classes);
                $view->with('dsNotStarted', $dsNotStarted);
                $view->with('tdNotStarted', $tdNotStarted);
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

    /**
     * Prevent destructive commands from targeting non-test databases.
     */
    private function guardAgainstUnsafeDatabaseCommands(): void
    {
        if (!app()->runningInConsole()) {
            return;
        }

        $argv = $_SERVER['argv'] ?? [];
        $command = $this->extractArtisanCommand($argv);

        if (!$command) {
            return;
        }

        $dangerousCommands = ['test', 'migrate:fresh', 'migrate:refresh', 'db:wipe'];
        if (!in_array($command, $dangerousCommands, true)) {
            return;
        }

        $forcedEnv = null;
        foreach ($argv as $arg) {
            if (str_starts_with($arg, '--env=')) {
                $forcedEnv = substr($arg, 6);
                break;
            }
        }

        $effectiveEnv = $forcedEnv ?: app()->environment();
        $connection = (string) config('database.default');
        $database = (string) config("database.connections.{$connection}.database");

        if ($connection === 'sqlite') {
            return;
        }

        $databaseLooksLikeTest = str_contains(strtolower($database), 'test');

        if ($command === 'test') {
            if ($effectiveEnv !== 'testing') {
                throw new RuntimeException(
                    "Blocked unsafe `artisan test`: env='{$effectiveEnv}'. Use `--env=testing`."
                );
            }

            return;
        }

        if (!$databaseLooksLikeTest) {
            throw new RuntimeException(
                "Blocked unsafe `artisan {$command}` on non-test database '{$database}'."
            );
        }
    }

    private function extractArtisanCommand(array $argv): ?string
    {
        foreach ($argv as $arg) {
            if ($arg === 'artisan') {
                continue;
            }

            if (str_starts_with($arg, '-')) {
                continue;
            }

            return $arg;
        }

        return null;
    }
}
