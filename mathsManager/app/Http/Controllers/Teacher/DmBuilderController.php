<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Requests\DM\AssignDmBuilderRequest;
use App\Models\MultipleChapter;
use App\Models\Problem;
use App\Models\StudentGroup;
use App\Models\Subchapter;
use App\Models\User;
use App\Services\BatchAssignmentService;
use App\Services\BuilderSearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Inertia\Inertia;
use Inertia\Response;

class DmBuilderController extends BaseBuilderController
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

        $multipleChapters = MultipleChapter::with('classe')
            ->where('title', 'not like', 'BONUS%')
            ->orderBy('title')
            ->get(['id', 'title', 'theme', 'classe_id']);

        $subchapters = Subchapter::with(['chapter:id,title,class_id', 'chapter.classe:id,name'])
            ->orderBy('chapter_id')
            ->orderBy('order')
            ->get(['id', 'title', 'chapter_id']);

        $academies = Problem::whereNotNull('academy')
            ->distinct()
            ->orderBy('academy')
            ->pluck('academy')
            ->values();

        return Inertia::render('Teacher/DM/Create', [
            'groups'               => $groups,
            'students'             => $students,
            'multipleChapters'     => $multipleChapters,
            'subchapters'          => $subchapters,
            'academies'            => $academies,
            'privateTags'          => $teacher->teacherTags()->get(['id', 'name', 'color']),
            'preselectedStudentId' => $request->integer('student') ?: null,
            'preselectedGroupId'   => $request->integer('group') ?: null,
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API — RECHERCHE PROBLEMS (paginated + filtres)
    // ──────────────────────────────────────────────────────────────────────────

    public function searchProblems(Request $request): JsonResponse
    {
        $hasHarder = Schema::hasColumn('problems', 'harder_exercise');
        $columns = ['id', 'name', 'difficulty', 'time', 'type', 'year', 'academy', 'multiple_chapter_id', 'latex_statement', 'statement'];
        if ($hasHarder) $columns[] = 'harder_exercise';
        if (Schema::hasColumn('problems', 'image_paths')) $columns[] = 'image_paths';

        $query = \App\Models\Problem::with('multipleChapter')->select($columns);

        if ($search = $request->query('search')) $query->where('name', 'like', "%{$search}%");
        if ($chapterId = $request->query('chapter_id')) $query->where('multiple_chapter_id', $chapterId);
        if ($classId = $request->query('class_id')) {
            $query->whereHas('multipleChapter', fn ($q) => $q->where('classe_id', $classId));
        }
        if ($difficulty = $request->query('difficulty')) $query->where('difficulty', $difficulty);
        if ($hasHarder && $request->query('harder') === '1') $query->where('harder_exercise', true);
        if ($year = $request->query('year')) $query->where('year', $year);
        if ($academy = $request->query('academy')) $query->where('academy', 'like', "%{$academy}%");

        $this->applySortOrDefault($query, $request, ['name', 'difficulty', 'year', 'time'], 'multiple_chapter_id', 'name');

        $problems = $query->paginate(20);

        return response()->json(
            $this->searchService->withImages($problems, 'problems', fn ($p) => 'problem-' . $p->id)
        );
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
    // API — RECHERCHE EXERCICES PRIVÉS (paginated + filtres)
    // ──────────────────────────────────────────────────────────────────────────

    public function searchPrivate(Request $request): JsonResponse
    {
        $teacher = Auth::user();

        $query = \App\Models\PrivateExercise::forTeacher($teacher->id)
            ->select('id', 'name', 'type', 'difficulty', 'time', 'latex_statement');

        if ($search = $request->query('search')) $query->where('name', 'like', "%{$search}%");
        if ($type = $request->query('type')) $query->where('type', $type);
        if ($difficulty = $request->query('difficulty')) $query->where('difficulty', $difficulty);
        if ($tagId = $request->query('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('teacher_tags.id', $tagId));
        }
        if ($classeId = $request->query('classe_id')) $query->where('classe_id', $classeId);
        if ($chapterId = $request->query('chapter_id')) $query->where('chapter_id', $chapterId);
        if ($subchapterId = $request->query('subchapter_id')) $query->where('subchapter_id', $subchapterId);

        $this->applySortOrDefault($query, $request, ['name', 'difficulty', 'time', 'created_at'], defaultCol: 'created_at', defaultDir: 'desc');

        $exercises = $query->paginate(20);

        return response()->json(
            $this->searchService->withImages($exercises, 'private-exercises', fn ($e) => 'private-exercise-' . $e->id)
        );
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ASSIGN — crée 1 DM par élève sélectionné
    // ──────────────────────────────────────────────────────────────────────────

    public function assign(AssignDmBuilderRequest $request)
    {
        $count = $this->assignmentService->assignDm($request, Auth::user());

        return back()->with('success', $count . ' DM assigné(s) avec succès.');
    }
}
