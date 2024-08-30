<?php

namespace App\Http\Controllers;

use App\Models\ExercisesSheet;
use App\Models\Exercise;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

    // Méthode pour afficher le formulaire de réassignation d'une fiche d'exercices
    public function reAssignForm($id)
    {
        $exercisesSheet = ExercisesSheet::find($id);
        $students = User::all();
        return view('exercises_sheet.reAssign', compact('exercisesSheet', 'students'));
    }

    // Méthode pour réassigner une fiche d'exercices existante à un autre élève
    public function reAssign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'exercises_sheet_id' => 'required|exists:exercises_sheet,id',
        ]);

        $oldExercisesSheet = ExercisesSheet::find($request->input('exercises_sheet_id'));
        $newExercisesSheet = $oldExercisesSheet->replicate();
        $newExercisesSheet->user_id = $request->input('user_id');
        $newExercisesSheet->save();

        // Copier les exercices associés à l'ancienne fiche vers la nouvelle
        foreach ($oldExercisesSheet->exercises as $exercise) {
            $newExercisesSheet->exercises()->attach($exercise->id);
        }

        // Envoyer un e-mail à l'élève (optionnel, à implémenter)

        return redirect()->route('exercises_sheet.index')->with('success', 'Fiche d\'exercices réassignée avec succès.');
    }

    // Méthode pour afficher les fiches d'exercices de l'utilisateur connecté
    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('exercises_sheet.mySheets', Auth::id());
        }

        $exercisesSheetList = ExercisesSheet::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

        return view('exercises_sheet.mySheets', compact('exercisesSheetList'));
    }

    // Méthode pour afficher une fiche d'exercices
    public function show($id)
    {
        $exercisesSheet = ExercisesSheet::find($id);
        return view('exercises_sheet.show', compact('exercisesSheet'));
    }
}
