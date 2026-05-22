<?php

namespace App\Http\Controllers\Sheet;

use App\Http\Controllers\Controller;

use App\Enums\TdStatus;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;
use App\Helpers\ErrorResponseHelper;
use App\Http\Requests\Td\StoreTdRequest;
use App\Http\Requests\Td\UpdateTdRequest;
use App\Http\Requests\Td\UpdateTdStatusRequest;
use App\Mail\AssignSheetMail;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\Td;
use App\Models\User;
use App\Notifications\StudentRequestedUnlock;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
class TdController extends Controller
{
    protected \App\Services\SheetFormattingService $sheetFormattingService;
    protected \App\Services\QueryFiltersService $queryFiltersService;

    public function __construct(
        \App\Services\SheetFormattingService $sheetFormattingService,
        \App\Services\QueryFiltersService $queryFiltersService
    ) {
        $this->sheetFormattingService = $sheetFormattingService;
        $this->queryFiltersService = $queryFiltersService;
    }

    // Méthode pour afficher toutes les fiches d'exercices
    public function index(Request $request)
    {
        $sort_by_student = $request->query('sort_by_student');
        $tdList = Td::query();

        // Recherche dans la relation user via le service
        if ($request->query('search')) {
            $tdList = $tdList->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            });
        }

        // Tri par étudiant
        if ($request->filled('sort_by_student')) {
            $tdList = $tdList->orderBy('user_id');
        }

        // Tri par date de création par défaut
        $tdList = $tdList->orderBy('created_at', 'desc');

        $tdList = $tdList->paginate(10)->withQueryString();

        // Legacy view - kept for old_blade reference
        return view('td.index', ['tdList' => $tdList, 'sort_by_student' => $sort_by_student]);
    }

    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('td.myTd', Auth::id());
        }
        $tdList = Td::where('user_id', $id)->paginate(10);
        // Legacy view - kept for old_blade reference
        return view('td.myTd', ['tdList' => $tdList]);
    }

    // Méthode pour selectionner un chapitre avant de créer un TD
    public function selectChapter(Request $request)
    {
        $chapters = Chapter::all();
        $studentId = $request->student_id;
        return view('td.select_chapter', compact('chapters', 'studentId'));
    }

    // Méthode pour afficher le formulaire de création d'un TD
    public function create(Request $request)
    {
        $selectedChapterId = $request->chapter_id;
        $selectedStudentId = $request->student_id;
        $selectedChapter = Chapter::find($selectedChapterId);

        if (!$selectedChapter) {
            return redirect()->back()->withErrors('Le chapitre spécifié n\'existe pas.');
        }

        $exercises = Exercise::whereHas('subchapter', function ($query) use ($selectedChapterId) {
            $query->whereHas('chapter', function ($subQuery) use ($selectedChapterId) {
                $subQuery->where('id', $selectedChapterId);
            });
        })->get();
        $students = User::where('role', 'student')->get();

        return view('td.create', compact('exercises', 'students', 'selectedChapter', 'selectedStudentId'));
    }

    // Méthode pour enregistrer un TD
    public function store(StoreTdRequest $request)
    {
        $td = new Td();
        $td->user_id = $request->user_id;
        $td->chapter_id = $request->chapter_id;
        $td->title = $request->title;
        $td->save();
        $td->exercises()->attach($request->exercises);

        // envoyer un mail à l'élève
        $student = User::find($request->user_id);
        try {
            Mail::to($student->email)->send(new AssignSheetMail($td));
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, 'TD create');
        }

        return redirect()->route('td.index')->with('success', 'TD créé avec succès');
    }

    // Méthode pour editer un TD
    public function edit($id)
    {
        $td = Td::find($id);
        $exercises = Exercise::whereHas('subchapter', function ($query) use ($td) {
            $query->whereHas('chapter', function ($subQuery) use ($td) {
                $subQuery->where('id', $td->chapter_id);
            });
        })->get();
        $students = User::where('role', 'student')->get();
        // Legacy view - kept for old_blade reference
        return view('td.edit', ['td' => $td, 'exercises' => $exercises, 'students' => $students]);
    }

    // Méthode pour mettre à jour un TD
    public function update(UpdateTdRequest $request, $id)
    {
        $td = Td::find($id);

        $td->user_id = $request->user_id;
        $td->chapter_id = $request->chapter_id;
        $td->title = $request->title;
        $td->save();
        $td->exercises()->sync($request->exercises);

        // envoyer un mail à l'élève
        $student = User::find($request->user_id);
        try {
            Mail::to($student->email)->send(new AssignSheetMail($td));
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, 'TD update');
        }

        return redirect()->route('td.index')->with('success', 'TD modifié avec succès');
    }

    // Méthode pour supprimer un TD
    public function destroy($id)
    {
        $td = Td::find($id);
        $td->delete();
        return redirect()->route('td.index')->with('success', 'TD supprimé avec succès');
    }

    public function show(int $id): InertiaResponse
    {
        $td = Td::findOrFail($id);
        $isTeacher = Auth::id() === $td->teacher_id;
        abort_unless(Auth::id() === $td->user_id || $isTeacher, 403);

        $td->load(['exercises', 'privateExercises', 'teacher:id,first_name,last_name']);

        $unlocked = $td->correction_unlocked || $isTeacher;

        return Inertia::render('Student/TD/Show', [
            'td' => [
                'id'                  => $td->id,
                'status'              => $td->status,
                'is_teacher'          => $isTeacher,
                'custom_title'        => $td->custom_title,
                'custom_level'        => $td->custom_level,
                'custom_instructions' => $td->custom_instructions,
                'correction_unlocked' => $unlocked,
                'teacher'             => $td->teacher
                    ? ['id' => $td->teacher->id, 'first_name' => $td->teacher->first_name, 'last_name' => $td->teacher->last_name]
                    : null,
                'exercises'         => $td->exercises->map(fn ($e) => $this->mapTdItem($e, $unlocked)),
                'private_exercises' => $td->privateExercises->map(fn ($e) => $this->mapTdItem($e, $unlocked)),
            ],
        ]);
    }

    private function mapTdItem(mixed $item, bool $unlocked): array
    {
        return [
            'id'              => $item->id,
            'name'            => $item->name ?? null,
            'title'           => $item->title ?? null,
            'statement'       => $item->statement ?? null,
            'latex_statement' => $item->latex_statement ?? null,
            'image_paths'     => $item->image_paths ?? null,
            'difficulty'      => $item->difficulty ?? null,
            'latex_solution'  => $unlocked ? ($item->latex_solution ?? null) : null,
        ];
    }

    public function updateStatus(UpdateTdStatusRequest $request, Td $td): RedirectResponse
    {
        abort_unless(Auth::id() === $td->user_id, 403);

        $td->update(['status' => $request->validated('status')]);

        return back();
    }

    public function requestUnlock(Td $td): RedirectResponse
    {
        abort_unless(Auth::id() === $td->user_id, 403);
        abort_unless($td->status === TdStatus::Ongoing, 422);

        $td->update(['status' => TdStatus::CorrectionRequested]);

        $td->teacher?->notify(new StudentRequestedUnlock($td));

        return back()->with('success', 'Demande de correction envoyée à votre professeur.');
    }
}
