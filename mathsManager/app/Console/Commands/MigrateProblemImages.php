<?php

namespace App\Console\Commands;

use App\Models\Problem;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

/**
 * Commande de migration des images de problems
 *
 * Fait suite au refacto ds_exercises → problems.
 * Déplace les fichiers de storage/app/public/ds-exercises/ → problems/
 * et met à jour les paths dans la colonne `statement` de la table `problems`.
 *
 * Usage :
 *   php artisan migrate:problem-images --dry-run   (voir ce qui serait fait)
 *   php artisan migrate:problem-images             (migration réelle)
 */
class MigrateProblemImages extends Command
{
    protected $signature = 'migrate:problem-images {--dry-run : Simule la migration sans rien modifier}';

    protected $description = 'Migre les images des problems de ds-exercises/ vers problems/ (suite au refacto)';

    public function handle(): int
    {
        $isDryRun = $this->option('dry-run');

        if ($isDryRun) {
            $this->warn('🔍 DRY-RUN — aucune modification ne sera effectuée.');
        } else {
            $this->info('🚀 Migration réelle — les fichiers et la DB seront modifiés.');
        }

        $problems = Problem::whereNotNull('statement')
            ->where('statement', 'like', '%ds-exercises%')
            ->get();

        if ($problems->isEmpty()) {
            $this->info('✅ Aucun problem avec des images sous ds-exercises/. Rien à faire.');
            return self::SUCCESS;
        }

        $this->info("📦 {$problems->count()} problem(s) à migrer.");
        $this->newLine();

        $movedFiles = 0;
        $updatedProblems = 0;

        foreach ($problems as $problem) {
            $oldIdentifier = "ds-exercises/problem-{$problem->id}";
            $newIdentifier = "problems/problem-{$problem->id}";

            $this->line("  Problem #{$problem->id} : {$oldIdentifier} → {$newIdentifier}");

            // 1. Déplacer les fichiers dans le storage
            $files = Storage::disk('public')->files($oldIdentifier);

            if (empty($files)) {
                $this->warn("    ⚠️  Aucun fichier trouvé dans {$oldIdentifier}");
            }

            foreach ($files as $file) {
                $filename = basename($file);
                $newPath = "{$newIdentifier}/{$filename}";

                $this->line("    📂 {$file} → {$newPath}");

                if (! $isDryRun) {
                    Storage::disk('public')->move($file, $newPath);
                }

                $movedFiles++;
            }

            // 2. Mettre à jour les chemins dans statement
            $oldStatement = $problem->statement;
            $newStatement = str_replace(
                "/storage/{$oldIdentifier}/",
                "/storage/{$newIdentifier}/",
                $oldStatement
            );

            if ($oldStatement !== $newStatement) {
                $this->line("    ✏️  Mise à jour statement (chemins images)");

                if (! $isDryRun) {
                    $problem->statement = $newStatement;
                    $problem->save();
                }

                $updatedProblems++;
            }

            // 3. Supprimer l'ancien dossier vide
            if (! $isDryRun && ! empty($files)) {
                Storage::disk('public')->deleteDirectory($oldIdentifier);
                $this->line("    🗑️  Ancien dossier supprimé : {$oldIdentifier}");
            }
        }

        $this->newLine();

        if ($isDryRun) {
            $this->warn("🔍 DRY-RUN terminé — {$movedFiles} fichier(s) auraient été déplacés, {$updatedProblems} problem(s) mis à jour.");
        } else {
            $this->info("✅ Migration terminée — {$movedFiles} fichier(s) déplacés, {$updatedProblems} problem(s) mis à jour.");
        }

        return self::SUCCESS;
    }
}
