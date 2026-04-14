<?php

namespace App\Services;

use App\Models\CorrectionRequest;
use App\Models\DmBatch;
use App\Models\DsBatch;
use App\Models\StudentGroup;
use App\Models\TdBatch;
use App\Models\TeacherInvitation;
use App\Models\User;
use App\Services\BureauActivity\BureauActivityMapper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

class BureauActivityService
{
    public function __construct(private readonly BureauActivityMapper $mapper)
    {
    }

    /**
     * Build a global activity feed for one teacher.
     */
    public function forTeacher(User $teacher, array $filters = []): LengthAwarePaginator
    {
        $perPage = max(5, min((int) ($filters['per_page'] ?? 20), 100));
        $page = max((int) ($filters['page'] ?? 1), 1);
        $sort = ($filters['sort'] ?? 'desc') === 'asc' ? 'asc' : 'desc';

        $groupNames = StudentGroup::query()
            ->where('teacher_id', $teacher->id)
            ->pluck('name', 'id');

        $activities = collect()
            ->merge($this->mapDsBatchActivities($teacher->id, $groupNames))
            ->merge($this->mapTdBatchActivities($teacher->id, $groupNames))
            ->merge($this->mapDmBatchActivities($teacher->id, $groupNames))
            ->merge($this->mapInvitationActivities($teacher->id, $groupNames))
            ->merge($this->mapStudentJoinActivities($teacher->id))
            ->merge($this->mapCorrectionActivities($teacher->id));

        $activities = $this->applyFilters($activities, $filters)
            ->sortBy('occurred_at_unix', SORT_REGULAR, $sort === 'desc')
            ->values();

        $total = $activities->count();

        $items = $activities
            ->forPage($page, $perPage)
            ->values()
            ->map(function (array $activity) {
                unset($activity['occurred_at_unix']);

                return $activity;
            })
            ->all();

        return new LengthAwarePaginator(
            $items,
            $total,
            $perPage,
            $page,
            [
                'path' => request()->url(),
                'query' => request()->query(),
            ]
        );
    }

    private function mapDsBatchActivities(int $teacherId, Collection $groupNames): Collection
    {
        return DsBatch::query()
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get()
            ->map(fn (DsBatch $batch) => $this->mapper->mapDsBatch($batch, $groupNames));
    }

    private function mapTdBatchActivities(int $teacherId, Collection $groupNames): Collection
    {
        return TdBatch::query()
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get()
            ->map(fn (TdBatch $batch) => $this->mapper->mapTdBatch($batch, $groupNames));
    }

    private function mapDmBatchActivities(int $teacherId, Collection $groupNames): Collection
    {
        return DmBatch::query()
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get()
            ->map(fn (DmBatch $batch) => $this->mapper->mapDmBatch($batch, $groupNames));
    }

    private function mapInvitationActivities(int $teacherId, Collection $groupNames): Collection
    {
        return TeacherInvitation::query()
            ->where('teacher_id', $teacherId)
            ->latest()
            ->get()
            ->map(fn (TeacherInvitation $invitation) => $this->mapper->mapInvitation($invitation, $groupNames));
    }

    private function mapStudentJoinActivities(int $teacherId): Collection
    {
        return User::query()
            ->where('teacher_id', $teacherId)
            ->where('role', 'student')
            ->latest()
            ->get(['id', 'first_name', 'last_name', 'created_at', 'teacher_joined_at'])
            ->map(fn (User $student) => $this->mapper->mapStudentJoin($student));
    }

    private function mapCorrectionActivities(int $teacherId): Collection
    {
        return CorrectionRequest::query()
            ->whereHas('ds', fn ($query) => $query->where('teacher_id', $teacherId))
            ->with(['user:id,first_name,last_name', 'ds:id,teacher_id'])
            ->latest()
            ->get()
            ->map(fn (CorrectionRequest $request) => $this->mapper->mapCorrection($request));
    }

    private function applyFilters(Collection $activities, array $filters): Collection
    {
        $search = mb_strtolower(trim((string) ($filters['search'] ?? '')));
        $scope = $filters['scope'] ?? 'all';
        $type = $filters['type'] ?? 'all';

        return $activities->filter(function (array $activity) use ($search, $scope, $type) {
            if ($scope !== 'all' && $activity['scope'] !== $scope) {
                return false;
            }

            if ($type !== 'all' && $activity['type'] !== $type) {
                return false;
            }

            if ($search === '') {
                return true;
            }

            $haystack = mb_strtolower("{$activity['title']} {$activity['description']} {$activity['type']}");

            return str_contains($haystack, $search);
        });
    }
}
