<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DS;
use App\Models\Chapter;
use App\Models\DsExercise;

class DSController extends Controller
{
    // Méthode pour afficher tous les DS
    public function index()
    {
        // with chapters and exercisesDS
        $dsList = DS::all();
        return view('ds.index', compact('dsList'));
    }

    // Méthode pour afficher un DS
    public function show($id)
    {
        $ds = DS::find($id);
        return view('ds.show', compact('ds'));
    }

    // Méthode pour créer un DS
    public function create()
    {
        return view('ds.create');
    }

    // Méthode pour enregistrer un DS
    public function store(Request $request)
    {
        $request->validate([
            'type_bac' => 'boolean',
            'exercises_number' => 'required|integer|min:1|max:4',
            'harder_exercises' => 'boolean',
            'chapters' => 'required|array',
            'chapters.*' => 'exists:chapters,id', 
        ]);

        // we will select "exercices_number" ds_exercises randomly from the chapters selected
        $exercisesDS = [];
        foreach ($request->chapters as $chapter) {
            $exercises = Chapter::find($chapter)->dsExercises;
            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }
        shuffle($exercisesDS);
        $exercisesDS = array_slice($exercisesDS, 0, $request->exercises_number);

        // somme de time de tous les exercisesDS pour avoir le temps total du DS
        $TotalTime = 0;
        foreach ($exercisesDS as $exercise) {
            $TotalTime += $exercise['time'];
        }

        $ds = new DS;
        $ds->type_bac = $request->type_bac;
        $ds->exercises_number = $request->exercises_number;
        $ds->harder_exercises = $request->harder_exercises;
        $ds->chapters = $request->chapters;
        $ds->exercisesDS = $exercisesDS;
        $ds->time = $TotalTime;
        $ds->status = "ongoing";
        $ds->save();

        return redirect()->route('ds.show', $ds->id);
    }

    // Méthode pour éditer un DS
    public function edit($id)
    {
        $ds = DS::find($id);
        return view('ds.edit', compact('ds'));
    }

    // Méthode pour mettre à jour un DS
    public function update(Request $request, $id)
    {
        $request->validate([
            'type_bac' => 'boolean',
            'exercises_number' => 'required|integer|min:1|max:4',
            'harder_exercises' => 'boolean',
            'chapters' => 'required|array',
            'chapters.*' => 'exists:chapters,id', 
        ]);

        // we will select "exercices_number" ds_exercises randomly from the chapters selected
        $exercisesDS = [];
        foreach ($request->chapters as $chapter) {
            $exercises = Chapter::find($chapter)->dsExercises;
            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }
        shuffle($exercisesDS);
        $exercisesDS = array_slice($exercisesDS, 0, $request->exercises_number);

        // somme de time de tous les exercisesDS pour avoir le temps total du DS
        $TotalTime = 0;
        foreach ($exercisesDS as $exercise) {
            $TotalTime += $exercise['time'];
        }

        $ds = DS::find($id);
        $ds->type_bac = $request->type_bac;
        $ds->exercises_number = $request->exercises_number;
        $ds->harder_exercises = $request->harder_exercises;
        $ds->chapters = $request->chapters;
        $ds->exercisesDS = $exercisesDS;
        $ds->time = $TotalTime;
        $ds->status = "ongoing";
        $ds->save();

        return redirect()->route('ds.show', $ds->id);
    }

    // Méthode pour supprimer un DS
    public function destroy($id)
    {
        $ds = DS::find($id);
        $ds->delete();
        return redirect()->route('ds.index');
    }
}
