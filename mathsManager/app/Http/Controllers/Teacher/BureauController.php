<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BuilderTemplate;
use App\Models\DmBatch;
use App\Models\DsBatch;
use App\Models\PrivateExercise;
use App\Models\StudentGroup;
use App\Models\TdBatch;
use App\Enums\DSStatus;
use App\Enums\DmStatus;
use App\Enums\TdStatus;
use App\Services\BureauActivityService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BureauController extends Controller
{
    /**
     * Dashboard du prof — hub de navigation vers les ressources.
     */
    public function index(): Response
    {
        $teacher = Auth::user();

        $batchesCount = DsBatch::where('teacher_id', $teacher->id)->count()
            + TdBatch::where('teacher_id', $teacher->id)->count()
            + DmBatch::where('teacher_id', $teacher->id)->count();

        $studentsCount = $teacher->students()->where('status', 'active')->count();

        return Inertia::render('Teacher/Bureau/Index', [
            'stats' => [
                'exercisesCount'   => PrivateExercise::forTeacher($teacher->id)->count(),
                'dsTemplatesCount' => BuilderTemplate::where('teacher_id', $teacher->id)->where('type', 'ds')->count(),
                'tdTemplatesCount' => BuilderTemplate::where('teacher_id', $teacher->id)->where('type', 'td')->count(),
                'dmTemplatesCount' => BuilderTemplate::where('teacher_id', $teacher->id)->where('type', 'dm')->count(),
                'batchesCount'     => $batchesCount,
                'studentsCount'    => $studentsCount,
            ],
        ]);
    }

    /**
     * Tous les devoirs envoyés — DS, DM, TD organisés par type.
     */
    public function devoirs(): Response
    {
        $teacher = Auth::user();

        $dsBatches = DsBatch::where('teacher_id', $teacher->id)
            ->with(['ds' => fn($q) => $q->select('id', 'batch_id', 'status', 'custom_title')])
            ->orderByDesc('created_at')->get()
            ->map(fn($b) => $this->mapBatch($b, 'ds', 'ds'));

        $tdBatches = TdBatch::where('teacher_id', $teacher->id)
            ->with(['tds' => fn($q) => $q->select('id', 'batch_id', 'status', 'custom_title')])
            ->orderByDesc('created_at')->get()
            ->map(fn($b) => $this->mapBatch($b, 'tds', 'td'));

        $dmBatches = DmBatch::where('teacher_id', $teacher->id)
            ->with(['dms' => fn($q) => $q->select('id', 'batch_id', 'status', 'custom_title')])
            ->orderByDesc('created_at')->get()
            ->map(fn($b) => $this->mapBatch($b, 'dms', 'dm'));

        $groups = StudentGroup::where('teacher_id', $teacher->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Teacher/Bureau/Devoirs', [
            'dsBatches' => $dsBatches,
            'tdBatches' => $tdBatches,
            'dmBatches' => $dmBatches,
            'groups'    => $groups,
        ]);
    }

    public function toggleArchive(string $type, int $id): RedirectResponse
    {
        $modelClass = match ($type) {
            'ds' => DsBatch::class,
            'dm' => DmBatch::class,
            'td' => TdBatch::class,
            default => abort(404),
        };

        $batch = $modelClass::where('teacher_id', Auth::id())->findOrFail($id);
        $batch->update(['is_archived' => !$batch->is_archived]);

        return back();
    }

    private function mapBatch(mixed $batch, string $relation, string $type): array
    {
        $items = $batch->$relation;

        $pendingKey = match ($type) {
            'ds'    => 'sent',
            'td'    => 'correction_requested',
            'dm'    => 'finished',
            default => '',
        };

        $completedKey = match ($type) {
            'ds' => DSStatus::Corrected->value,
            'dm' => DmStatus::Corrected->value,
            'td' => TdStatus::CorrectionUnlocked->value,
            default => '',
        };

        $statuses = $items
            ->groupBy(fn($item) => $item->status instanceof \BackedEnum ? $item->status->value : $item->status)
            ->map->count()
            ->toArray();

        $total        = $items->count();
        $pendingCount = $statuses[$pendingKey] ?? 0;
        $isComplete   = $total > 0 && ($statuses[$completedKey] ?? 0) >= $total;

        return [
            'id'              => $batch->id,
            'title'           => $items->first()?->custom_title ?? strtoupper($type),
            'due_date'        => $batch->due_date?->format('Y-m-d'),
            'created_at'      => $batch->created_at->format('Y-m-d'),
            'total'           => $total,
            'statuses'        => $statuses,
            'pending_actions' => $pendingCount,
            'is_archived'     => $batch->is_archived || $isComplete,
            'group_ids'       => $batch->group_ids ?? [],
        ];
    }

    /**
     * Listing unifié des templates sauvegardés (DS + TD + DM).
     */
    public function templates(): Response
    {
        $teacher = Auth::user();

        $cols = ['id', 'name', 'type', 'student_group_id', 'created_at', 'payload'];

        $base = BuilderTemplate::where('teacher_id', $teacher->id)
            ->with('studentGroup:id,name')
            ->orderByDesc('created_at');

        $groups = StudentGroup::where('teacher_id', $teacher->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Teacher/Bureau/Templates', [
            'dsTemplates' => (clone $base)->where('type', 'ds')->get($cols),
            'tdTemplates' => (clone $base)->where('type', 'td')->get($cols),
            'dmTemplates' => (clone $base)->where('type', 'dm')->get($cols),
            'groups'      => $groups,
        ]);
    }

    /**
     * Historique global du professeur (assignations, élèves, corrections...).
     */
    public function history(Request $request, BureauActivityService $activityService): Response
    {
        $teacher = Auth::user();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'scope' => ['nullable', 'in:all,assignments,students,corrections'],
            'type' => [
                'nullable',
                'in:all,ds_assigned,td_assigned,dm_assigned,student_joined,invitation_configured,correction_requested,correction_processed',
            ],
            'sort' => ['nullable', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $paginator = $activityService->forTeacher($teacher, [
            'search' => $filters['search'] ?? '',
            'scope' => $filters['scope'] ?? 'all',
            'type' => $filters['type'] ?? 'all',
            'sort' => $filters['sort'] ?? 'desc',
            'page' => $filters['page'] ?? 1,
            'per_page' => $filters['per_page'] ?? 20,
        ]);

        return Inertia::render('Teacher/Bureau/History', [
            'activities' => $paginator,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'all',
                'type' => $filters['type'] ?? 'all',
                'sort' => $filters['sort'] ?? 'desc',
                'per_page' => (int) ($filters['per_page'] ?? 20),
            ],
        ]);
    }
}
