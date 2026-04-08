<?php

namespace Tests\Feature\Services;

use App\Http\Requests\Td\AssignTdRequest;
use App\Models\PrivateExercise;
use App\Models\Td;
use App\Models\TdBatch;
use App\Models\User;
use App\Services\BatchAssignmentService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class BatchAssignmentServiceTest extends TestCase
{
    use RefreshDatabase;

    private BatchAssignmentService $service;
    private User $teacher;

    protected function setUp(): void
    {
        parent::setUp();
        Mail::fake();
        $this->service = app(BatchAssignmentService::class);
        $this->teacher = User::factory()->create(['role' => 'teacher', 'status' => 'active']);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // assignTd
    // ──────────────────────────────────────────────────────────────────────────

    public function test_assignTd_returns_student_count(): void
    {
        $students = User::factory()->count(3)->create();
        $exercise = $this->makePrivateExercise($this->teacher->id, 'basic');

        $request = $this->makeTdRequest(
            privateExerciseIds: [$exercise->id],
            studentIds: $students->pluck('id')->all(),
        );

        $count = $this->service->assignTd($request, $this->teacher);

        $this->assertSame(3, $count);
    }

    public function test_assignTd_creates_one_td_per_student(): void
    {
        $students = User::factory()->count(2)->create();
        $exercise  = $this->makePrivateExercise($this->teacher->id, 'basic');

        $request = $this->makeTdRequest(
            privateExerciseIds: [$exercise->id],
            studentIds: $students->pluck('id')->all(),
        );

        $this->service->assignTd($request, $this->teacher);

        $this->assertDatabaseCount('td', 2);
        foreach ($students as $student) {
            $this->assertDatabaseHas('td', [
                'teacher_id' => $this->teacher->id,
                'user_id'    => $student->id,
            ]);
        }
    }

    public function test_assignTd_creates_a_td_batch(): void
    {
        $student  = User::factory()->create();
        $exercise = $this->makePrivateExercise($this->teacher->id, 'basic');

        $request = $this->makeTdRequest(
            privateExerciseIds: [$exercise->id],
            studentIds: [$student->id],
        );

        $this->service->assignTd($request, $this->teacher);

        $this->assertDatabaseCount('td_batches', 1);
        $this->assertDatabaseHas('td_batches', ['teacher_id' => $this->teacher->id, 'td_count' => 1]);
    }

    public function test_assignTd_ignores_private_exercises_from_other_teacher(): void
    {
        $otherTeacher   = User::factory()->create(['role' => 'teacher']);
        $foreignExercise = $this->makePrivateExercise($otherTeacher->id, 'basic');
        $student = User::factory()->create();

        $request = $this->makeTdRequest(
            privateExerciseIds: [$foreignExercise->id],
            studentIds: [$student->id],
        );

        // L'exercice appartient à un autre prof → aucun TD ne doit avoir cet exercice attaché
        $this->service->assignTd($request, $this->teacher);

        $td = Td::first();
        $this->assertNotNull($td);
        $this->assertEmpty($td->privateExercises);
    }

    public function test_assignTd_rejects_non_basic_private_exercises(): void
    {
        $exercise = $this->makePrivateExercise($this->teacher->id, 'problem');
        $student  = User::factory()->create();

        $request = $this->makeTdRequest(
            privateExerciseIds: [$exercise->id],
            studentIds: [$student->id],
        );

        $this->service->assignTd($request, $this->teacher);

        $td = Td::first();
        $this->assertNotNull($td);
        $this->assertEmpty($td->privateExercises);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers
    // ──────────────────────────────────────────────────────────────────────────

    private function makePrivateExercise(int $teacherId, string $type): PrivateExercise
    {
        return PrivateExercise::create([
            'teacher_id'       => $teacherId,
            'type'             => $type,
            'name'             => 'Test exercise',
            'latex_statement'  => '',
        ]);
    }

    private function makeTdRequest(array $privateExerciseIds = [], array $studentIds = []): AssignTdRequest
    {
        $request = new AssignTdRequest();
        $request->merge([
            'private_exercise_ids' => $privateExerciseIds,
            'student_ids'          => $studentIds,
        ]);
        return $request;
    }
}
