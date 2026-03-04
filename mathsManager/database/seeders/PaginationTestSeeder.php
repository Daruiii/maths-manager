<?php

namespace Database\Seeders;

use App\Models\StudentGroup;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Seeder de test pour la pagination de la page "Mes Élèves".
 * Cible le prof david@gmail.com en local.
 *
 * Usage :
 *   php artisan db:seed --class=PaginationTestSeeder
 *
 * Idempotent — ne duplique pas si relancé.
 */
class PaginationTestSeeder extends Seeder
{
    // 3 groupes + 45 élèves non groupés = 60 élèves total → 3 pages (PAGE_SIZE = 20)
    private const GROUPS = [
        ['name' => 'Terminale S1', 'students' => 8],
        ['name' => 'Première S2',  'students' => 5],
        ['name' => 'Seconde A',    'students' => 6],
    ];
    private const UNGROUPED_COUNT = 45;

    public function run(): void
    {
        $teacher = User::where('email', 'david@gmail.com')->first();

        if (! $teacher) {
            $this->command->error('Utilisateur david@gmail.com introuvable en base.');
            return;
        }

        $this->command->info("✓ Prof : {$teacher->first_name} {$teacher->last_name} (id={$teacher->id})");

        // --- Groupes + élèves groupés ---
        foreach (self::GROUPS as $data) {
            $group = StudentGroup::firstOrCreate([
                'teacher_id' => $teacher->id,
                'name'       => $data['name'],
            ]);

            $existing = $group->students()->count();
            $toCreate = max(0, $data['students'] - $existing);

            if ($toCreate > 0) {
                User::factory($toCreate)->create([
                    'teacher_id' => $teacher->id,
                    'group_id'   => $group->id,
                ]);
            }

            $this->command->line("  ✓ Groupe « {$group->name} » → {$group->students()->count()} élèves");
        }

        // --- Élèves non groupés (liés au prof via teacher_id, group_id null) ---
        $existingUngrouped = User::where('teacher_id', $teacher->id)
            ->whereNull('group_id')
            ->count();

        $toCreate = max(0, self::UNGROUPED_COUNT - $existingUngrouped);

        if ($toCreate > 0) {
            User::factory($toCreate)->create([
                'teacher_id' => $teacher->id,
                'group_id'   => null,
            ]);
        }

        $finalUngrouped = User::where('teacher_id', $teacher->id)->whereNull('group_id')->count();
        $this->command->line("  ✓ Élèves non groupés → {$finalUngrouped}");

        $this->command->newLine();
        $this->command->info('Pagination : 45 élèves non groupés → 3 pages (PAGE_SIZE=20)');
        $this->command->info('→ http://localhost/teacher/students');
    }
}
