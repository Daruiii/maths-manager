<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DmBatch;
use App\Models\DsBatch;
use App\Models\TdBatch;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TeacherAssignmentController extends Controller
{
    public function show(string $type, int $batch): Response
    {
        abort_unless(in_array($type, ['ds', 'dm', 'td'], true), 404);

        [$batchModel, $items] = match ($type) {
            'ds' => $this->loadDsBatch($batch),
            'dm' => $this->loadDmBatch($batch),
            'td' => $this->loadTdBatch($batch),
        };

        abort_unless($batchModel->teacher_id === Auth::id(), 403);

        $statuses = $items
            ->groupBy('status')
            ->map->count()
            ->toArray();

        $groups = $items
            ->filter(fn ($i) => $i['student']['group'] ?? null)
            ->groupBy(fn ($i) => $i['student']['group']['id'])
            ->map(fn ($g) => [
                'id' => $g->first()['student']['group']['id'],
                'name' => $g->first()['student']['group']['name'],
                'count' => $g->count(),
            ])
            ->values()
            ->toArray();

        return Inertia::render('Teacher/Assignments/Show', [
            'type' => $type,
            'batch' => [
                'id' => $batchModel->id,
                'title' => $items->first()['title'] ?? strtoupper($type),
                'due_date' => $batchModel->due_date?->format('Y-m-d'),
                'created_at' => $batchModel->created_at->format('Y-m-d'),
                'total' => $items->count(),
                'statuses' => $statuses,
                'groups' => $groups,
            ],
            'items' => $items->values(),
        ]);
    }

    private function loadDsBatch(int $batchId): array
    {
        $batch = DsBatch::with([
            'ds.user:id,first_name,last_name,avatar,group_id',
            'ds.user.group:id,name',
            'ds.correctionRequest:id,ds_id,status',
        ])->findOrFail($batchId);

        return [$batch, $batch->ds->map(fn ($ds) => [
            'id' => $ds->id,
            'title' => $ds->custom_title,
            'status' => $this->statusValue($ds->status),
            'student' => $this->studentPayload($ds->user),
            'show_url' => route('ds.show', $ds->id),
            'correction_request_id' => $ds->correctionRequest?->id,
            'correction_status' => $ds->correctionRequest ? $this->statusValue($ds->correctionRequest->status) : null,
        ])];
    }

    private function loadDmBatch(int $batchId): array
    {
        $batch = DmBatch::with([
            'dms.user:id,first_name,last_name,avatar,group_id',
            'dms.user.group:id,name',
            'dms.correctionRequest:id,dm_id,status',
        ])->findOrFail($batchId);

        return [$batch, $batch->dms->map(fn ($dm) => [
            'id' => $dm->id,
            'title' => $dm->custom_title,
            'status' => $this->statusValue($dm->status),
            'student' => $this->studentPayload($dm->user),
            'show_url' => route('dm.show', $dm->id),
            'correction_request_id' => $dm->correctionRequest?->id,
            'correction_status' => $dm->correctionRequest ? $this->statusValue($dm->correctionRequest->status) : null,
        ])];
    }

    private function loadTdBatch(int $batchId): array
    {
        $batch = TdBatch::with([
            'tds.student:id,first_name,last_name,avatar,group_id',
            'tds.student.group:id,name',
        ])->findOrFail($batchId);

        return [$batch, $batch->tds->map(fn ($td) => [
            'id' => $td->id,
            'title' => $td->custom_title,
            'status' => $this->statusValue($td->status),
            'student' => $this->studentPayload($td->student),
            'show_url' => route('td.show', $td->id),
            'correction_request_id' => null,
        ])];
    }

    private function statusValue(mixed $status): string
    {
        return $status instanceof \BackedEnum ? $status->value : (string) $status;
    }

    private function studentPayload($student): ?array
    {
        if (!$student) return null;

        return [
            'id' => $student->id,
            'first_name' => $student->first_name,
            'last_name' => $student->last_name,
            'avatar' => $student->avatar,
            'group' => $student->group
                ? ['id' => $student->group->id, 'name' => $student->group->name]
                : null,
        ];
    }
}
