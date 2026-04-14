<?php

namespace Tests\Feature\Services;

use App\Models\DmBatch;
use App\Models\DsBatch;
use App\Models\StudentGroup;
use App\Models\TeacherInvitation;
use App\Models\User;
use App\Services\BureauActivityService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BureauActivityServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_builds_sorted_activities_for_the_given_teacher_only(): void
    {
        $service = app(BureauActivityService::class);

        $teacher = User::factory()->create(['role' => 'teacher', 'status' => 'active']);
        $otherTeacher = User::factory()->create(['role' => 'teacher', 'status' => 'active']);

        $group = StudentGroup::create([
            'teacher_id' => $teacher->id,
            'name' => 'Terminale A',
        ]);

        $dsBatch = DsBatch::create([
            'teacher_id' => $teacher->id,
            'group_ids' => [$group->id],
            'student_ids' => [],
            'ds_count' => 2,
        ]);
        $dsBatch->forceFill([
            'created_at' => now()->subHours(3),
            'updated_at' => now()->subHours(3),
        ])->save();

        $invitation = TeacherInvitation::create([
            'teacher_id' => $teacher->id,
            'group_id' => $group->id,
            'code' => 'ABC123',
            'expires_at' => now()->addDays(7),
            'max_uses' => 25,
            'current_uses' => 0,
            'is_active' => true,
        ]);
        $invitation->forceFill([
            'created_at' => now()->subHours(1),
            'updated_at' => now()->subHours(1),
        ])->save();

        DmBatch::create([
            'teacher_id' => $otherTeacher->id,
            'group_ids' => null,
            'student_ids' => [],
            'dm_count' => 99,
        ]);

        $paginator = $service->forTeacher($teacher);
        $activities = collect($paginator->items());

        $this->assertNotEmpty($activities);
        $this->assertSame('invitation_configured', $activities[0]['type']);

        $activityIds = $activities->pluck('id');
        $this->assertTrue($activityIds->contains("ds-batch-{$dsBatch->id}"));
        $this->assertFalse($activityIds->contains(fn (string $id) => str_starts_with($id, 'dm-batch-')));
    }
}
