<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\DS;
use App\Models\Exercise;
use App\Models\MultipleChapter;
use App\Models\Problem;
use App\Models\StudentGroup;
use App\Models\Subchapter;
use App\Models\User;
use App\Services\FileUploadService;
use Illuminate\Support\Facades\Schema;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\AssignDSMail;
use App\Helpers\ErrorResponseHelper;
use Inertia\Inertia;
use Inertia\Response;

class DSBuilderController extends Controller
{
    public function __construct(private FileUploadService $fileUploadService) {}
    /**
     * Temps par défaut (minutes) pour un exercise basique sans champ time.
     * Timer V1 — sera dynamique en V2.
     */
    private const DEFAULT_EXERCISE_MINUTES = 10;

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

        return Inertia::render('Teacher/DS/Create', [
            'groups'          => $groups,
            'students'        => $students,
            'multipleChapters' => $multipleChapters,
            'subchapters'      => $subchapters,
            'academies'        => $academies,
            // Pré-sélection depuis la page élèves
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
        if ($hasHarder) {
            $columns[] = 'harder_exercise';
        }
        if (Schema::hasColumn('problems', 'image_paths')) {
            $columns[] = 'image_paths';
        }

        $query = Problem::with('multipleChapter')->select($columns);

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($chapterId = $request->query('chapter_id')) {
            $query->where('multiple_chapter_id', $chapterId);
        }

        if ($classId = $request->query('class_id')) {
            $query->whereHas('multipleChapter', function ($q) use ($classId) {
                $q->where('classe_id', $classId);
            });
        }

        if ($difficulty = $request->query('difficulty')) {
            $query->where('difficulty', $difficulty);
        }

        if ($hasHarder && $request->query('harder') === '1') {
            $query->where('harder_exercise', true);
        }

        if ($year = $request->query('year')) {
            $query->where('year', $year);
        }

        if ($academy = $request->query('academy')) {
            $query->where('academy', 'like', "%{$academy}%");
        }

        $problems = $query->orderBy('multiple_chapter_id')->orderBy('name')->paginate(20);

        // Charger les images depuis le filesystem (image_paths n'est pas en DB)
        $problems->getCollection()->transform(function (Problem $problem) {
            $files = $this->fileUploadService->getFiles(
                'problems',
                'problem-' . $problem->id,
                true,   // isPublic
                'img-*'
            );

            // Format: ['img-1' => 'problems/problem-123/img-1.jpg', ...]
            // Conserve les deux formats (associatif pour \graph{id} et indexé pour \graph{n})
            $imagePaths = [];
            foreach ($files as $path) {
                $name = pathinfo($path, PATHINFO_FILENAME); // 'img-1'
                $imagePaths[$name] = $path;
            }

            $problem->image_paths = $imagePaths;
            return $problem;
        });

        return response()->json($problems);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // API — RECHERCHE EXERCISES basiques (paginated + filtres)
    // ──────────────────────────────────────────────────────────────────────────

    public function searchExercises(Request $request): JsonResponse
    {
        $query = Exercise::visible()
            ->with(['subchapter.chapter.classe'])
            ->select('id', 'name', 'difficulty', 'order', 'subchapter_id', 'latex_statement');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }

        if ($subchapterId = $request->query('subchapter_id')) {
            $query->where('subchapter_id', $subchapterId);
        }

        if ($chapterId = $request->query('chapter_id')) {
            $query->whereHas('subchapter', function ($q) use ($chapterId) {
                $q->where('chapter_id', $chapterId);
            });
        }

        if ($difficulty = $request->query('difficulty')) {
            $query->where('difficulty', $difficulty);
        }

        if ($classId = $request->query('class_id')) {
            $query->whereHas('subchapter.chapter', function ($q) use ($classId) {
                $q->where('class_id', $classId);
            });
        }

        $exercises = $query->orderBy('subchapter_id')->orderBy('order')->paginate(20);

        return response()->json($exercises);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // ASSIGN — crée 1 DS par élève sélectionné
    // ──────────────────────────────────────────────────────────────────────────

    public function assign(Request $request)
    {
        $request->validate([
            'problem_ids'         => 'required_without_all:exercise_ids|array|min:1',
            'problem_ids.*'       => 'exists:problems,id',
            'exercise_ids'        => 'required_without_all:problem_ids|array|min:1',
            'exercise_ids.*'      => 'exists:exercises,id',
            'student_ids'         => 'required_without:group_ids|array',
            'student_ids.*'       => 'exists:users,id',
            'group_ids'           => 'required_without:student_ids|array',
            'group_ids.*'         => 'exists:student_groups,id',
            'custom_title'        => 'nullable|string|max:255',
            'custom_level'        => 'nullable|string|max:255',
            'custom_instructions' => 'nullable|string',
        ]);

        $teacher = Auth::user();

        $problems = Problem::findMany($request->input('problem_ids', []));
        $exercises = Exercise::findMany($request->input('exercise_ids', []));
        $totalTime = $problems->sum('time') + ($exercises->count() * self::DEFAULT_EXERCISE_MINUTES);
        $multipleChapterIds = $problems->pluck('multiple_chapter_id')->unique()->values()->all();

        // Résoudre les élèves cibles (individuels + membres des groupes)
        $studentIds = collect($request->input('student_ids', []));

        foreach ($request->input('group_ids', []) as $groupId) {
            $group = StudentGroup::where('id', $groupId)
                ->where('teacher_id', $teacher->id)
                ->first();

            if ($group) {
                $groupStudentIds = User::where('group_id', $group->id)->pluck('id');
                $studentIds = $studentIds->merge($groupStudentIds);
            }
        }

        $studentIds = $studentIds->unique()->values();

        foreach ($studentIds as $studentId) {
            $ds = new DS();
            $ds->user_id    = $studentId;
            $ds->teacher_id = $teacher->id;
            $ds->type_bac   = false;
            $ds->exercises_number = $problems->count() + $exercises->count();
            $ds->harder_exercises = false;
            $ds->time   = $totalTime;
            $ds->timer  = $totalTime * 60;
            $ds->chrono = 0;
            $ds->status = 'not_started';
            $ds->custom_title        = $request->input('custom_title');
            $ds->custom_level        = $request->input('custom_level');
            $ds->custom_instructions = $request->input('custom_instructions');
            $ds->save();

            $ds->multipleChapters()->attach($multipleChapterIds);
            if ($problems->isNotEmpty()) {
                $ds->problems()->attach($problems->pluck('id'));
            }
            if ($exercises->isNotEmpty()) {
                $ds->exercises()->attach($exercises->pluck('id'));
            }

            $student = User::find($studentId);
            try {
                Mail::to($student->email)->send(new AssignDSMail($ds));
            } catch (\Exception $e) {
                ErrorResponseHelper::mailError($e, 'DS builder assign');
            }
        }

        return back()->with('success', count($studentIds) . ' DS assigné(s) avec succès.');
    }
}
