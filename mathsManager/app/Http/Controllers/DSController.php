<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DS;
use App\Models\Chapter;
use App\Models\DsExercise;
use App\Models\MultipleChapter;

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
        /// sorted by most recent DS first
        $dsList = $dsList->sortByDesc('created_at');
        // get the multipleChapters data foreach exerciseDS
        foreach ($dsList as $ds) {
            foreach ($ds->exercisesDS as $exerciseDS) {
                $exerciseDS->multipleChapter = MultipleChapter::find($exerciseDS->multiple_chapter_id);
            }
        }

        return view('ds.myDS', compact('dsList'));
    }

    // Méthode pour afficher un DS
    public function show($id)
    {
        $ds = DS::find($id);
        // get the exercisesDS data foreach exerciseDS
        return view('ds.show', compact('ds'));
    }

    // Méthode pour créer un DS
    public function create()
    {
        $chapters = MultipleChapter::all();
        return view('ds.create', compact('chapters'));
    }

    // Méthode pour enregistrer un DS
    public function store(Request $request)
    {
        $ds = $this->generateDS($request);
        return redirect()->route('ds.myDS');
    }

    // private function for générate DS like in store method
    private function generateDS(Request $request, DS $ds = null)
    {
        $request->validate([
            'type_bac' => 'boolean',
            'exercises_number' => 'required|integer|min:1|max:4',
            'harder_exercises' => 'boolean',
            'multiple_chapters' => 'required|array',
            'multiple_chapters.*' => 'exists:multiple_chapters,id',
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id', 
        ]);

        if ($ds != null) {
            $ds->multipleChapters()->detach();
            $ds->exercisesDS()->detach();
        }

        // we will select "exercices_number" ds_exercises randomly from the chapters selected
        $exercisesDS = [];
        // for each chapter selected, we will find all the exercises from the chapter
        foreach ($request->multiple_chapters as $multiple_chapter) {
            // find all the exercises from the chapter
            $exercises = MultipleChapter::find($multiple_chapter)->dsExercises;
            // if harder_exercises is not checked, we will select only the exercises with harder_exercise = 0
            if (!$request->harder_exercises) {
                $exercises = $exercises->where('harder_exercise', 0);
            }
            // if harder_exercises is checked, we will select only the exercises with harder_exercise = 1
            else {
                $exercises = $exercises->where('harder_exercise', 1);
            }
            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }
        // shuffle the exercisesDS
        shuffle($exercisesDS);
        // supprimer les exercisesDS qui ont le même id
        foreach ($exercisesDS as $key => $exercise) {
            foreach ($exercisesDS as $key2 => $exercise2) {
                if ($key != $key2 && $exercise['id'] == $exercise2['id']) {
                    unset($exercisesDS[$key]);
                }
            }
        }
        if ($request->type_bac) {
            foreach ($exercisesDS as $key => $exercise) {
                $exercisesDS[$key]['multipleChapter'] = MultipleChapter::find($exercise['multiple_chapter_id']);
            }
            // dans tous les exercisesDS, on va chercher les exercises qui ont le même multipleChapter->theme et supprimer les doublons
            foreach ($exercisesDS as $key => $exercise) { // on aura donc des exos avec couleurs différentes affichés sur les labels
                foreach ($exercisesDS as $key2 => $exercise2) {
                    if ($key != $key2 && $exercise['multipleChapter']['theme'] == $exercise2['multipleChapter']['theme']) {
                        unset($exercisesDS[$key]);
                    }
                }
            }
        }
        // si on a moins d'exercises que le nombre d'exercises, on va sélectionner le nombre d'exercises qu'on a et réduire le nombre d'exercises
        if (count($exercisesDS) < $request->exercises_number) {
            $new_exercises_number = count($exercisesDS);
        }
        // selectionner soit $new_exercises_number soit $request->exercises_number
        $exercisesDS = array_slice($exercisesDS, 0, $new_exercises_number ?? $request->exercises_number);

        // somme de time de tous les exercisesDS pour avoir le temps total du DS
        $TotalTime = 0;
        foreach ($exercisesDS as $exercise) {
            $TotalTime += $exercise['time'];
        }

        // il y aura la plus part du temps plus de multiple_chapters que nécessaire
        // on va donc sélectionner seulement les multiple_chapters des exos sélectionnés
        $multiple_chapters = [];
        foreach ($exercisesDS as $exercise) {
            $multiple_chapters[] = $exercise['multiple_chapter_id'];
        }
        $multiple_chapters = array_unique($multiple_chapters);

        // we will store only the id of the exercisesDS
        $exercisesDS = array_map(function ($exercise) {
            return $exercise['id'];
        }, $exercisesDS);

        if ($ds == null) {
            $ds = new DS;
        }
        $ds->type_bac =  $request->has('type_bac') ? true : false;
        $ds->exercises_number = $new_exercises_number ?? $request->exercises_number;
        $ds->harder_exercises = $request->has('harder_exercises') ? true : false;
        $ds->time = $TotalTime;
        $ds->timer = $TotalTime;
        $ds->chrono = "0";
        $ds->status = "not_started";
        $ds->user_id = Auth::id();
        $ds->save();

        // $ds->chapters()->attach($request->chapters);
        $ds->multipleChapters()->attach($multiple_chapters);
        $ds->exercisesDS()->attach($exercisesDS);

        return $ds;
    }

    // Méthode pour éditer un DS
    public function edit($id)
    {
        $ds = DS::find($id);
        $chapters = MultipleChapter::all();
        return view('ds.edit', compact('ds', 'chapters'));
    }

    // Méthode pour mettre à jour un DS
    public function update(Request $request, $id)
    {
        $ds = DS::find($id);
        $ds = $this->generateDS($request, $ds);
        return redirect()->route('ds.myDS');
    }

    // Méthode pour supprimer un DS
    public function destroy($id)
    {
        $ds = DS::find($id);
        $ds->delete();
        return redirect()->route('ds.myDS');
    }
}
