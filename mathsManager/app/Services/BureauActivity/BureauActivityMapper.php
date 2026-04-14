<?php

namespace App\Services\BureauActivity;

use App\Models\CorrectionRequest;
use App\Models\DmBatch;
use App\Models\DsBatch;
use App\Models\TdBatch;
use App\Models\TeacherInvitation;
use App\Models\User;
use Illuminate\Support\Collection;

class BureauActivityMapper
{
    public function mapDsBatch(DsBatch $batch, Collection $groupNames): array
    {
        $recipientCount = (int) ($batch->ds_count ?? count($batch->student_ids ?? []));

        return [
            'id' => "ds-batch-{$batch->id}",
            'type' => 'ds_assigned',
            'scope' => 'assignments',
            'title' => 'DS assigné',
            'description' => $this->buildAssignmentDescription($recipientCount, $batch->group_ids ?? [], $groupNames),
            'occurred_at' => $batch->created_at?->toIso8601String(),
            'occurred_at_unix' => $batch->created_at?->timestamp ?? 0,
        ];
    }

    public function mapTdBatch(TdBatch $batch, Collection $groupNames): array
    {
        $recipientCount = (int) ($batch->td_count ?? count($batch->student_ids ?? []));

        return [
            'id' => "td-batch-{$batch->id}",
            'type' => 'td_assigned',
            'scope' => 'assignments',
            'title' => 'TD assigné',
            'description' => $this->buildAssignmentDescription($recipientCount, $batch->group_ids ?? [], $groupNames),
            'occurred_at' => $batch->created_at?->toIso8601String(),
            'occurred_at_unix' => $batch->created_at?->timestamp ?? 0,
        ];
    }

    public function mapDmBatch(DmBatch $batch, Collection $groupNames): array
    {
        $recipientCount = (int) ($batch->dm_count ?? count($batch->student_ids ?? []));

        return [
            'id' => "dm-batch-{$batch->id}",
            'type' => 'dm_assigned',
            'scope' => 'assignments',
            'title' => 'DM assigné',
            'description' => $this->buildAssignmentDescription($recipientCount, $batch->group_ids ?? [], $groupNames),
            'occurred_at' => $batch->created_at?->toIso8601String(),
            'occurred_at_unix' => $batch->created_at?->timestamp ?? 0,
        ];
    }

    public function mapInvitation(TeacherInvitation $invitation, Collection $groupNames): array
    {
        $groupName = $invitation->group_id ? $groupNames->get($invitation->group_id) : null;

        $description = $groupName
            ? "Lien d'invitation configuré pour le groupe {$groupName}."
            : "Lien d'invitation configuré pour tous vos élèves.";

        return [
            'id' => "invitation-{$invitation->id}",
            'type' => 'invitation_configured',
            'scope' => 'students',
            'title' => 'Lien d\'invitation configuré',
            'description' => $description,
            'occurred_at' => $invitation->created_at?->toIso8601String(),
            'occurred_at_unix' => $invitation->created_at?->timestamp ?? 0,
        ];
    }

    public function mapStudentJoin(User $student): array
    {
        $joinedAt = $student->teacher_joined_at;

        return [
            'id' => "student-join-{$student->id}",
            'type' => 'student_joined',
            'scope' => 'students',
            'title' => 'Nouvel élève rattaché',
            'description' => "{$student->name} a rejoint votre espace prof.",
            'occurred_at' => $joinedAt?->toIso8601String(),
            'occurred_at_unix' => $joinedAt?->timestamp ?? 0,
        ];
    }

    public function mapCorrection(CorrectionRequest $request): array
    {
        $isProcessed = in_array($request->status, ['corrected', 'refused'], true);
        $occurredAt = $isProcessed ? $request->updated_at : $request->created_at;
        $studentName = $request->user?->name ?? 'Un élève';

        return [
            'id' => "correction-request-{$request->id}",
            'type' => $isProcessed ? 'correction_processed' : 'correction_requested',
            'scope' => 'corrections',
            'title' => $isProcessed ? 'Correction traitée' : 'Demande de correction',
            'description' => $isProcessed
                ? "Correction traitée pour {$studentName}."
                : "{$studentName} a envoyé une demande de correction.",
            'occurred_at' => $occurredAt?->toIso8601String(),
            'occurred_at_unix' => $occurredAt?->timestamp ?? 0,
        ];
    }

    private function buildAssignmentDescription(int $recipientCount, array $groupIds, Collection $groupNames): string
    {
        $groups = collect($groupIds)
            ->filter()
            ->map(fn ($groupId) => $groupNames->get((int) $groupId))
            ->filter()
            ->values();

        if ($groups->isEmpty()) {
            return "Assigné à {$recipientCount} élève(s).";
        }

        $groupLabel = $groups->take(2)->implode(', ');
        $remainingGroups = $groups->count() - 2;
        $groupSuffix = $remainingGroups > 0 ? " (+{$remainingGroups})" : '';

        return "Assigné à {$recipientCount} élève(s) · Groupes : {$groupLabel}{$groupSuffix}.";
    }
}
