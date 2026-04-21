<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\Td\AssignTdRequest;
use App\Models\StudentGroup;
use App\Models\Subchapter;
use App\Models\User;
use App\Services\BatchAssignmentService;
use App\Services\BuilderSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TdBuilderController extends BaseBuilderController
{
    public function __construct(
        private BuilderSearchService $searchService,
        private BatchAssignmentService $assignmentService,
    ) {}

    // ──────────────────────────────────────────────────────────────────────────
    // PAGE
    // ──────────────────────────────────────────────────────────────────────────

    public function create(Request $request): Response
    {
        $teacher = Auth::user();

        $groups = StudentGroup::where('teacher_id', $teacher->id)
            ->withCount('students')
            ->orderBy('name')
            ->get();

        $students = User::where('teacher_id', $teacher->id)
            ->orderBy('first_name')
            ->get(['id', 'first_name', 'last_name', 'avatar', 'group_id']);

        $subchapters = Subchapter::with(['chapter:id,title,class_id', 'chapter.classe:id,name'])
            ->orderBy('chapter_id')
            ->orderBy('order')
            ->get(['id', 'title', 'chapter_id']);

        $initialTemplate = $this->loadInitialTemplate($request, $teacher, 'td');

        return Inertia::render('Teacher/TD/Create', [
            'groups'               => $groups,
            'students'             => $students,
            'subchapters'          => $subchapters,
            'privateTags'          => $teacher->teacherTags()->get(['id', 'name', 'color']),
            'preselectedStudentId' => $request->integer('student') ?: null,
            'preselectedGroupId'   => $request->integer('group') ?: null,
            'initialTemplate'      => $initialTemplate,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API — RECHERCHE EXERCISES basiques (paginated + filtres)
    // ──────────────────────────────────────────────────────────────────────────

    public function searchExercises(Request $request): JsonResponse
    {
        $query = \App\Models\Exercise::visible()
            ->with(['subchapter.chapter.classe'])
            ->select('id', 'name', 'difficulty', 'order', 'subchapter_id', 'latex_statement');

        if ($search = $request->query('search')) $query->where('name', 'like', "%{$search}%");
        if ($subchapterId = $request->query('subchapter_id')) $query->where('subchapter_id', $subchapterId);
        if ($chapterId = $request->query('chapter_id')) {
            $query->whereHas('subchapter', fn ($q) => $q->where('chapter_id', $chapterId));
        }
        if ($difficulty = $request->query('difficulty')) $query->where('difficulty', $difficulty);
        if ($classId = $request->query('class_id')) {
            $query->whereHas('subchapter.chapter', fn ($q) => $q->where('class_id', $classId));
        }

        $this->applySortOrDefault($query, $request, ['name', 'difficulty', 'order'], 'subchapter_id', 'order');

        $exercises = $query->paginate(20);

        return response()->json(
            $this->searchService->withImages($exercises, 'exercises', fn ($e) => 'exercise-' . $e->id . '/statement')
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API — RECHERCHE EXERCICES PRIVÉS type basic uniquement
    // ──────────────────────────────────────────────────────────────────────────

    public function searchPrivate(Request $request): JsonResponse
    {
        $teacher = Auth::user();

        $query = \App\Models\PrivateExercise::forTeacher($teacher->id)
            ->where('type', 'basic')
            ->select('id', 'name', 'type', 'difficulty', 'time', 'latex_statement');

        if ($search = $request->query('search')) $query->where('name', 'like', "%{$search}%");
        if ($difficulty = $request->query('difficulty')) $query->where('difficulty', $difficulty);
        if ($tagId = $request->query('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('teacher_tags.id', $tagId));
        }
        if ($classeId = $request->query('classe_id')) $query->where('classe_id', $classeId);
        if ($chapterId = $request->query('chapter_id')) $query->where('chapter_id', $chapterId);
        if ($subchapterId = $request->query('subchapter_id')) $query->where('subchapter_id', $subchapterId);

        $this->applySortOrDefault($query, $request, ['name', 'difficulty', 'created_at'], defaultCol: 'created_at', defaultDir: 'desc');

        $exercises = $query->paginate(20);

        return response()->json(
            $this->searchService->withImages($exercises, 'private-exercises', fn ($e) => 'private-exercise-' . $e->id)
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ASSIGN — crée 1 TD par élève sélectionné
    // ──────────────────────────────────────────────────────────────────────────

    public function assign(AssignTdRequest $request)
    {
        $count = $this->assignmentService->assignTd($request, Auth::user());

        return back()->with('success', $count . ' TD assigné(s) avec succès.');
    }

}
