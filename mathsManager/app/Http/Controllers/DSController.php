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

    // Méthode pour afficher les DS de l'utilisateur connecté
    public function indexUser()
    {
        // with chapters and exercisesDS
        $dsList = Auth::user()->ds;
        return view('ds.myDS', compact('dsList'));
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
        $chapters = Chapter::all();
        return view('ds.create', compact('chapters'));
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
            // find a random exercice from the chapter
            $exercises = Chapter::find($chapter)->dsExercises;
            // if harder_exercises is not checked, we will select only the exercises with harder_exercise = 0
            if (!$request->harder_exercises) {
                $exercises = $exercises->where('harder_exercise', 0);
            }
            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }
        shuffle($exercisesDS);
        // supprimer les exercisesDS qui ont le même id
        foreach ($exercisesDS as $key => $exercise) {
            foreach ($exercisesDS as $key2 => $exercise2) {
                if ($key != $key2 && $exercise['id'] == $exercise2['id']) {
                    unset($exercisesDS[$key]);
                }
            }
        }
        // if we have less exercises than the exercises_number, we will only select the number of exercises we have and reduce the exercises_number
        if (count($exercisesDS) < $request->exercises_number) {
            $new_exercises_number = count($exercisesDS);
        }
        $exercisesDS = array_slice($exercisesDS, 0, $new_exercises_number ?? $request->exercises_number);

        // somme de time de tous les exercisesDS pour avoir le temps total du DS
        $TotalTime = 0;
        foreach ($exercisesDS as $exercise) {
            $TotalTime += $exercise['time'];
        }
        // we will store only the id of the exercisesDS
        $exercisesDS = array_map(function ($exercise) {
            return $exercise['id'];
        }, $exercisesDS);

        $ds = new DS;
        $ds->type_bac =  $request->has('type_bac') ? true : false;
        $ds->exercises_number = $new_exercises_number ?? $request->exercises_number;
        $ds->harder_exercises = $request->has('harder_exercises') ? true : false;
        $ds->time = $TotalTime;
        $ds->timer = "0";
        $ds->chrono = "0";
        $ds->status = "ongoing";
        $ds->user_id = Auth::id();
        $ds->save();

        $ds->chapters()->attach($request->chapters);
        $ds->exercisesDS()->attach($exercisesDS);

        return redirect()->route('ds.index');
    }

    // Méthode pour éditer un DS
    public function edit($id)
    {
        $ds = DS::find($id);
        $chapters = Chapter::all();
        return view('ds.edit', compact('ds', 'chapters'));
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
            // find a random exercice from the chapter
            $exercises = Chapter::find($chapter)->dsExercises;
            // if harder_exercises is not checked, we will select only the exercises with harder_exercise = 0
            if (!$request->harder_exercises) {
                $exercises = $exercises->where('harder_exercise', 0);
            }
            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }
        shuffle($exercisesDS);
        // supprimer les exercisesDS qui ont le même id
        foreach ($exercisesDS as $key => $exercise) {
            foreach ($exercisesDS as $key2 => $exercise2) {
                if ($key != $key2 && $exercise['id'] == $exercise2['id']) {
                    unset($exercisesDS[$key]);
                }
            }
        }
        // if we have less exercises than the exercises_number, we will only select the number of exercises we have and reduce the exercises_number
        if (count($exercisesDS) < $request->exercises_number) {
            $new_exercises_number = count($exercisesDS);
        }
        $exercisesDS = array_slice($exercisesDS, 0, $new_exercises_number ?? $request->exercises_number);

        // somme de time de tous les exercisesDS pour avoir le temps total du DS
        $TotalTime = 0;
        foreach ($exercisesDS as $exercise) {
            $TotalTime += $exercise['time'];
        }
        // we will store only the id of the exercisesDS
        $exercisesDS = array_map(function ($exercise) {
            return $exercise['id'];
        }, $exercisesDS);

        $ds = DS::find($id);
        $ds->type_bac = $request->has('type_bac') ? true : false;
        $ds->exercises_number = $new_exercises_number ?? $request->exercises_number;
        $ds->harder_exercises = $request->has('harder_exercises') ? true : false;
        $ds->time = $TotalTime;
        $ds->timer = "0";
        $ds->chrono = "0";
        $ds->status = "ongoing";
        $ds->user_id = Auth::id();
        $ds->save();

        $ds->chapters()->sync($request->chapters);
        $ds->exercisesDS()->sync($exercisesDS);

        return redirect()->route('ds.index');
    }

    // Méthode pour supprimer un DS
    public function destroy($id)
    {
        $ds = DS::find($id);
        $ds->delete();
        return redirect()->route('ds.index');
    }
}
