<?php

namespace App\Services;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Enums\DSStatus;
use App\Enums\TdStatus;
use App\Models\CorrectionRequest;
use App\Models\Dm;
use App\Models\DmBatch;
use App\Models\DS;
use App\Models\DsBatch;
use App\Models\Td;
use App\Models\TdBatch;
use App\Models\User;

class HomeDashboardService
{
    public function teacherPayload(User $user): array
    {
        $pendingCorrections = CorrectionRequest::query()
            ->where('status', CorrectionRequestStatus::Pending->value)
            ->where(function ($q) use ($user) {
                $q->whereHas('ds', fn ($q) => $q->where('teacher_id', $user->id))
                    ->orWhereHas('dm', fn ($q) => $q->where('teacher_id', $user->id));
            })
            ->with([
                'user:id,first_name,last_name',
                'ds:id,batch_id,custom_title',
                'dm:id,batch_id,custom_title',
            ])
            ->latest()
            ->get();

        $unlockRequests = Td::where('teacher_id', $user->id)
            ->where('status', TdStatus::CorrectionRequested->value)
            ->with('student:id,first_name,last_name')
            ->latest('updated_at')
            ->get(['id', 'custom_title', 'user_id', 'updated_at']);

        return [
            'pendingCorrections' => [
                'count' => $pendingCorrections->count(),
                'items' => $pendingCorrections->take(5)->map(fn ($cr) => [
                    'id'            => $cr->id,
                    'student_name'  => $cr->user?->name ?? 'Élève',
                    'subject_title' => $cr->ds?->custom_title ?? $cr->dm?->custom_title ?? 'Devoir',
                    'subject_type'  => $cr->ds_id ? 'ds' : 'dm',
                    'batch_id'      => $cr->ds?->batch_id ?? $cr->dm?->batch_id,
                    'batch_url'     => $this->teacherAssignmentUrl($cr),
                    'created_at'    => $cr->created_at->toIso8601String(),
                ])->values(),
            ],
            'unlockRequests' => [
                'count' => $unlockRequests->count(),
                'items' => $unlockRequests->take(5)->map(fn ($td) => [
                    'id'           => $td->id,
                    'student_name' => $td->student?->name ?? 'Élève',
                    'title'        => $td->custom_title ?? 'TD',
                    'updated_at'   => $td->updated_at->toIso8601String(),
                ])->values(),
            ],
            'pendingTeachersCount' => $user->isAdmin()
                ? User::where('role', 'teacher')->where('status', 'pending_approval')->count()
                : 0,
            'activeStudentsCount' => $user->students()->where('status', 'active')->count(),
            'assignedThisMonth' => $this->assignedThisMonth($user),
        ];
    }

    public function studentPayload(User $user): array
    {
        $activeDs = DS::where('user_id', $user->id)
            ->whereIn('status', [
                DSStatus::NotStarted->value,
                DSStatus::Ongoing->value,
                DSStatus::Paused->value,
                DSStatus::Sent->value,
            ])
            ->with('batch:id,due_date')
            ->latest()
            ->get(['id', 'custom_title', 'status', 'batch_id']);

        $activeDm = Dm::where('user_id', $user->id)
            ->whereIn('status', [
                DmStatus::NotStarted->value,
                DmStatus::Ongoing->value,
            ])
            ->with('batch:id,due_date')
            ->latest()
            ->get(['id', 'custom_title', 'status', 'batch_id']);

        $activeTd = Td::where('user_id', $user->id)
            ->whereIn('status', [
                TdStatus::NotStarted->value,
                TdStatus::Ongoing->value,
                TdStatus::CorrectionRequested->value,
            ])
            ->with('batch:id,due_date')
            ->latest()
            ->get(['id', 'custom_title', 'status', 'batch_id']);

        $averageGrade = CorrectionRequest::where('user_id', $user->id)
            ->where('status', CorrectionRequestStatus::Corrected->value)
            ->avg('grade');

        $correctedCount = CorrectionRequest::where('user_id', $user->id)
            ->where('status', CorrectionRequestStatus::Corrected->value)
            ->count();

        return [
            'activeAssignments' => [
                'ds' => $activeDs->map(fn ($ds) => [
                    'id'       => $ds->id,
                    'title'    => $ds->custom_title ?? 'DS',
                    'status'   => $ds->status,
                    'due_date' => $ds->batch?->due_date?->toDateString(),
                ])->values(),
                'dm' => $activeDm->map(fn ($dm) => [
                    'id'       => $dm->id,
                    'title'    => $dm->custom_title ?? 'DM',
                    'status'   => $dm->status->value,
                    'due_date' => $dm->batch?->due_date?->toDateString(),
                ])->values(),
                'td' => $activeTd->map(fn ($td) => [
                    'id'       => $td->id,
                    'title'    => $td->custom_title ?? 'TD',
                    'status'   => $td->status->value,
                    'due_date' => $td->batch?->due_date?->toDateString(),
                ])->values(),
            ],
            'averageGrade' => $averageGrade ? round((float) $averageGrade, 1) : null,
            'correctedCount' => $correctedCount,
        ];
    }

    private function assignedThisMonth(User $user): int
    {
        $range = [now()->startOfMonth(), now()->endOfMonth()];

        return DsBatch::where('teacher_id', $user->id)->whereBetween('created_at', $range)->count()
            + DmBatch::where('teacher_id', $user->id)->whereBetween('created_at', $range)->count()
            + TdBatch::where('teacher_id', $user->id)->whereBetween('created_at', $range)->count();
    }

    private function teacherAssignmentUrl(CorrectionRequest $correctionRequest): ?string
    {
        $type = $correctionRequest->ds_id ? 'ds' : 'dm';
        $batchId = $correctionRequest->ds?->batch_id ?? $correctionRequest->dm?->batch_id;

        return $batchId
            ? route('teacher.assignations.show', ['type' => $type, 'batch' => $batchId])
            : null;
    }
}
