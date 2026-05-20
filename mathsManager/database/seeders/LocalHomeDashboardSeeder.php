<?php

namespace Database\Seeders;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Enums\DSStatus;
use App\Enums\TdStatus;
use App\Models\CorrectionRequest;
use App\Models\Dm;
use App\Models\DmBatch;
use App\Models\DS;
use App\Models\DsBatch;
use App\Models\StudentGroup;
use App\Models\Td;
use App\Models\TdBatch;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Hash;

class LocalHomeDashboardSeeder extends Seeder
{
    private const TEACHER_EMAIL = 'davidmgr6@icloud.com';
    private const STUDENT_EMAIL = 'davidmeguira6@gmail.com';

    public function run(): void
    {
        abort_unless(App::environment(['local', 'development', 'testing']), 403, 'Local-only seeder.');

        $teacher = User::where('email', self::TEACHER_EMAIL)->firstOrFail();
        $students = $this->studentsFor($teacher);

        $group = StudentGroup::updateOrCreate(
            ['teacher_id' => $teacher->id, 'name' => '[Demo] Terminale dashboard'],
            ['teacher_id' => $teacher->id]
        );

        $students->each(fn (User $student) => $student->update([
            'teacher_id' => $teacher->id,
            'group_id' => $group->id,
            'status' => 'active',
        ]));

        $this->seedDs($teacher, $students, $group);
        $this->seedDm($teacher, $students, $group);
        $this->seedTd($teacher, $students, $group);

        $this->command?->info('[Demo] Home dashboard data ready.');
    }

    private function studentsFor(User $teacher)
    {
        $primary = User::where('email', self::STUDENT_EMAIL)->firstOrFail();

        $extra = collect([
            ['first_name' => 'Léo', 'last_name' => 'Martin', 'email' => 'demo.student.leo@mathsmanager.test'],
            ['first_name' => 'Sarah', 'last_name' => 'Durand', 'email' => 'demo.student.sarah@mathsmanager.test'],
            ['first_name' => 'Inès', 'last_name' => 'Moreau', 'email' => 'demo.student.ines@mathsmanager.test'],
        ])->map(fn (array $student) => User::updateOrCreate(
            ['email' => $student['email']],
            [
                ...$student,
                'password' => Hash::make('password'),
                'role' => 'student',
                'status' => 'active',
                'teacher_id' => $teacher->id,
            ]
        ));

        return collect([$primary])->merge($extra)->values();
    }

    private function seedDs(User $teacher, $students, StudentGroup $group): void
    {
        $batch = DsBatch::updateOrCreate(
            ['teacher_id' => $teacher->id, 'group_ids' => [$group->id], 'student_ids' => $students->pluck('id')->all()],
            ['ds_count' => $students->count(), 'due_date' => now()->addDays(5)->toDateString()]
        );

        $statuses = [
            DSStatus::Ongoing->value,
            DSStatus::Sent->value,
            DSStatus::Corrected->value,
            DSStatus::NotStarted->value,
        ];

        $students->each(function (User $student, int $index) use ($teacher, $batch, $statuses) {
            $ds = DS::updateOrCreate(
                ['user_id' => $student->id, 'teacher_id' => $teacher->id, 'batch_id' => $batch->id],
                [
                    'custom_title' => '[Demo] DS - Fonctions',
                    'custom_level' => 'Terminale',
                    'custom_instructions' => 'Exemple de DS pour tester la home.',
                    'type_bac' => false,
                    'exercises_number' => 3,
                    'harder_exercises' => false,
                    'time' => 90,
                    'timer' => 5400,
                    'chrono' => false,
                    'status' => $statuses[$index] ?? DSStatus::NotStarted->value,
                    'started_at' => null,
                ]
            );

            if ($ds->status === DSStatus::Sent->value) {
                $this->pendingCorrection($student, $ds, '[Demo] Ma copie de DS.');
            }

            if ($ds->status === DSStatus::Corrected->value) {
                $this->correctedCorrection($teacher, $student, $ds, '[Demo] Correction DS envoyée.', 15);
            }
        });
    }

    private function seedDm(User $teacher, $students, StudentGroup $group): void
    {
        $batch = DmBatch::updateOrCreate(
            ['teacher_id' => $teacher->id, 'group_ids' => [$group->id], 'student_ids' => $students->pluck('id')->all()],
            ['dm_count' => $students->count(), 'due_date' => now()->addDay()->toDateString()]
        );

        $statuses = [
            DmStatus::Ongoing,
            DmStatus::Finished,
            DmStatus::Corrected,
            DmStatus::NotStarted,
        ];

        $students->each(function (User $student, int $index) use ($teacher, $batch, $statuses) {
            $dm = Dm::updateOrCreate(
                ['user_id' => $student->id, 'teacher_id' => $teacher->id, 'batch_id' => $batch->id],
                [
                    'status' => $statuses[$index] ?? DmStatus::NotStarted,
                    'custom_title' => '[Demo] DM - Suites numériques',
                    'custom_level' => 'Terminale',
                    'custom_instructions' => 'Exemple de DM pour tester la home.',
                ]
            );

            if ($dm->status === DmStatus::Finished) {
                $this->pendingCorrection($student, $dm, '[Demo] Ma copie de DM.');
            }

            if ($dm->status === DmStatus::Corrected) {
                $this->correctedCorrection($teacher, $student, $dm, '[Demo] Correction DM envoyée.', 17);
            }
        });
    }

    private function seedTd(User $teacher, $students, StudentGroup $group): void
    {
        $batch = TdBatch::updateOrCreate(
            ['teacher_id' => $teacher->id, 'group_ids' => [$group->id], 'student_ids' => $students->pluck('id')->all()],
            ['td_count' => $students->count(), 'due_date' => now()->addDays(3)->toDateString()]
        );

        $statuses = [
            TdStatus::NotStarted,
            TdStatus::CorrectionRequested,
            TdStatus::Ongoing,
            TdStatus::CorrectionUnlocked,
        ];

        $students->each(fn (User $student, int $index) => Td::updateOrCreate(
            ['user_id' => $student->id, 'teacher_id' => $teacher->id, 'batch_id' => $batch->id],
            [
                'status' => $statuses[$index] ?? TdStatus::NotStarted,
                'custom_title' => '[Demo] TD - Géométrie',
                'custom_level' => 'Terminale',
                'custom_instructions' => 'Exemple de TD pour tester la home.',
                'correction_unlocked' => ($statuses[$index] ?? TdStatus::NotStarted) === TdStatus::CorrectionUnlocked,
            ]
        ));
    }

    private function pendingCorrection(User $student, DS|Dm $assignment, string $message): void
    {
        CorrectionRequest::updateOrCreate(
            $this->correctionIdentity($student, $assignment),
            [
                'pictures' => ['demo/student-copy.jpg'],
                'correction_pictures' => null,
                'message' => $message,
                'correction_message' => null,
                'grade' => null,
                'status' => CorrectionRequestStatus::Pending->value,
            ]
        );
    }

    private function correctedCorrection(
        User $teacher,
        User $student,
        DS|Dm $assignment,
        string $message,
        int $grade
    ): void {
        CorrectionRequest::updateOrCreate(
            $this->correctionIdentity($student, $assignment),
            [
                'corrector_id' => $teacher->id,
                'pictures' => ['demo/student-copy.jpg'],
                'correction_pictures' => ['demo/teacher-correction.jpg'],
                'message' => '[Demo] Copie envoyée.',
                'correction_message' => $message,
                'grade' => $grade,
                'status' => CorrectionRequestStatus::Corrected->value,
            ]
        );
    }

    private function correctionIdentity(User $student, DS|Dm $assignment): array
    {
        return [
            'user_id' => $student->id,
            'ds_id' => $assignment instanceof DS ? $assignment->id : null,
            'dm_id' => $assignment instanceof Dm ? $assignment->id : null,
        ];
    }
}
