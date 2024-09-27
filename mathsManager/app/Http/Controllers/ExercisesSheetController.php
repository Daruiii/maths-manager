<?php

namespace App\Http\Controllers;

use App\Models\ExercisesSheet;
use App\Models\Exercise;
use App\Models\User;
use App\Models\Chapter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Mail\AssignSheetMail;
use Illuminate\Support\Facades\Mail;

class ExercisesSheetController extends Controller
{
    // Méthode pour afficher toutes les fiches d'exercices
    public function index(Request $request)
    {
        $sort_by_student = $request->query('sort_by_student');
        $exercisesSheetList = ExercisesSheet::query();
        // Tri par étudiant
        if ($request->filled('sort_by_student')) {
            $exercisesSheetList = $exercisesSheetList->orderBy('user_id');
        }

        // Tri par date de création par défaut
        $exercisesSheetList = $exercisesSheetList->orderBy('created_at', 'desc');

        // Recherche
        if ($request->query('search')) {
            $exercisesSheetList = $exercisesSheetList->whereHas('user', function ($query) {
                $query->where('name', 'like', '%' . request()->query('search') . '%');
            });
        }
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
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'exercises' => 'required|array',
            'exercises.*' => 'exists:exercises,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'nullable|string|max:255',
        ]);

        $exercisesSheet = new ExercisesSheet();
        $exercisesSheet->user_id = $request->user_id;
        $exercisesSheet->chapter_id = $request->chapter_id;
        $exercisesSheet->title = $request->title;
        $exercisesSheet->save();
        $exercisesSheet->exercises()->attach($request->exercises);

        // envoyer un mail à l'élève
        $student = User::find($request->user_id);
        Mail::to($student->email)->send(new AssignSheetMail($exercisesSheet));

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
    public function update(Request $request, $id)
    {
        $exercisesSheet = ExercisesSheet::find($id);
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'exercises' => 'required|array',
            'exercises.*' => 'exists:exercises,id',
            'chapter_id' => 'required|exists:chapters,id',
            'title' => 'nullable|string|max:255',
        ]);

        $exercisesSheet->user_id = $request->user_id;
        $exercisesSheet->chapter_id = $request->chapter_id;
        $exercisesSheet->title = $request->title;
        $exercisesSheet->save();
        $exercisesSheet->exercises()->sync($request->exercises);

        // envoyer un mail à l'élève
        $student = User::find($request->user_id);
        Mail::to($student->email)->send(new AssignSheetMail($exercisesSheet));

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
    
        $globalIndex = 0;
    
        $subChapterIndex = 0;
    
        $exercises = $exercisesSheet->exercises
            ->groupBy('subchapter_id')
            ->map(function ($group) use (&$globalIndex, &$subChapterIndex) {
                $group->each(function ($item) use (&$globalIndex) {
                    $item->globalIndex = ++$globalIndex;
                });
                $subChapterIndex++;
                return [
                    'subChapterIndex' => $subChapterIndex,
                    'subChapterTitle' => $group->first()->subchapter->title,
                    'exercises' => $group,
                    'subChapterOrder' => $group->first()->subchapter->order,
                ];
            })
            ->sortBy('subChapterOrder');
    
        // Mettre à jour le statut à "opened" ssi l'utilisateur connecté est le propriétaire de la fiche
        if (Auth::id() == $exercisesSheet->user_id) {
            $exercisesSheet->status = 'opened';
            $exercisesSheet->save();
        }
    
        return view('exercises_sheet.show', compact('exercisesSheet', 'exercises'));
    }
}
