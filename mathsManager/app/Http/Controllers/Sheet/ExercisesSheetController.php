<?php

namespace App\Http\Controllers\Sheet;

use App\Http\Controllers\Controller;

use App\Helpers\ErrorResponseHelper;
use App\Http\Requests\ExercisesSheet\StoreExercisesSheetRequest;
use App\Http\Requests\ExercisesSheet\UpdateExercisesSheetRequest;
use App\Mail\AssignSheetMail;
use App\Models\Chapter;
use App\Models\Exercise;
use App\Models\ExercisesSheet;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class ExercisesSheetController extends Controller
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
        $exercisesSheetList = ExercisesSheet::query();

        // Recherche dans la relation user via le service
        if ($request->query('search')) {
            $exercisesSheetList = $exercisesSheetList->whereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            });
        }

        // Tri par étudiant
        if ($request->filled('sort_by_student')) {
            $exercisesSheetList = $exercisesSheetList->orderBy('user_id');
        }

        // Tri par date de création par défaut
        $exercisesSheetList = $exercisesSheetList->orderBy('created_at', 'desc');

        $exercisesSheetList = $exercisesSheetList->paginate(10)->withQueryString();

        return view('exercises_sheet.index', compact('exercisesSheetList', 'sort_by_student'));
    }

    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('exercises_sheet.myExerciseSheets', Auth::id());
        }
        $exercisesSheetList = ExercisesSheet::where('user_id', $id)->paginate(10);
        return view('exercises_sheet.myExercisesSheet', compact('exercisesSheetList'));
    }

    // Méthode pour selectionner un chapitre avant de créer une fiche d'exercices
    public function selectChapter(Request $request)
    {
        $chapters = Chapter::all();
        $studentId = $request->student_id;
        return view('exercises_sheet.select_chapter', compact('chapters', 'studentId'));
    }
    // Méthode pour afficher le formulaire de création d'une fiche d'exercices
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

        return view('exercises_sheet.create', compact('exercises', 'students', 'selectedChapter', 'selectedStudentId'));
    }

    // Méthode pour enregistrer une fiche d'exercices
    public function store(StoreExercisesSheetRequest $request)
    {
        $exercisesSheet = new ExercisesSheet();
        $exercisesSheet->user_id = $request->user_id;
        $exercisesSheet->chapter_id = $request->chapter_id;
        $exercisesSheet->title = $request->title;
        $exercisesSheet->save();
        $exercisesSheet->exercises()->attach($request->exercises);

        // envoyer un mail à l'élève
        $student = User::find($request->user_id);
        try {
            Mail::to($student->email)->send(new AssignSheetMail($exercisesSheet));
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, 'Exercises sheet create');
        }

        return redirect()->route('exercises_sheet.index')->with('success', 'Fiche d\'exercices créée avec succès');
    }

    // Méthode pour editer une fiche d'exercices
    public function edit($id)
    {
        $exercisesSheet = ExercisesSheet::find($id);
        $exercises = Exercise::whereHas('subchapter', function ($query) use ($exercisesSheet) {
            $query->whereHas('chapter', function ($subQuery) use ($exercisesSheet) {
                $subQuery->where('id', $exercisesSheet->chapter_id);
            });
        })->get();
        $students = User::where('role', 'student')->get();
        return view('exercises_sheet.edit', compact('exercisesSheet', 'exercises', 'students'));
    }

    // Méthode pour mettre à jour une fiche d'exercices
    public function update(UpdateExercisesSheetRequest $request, $id)
    {
        $exercisesSheet = ExercisesSheet::find($id);

        $exercisesSheet->user_id = $request->user_id;
        $exercisesSheet->chapter_id = $request->chapter_id;
        $exercisesSheet->title = $request->title;
        $exercisesSheet->save();
        $exercisesSheet->exercises()->sync($request->exercises);

        // envoyer un mail à l'élève
        $student = User::find($request->user_id);
        try {
            Mail::to($student->email)->send(new AssignSheetMail($exercisesSheet));
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, 'Exercises sheet update');
        }

        return redirect()->route('exercises_sheet.index')->with('success', 'Fiche d\'exercices modifiée avec succès');
    }

    // Méthode pour supprimer une fiche d'exercices
    public function destroy($id)
    {
        $exercisesSheet = ExercisesSheet::find($id);
        $exercisesSheet->delete();
        return redirect()->route('exercises_sheet.index')->with('success', 'Fiche d\'exercices supprimée avec succès');
    }

    // Méthode pour afficher une fiche d'exercices
    public function show($id)
    {
        $exercisesSheet = ExercisesSheet::with('exercises.subchapter')->find($id);

        $exercises = $this->sheetFormattingService->formatExercisesBySubchapter($exercisesSheet);

        // Mettre à jour le statut à "opened" ssi l'utilisateur connecté est le propriétaire de la fiche
        if (Auth::id() == $exercisesSheet->user_id) {
            $exercisesSheet->status = 'opened';
            $exercisesSheet->save();
        }

        return view('exercises_sheet.show', compact('exercisesSheet', 'exercises'));
    }
}
