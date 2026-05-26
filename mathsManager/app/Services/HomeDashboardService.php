<?php

namespace App\Services;

use Illuminate\Support\Collection;
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
            ->latest('updated_at')
            ->get(['id', 'custom_title', 'user_id', 'batch_id', 'updated_at']);

        $unlockBatches = $unlockRequests
            ->groupBy('batch_id')
            ->map(function ($items, $batchId) {
                $batchId = $batchId ?: null;
                return [
                    'batch_id'   => $batchId,
                    'title'      => $items->first()->custom_title ?? 'TD',
                    'count'      => $items->count(),
                    'batch_url'  => $batchId
                        ? route('teacher.assignations.show', ['type' => 'td', 'batch' => $batchId])
                        : null,
                    'updated_at' => $items->sortByDesc('updated_at')->first()->updated_at->toIso8601String(),
                ];
            })
            ->values();

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
                'items' => $unlockBatches->take(5)->values(),
            ],
            'pendingTeachersCount' => $user->isAdmin()
                ? User::where('role', 'teacher')->where('status', 'pending_approval')->count()
                : 0,
            'activeStudentsCount' => $user->students()->where('status', 'active')->count(),
            'assignedThisMonth'   => $this->assignedThisMonth($user),
            'activeBatches'       => [
                'ds' => DsBatch::where('teacher_id', $user->id)->where('is_archived', false)->count(),
                'dm' => DmBatch::where('teacher_id', $user->id)->where('is_archived', false)->count(),
                'td' => TdBatch::where('teacher_id', $user->id)->where('is_archived', false)->count(),
            ],
        ];
    }

    public function studentPayload(User $user): array
    {
        $correctionRequests = $this->studentFeedbackRequests($user);
        $correctedCount = $this->correctedFeedbackCount($correctionRequests);

        return [
            'activeAssignments' => $this->studentActiveAssignments($user),
            'averageGrade' => $this->averageFeedbackGrade($correctionRequests),
            'correctedCount' => $correctedCount,
            'feedbackSummary' => [
                'corrected' => $correctedCount,
                'pending'   => $this->pendingFeedbackCount($correctionRequests),
            ],
            'recentFeedbackItems' => $this->recentFeedbackItems($correctionRequests),
        ];
    }

    private function studentActiveAssignments(User $user): array
    {
        $activeDs = DS::where('user_id', $user->id)
            ->whereIn('status', [
                DSStatus::NotStarted->value,
                DSStatus::Ongoing->value,
                DSStatus::Paused->value,
                DSStatus::Finished->value,
                DSStatus::FinishedLate->value,
            ])
            ->with('batch:id,due_date')
            ->latest()
            ->get(['id', 'custom_title', 'status', 'batch_id']);

        $activeDm = Dm::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereIn('status', [
                    DmStatus::NotStarted->value,
                    DmStatus::Ongoing->value,
                ])
                    ->orWhere(function ($query) {
                        $query->where('status', DmStatus::Finished->value)
                            ->whereDoesntHave('correctionRequest');
                    });
            })
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

        return [
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
        ];
    }

    private function studentFeedbackRequests(User $user): Collection
    {
        return CorrectionRequest::where('user_id', $user->id)
            ->whereIn('status', [
                CorrectionRequestStatus::Corrected->value,
                CorrectionRequestStatus::Pending->value,
            ])
            ->with([
                'ds:id,custom_title',
                'dm:id,custom_title',
            ])
            ->latest('updated_at')
            ->get();
    }

    private function averageFeedbackGrade(Collection $correctionRequests): ?float
    {
        $average = $correctionRequests
            ->where('status', CorrectionRequestStatus::Corrected->value)
            ->avg('grade');

        return $average ? round((float) $average, 1) : null;
    }

    private function correctedFeedbackCount(Collection $correctionRequests): int
    {
        return $correctionRequests
            ->where('status', CorrectionRequestStatus::Corrected->value)
            ->count();
    }

    private function pendingFeedbackCount(Collection $correctionRequests): int
    {
        return $correctionRequests
            ->where('status', CorrectionRequestStatus::Pending->value)
            ->count();
    }

    private function recentFeedbackItems(Collection $correctionRequests): Collection
    {
        return $correctionRequests
            ->take(5)
            ->map(fn ($cr) => [
                'id'         => $cr->id,
                'type'       => $cr->ds_id ? 'ds' : 'dm',
                'title'      => $cr->ds?->custom_title ?? $cr->dm?->custom_title ?? 'Devoir',
                'status'     => $cr->status,
                'grade'      => $cr->grade,
                'href'       => $cr->ds_id
                    ? route('ds.show', $cr->ds_id)
                    : route('dm.show', $cr->dm_id),
                'updated_at' => $cr->updated_at->toIso8601String(),
            ])
            ->values();
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
