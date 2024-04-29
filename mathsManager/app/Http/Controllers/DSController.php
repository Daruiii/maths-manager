<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DS;
use App\Models\Chapter;
use App\Models\DsExercise;
use App\Models\MultipleChapter;
use App\Models\CorrectionRequest;

class DSController extends Controller
{
    // Méthode pour afficher tous les DS
    public function index()
    {
        // search func by user name 
        if (request()->query('search')) {
            $dsList = DS::where('type_bac', 'like', '%' . request()->query('search') . '%')
                ->orWhere('exercises_number', 'like', '%' . request()->query('search') . '%')
                ->orWhere('status', 'like', '%' . request()->query('search') . '%')
                ->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . request()->query('search') . '%');
                })
                ->get();
        }
        else {
            $dsList = DS::all();
        }
        return view('ds.index', compact('dsList'));
    }

    // Méthode pour afficher les DS de l'utilisateur connecté
    public function indexUser($id)
    {
        // with chapters and exercisesDS
        $dsList = DS::where('user_id', $id)->get();
        $dsList = $dsList->sortByDesc('created_at');
        foreach ($dsList as $ds) {
            foreach ($ds->exercisesDS as $exerciseDS) {
                $exerciseDS->multipleChapter = MultipleChapter::find($exerciseDS->multiple_chapter_id);
            }
        }
        // get the corrections data, ds are linked to correction requests
        $correctionRequests = CorrectionRequest::all();
        foreach ($dsList as $ds) {
            foreach ($correctionRequests as $correctionRequest) {
                if ($ds->id == $correctionRequest->ds_id) {
                    $ds->correctionRequest = $correctionRequest;
                }
            }
        }

        return view('ds.myDS', compact('dsList'));
    }

    // Méthode pour afficher un DS
    public function show($id)
    {
        $ds = DS::find($id);
        $timerFormatted = $this->formatTimer($ds->timer);
        $timerAction = "show";
        return view('ds.show', compact('ds', 'timerFormatted', 'timerAction'));
    }

    private function formatTimer($timerInSeconds)
    {
        $hours = floor($timerInSeconds / 3600);
        $minutes = floor(($timerInSeconds - $hours * 3600) / 60);
        $seconds = $timerInSeconds - $hours * 3600 - $minutes * 60;
        $timerFormatted = "";
        if ($hours < 10) {
            $timerFormatted .= "0" . $hours . ":";
        } else {
            $timerFormatted .= $hours . ":";
        }
        if ($minutes < 10) {
            $timerFormatted .= "0" . $minutes . ":";
        } else {
            $timerFormatted .= $minutes . ":";
        }
        if ($seconds < 10) {
            $timerFormatted .= "0" . $seconds;
        } else {
            $timerFormatted .= $seconds;
        }
        return $timerFormatted;
    }

    // Méthode pour démarrer un DS
    public function start($id)
    {
        $ds = DS::find($id);
        $timerFormatted = $this->formatTimer($ds->timer);
        $ds->status = "ongoing";
        $ds->save();
        $timerAction = "start";

        return view('ds.show', compact('ds', 'timerAction', 'timerFormatted'));
    }

    private function resetTimerToSeconds($timer)
    {
        $timerArray = explode(":", $timer);
        // convert timer to seconds
        $timerInSeconds = $timerArray[0] * 3600 + $timerArray[1] * 60 + $timerArray[2];
        return $timerInSeconds;
    }

    // Méthode pour mettre en pause un DS
    public function pause($id, $timer)
    {
        $timerInSeconds = $this->resetTimerToSeconds($timer);
        $ds = DS::find($id);
        $ds->timer = $timerInSeconds;
        // timer = 0 means the DS is finished so set status to finished
        if ($timerInSeconds == 0) {
            $ds->status = "finished";
        }
        $ds->save();
        $timerAction = "pause";
        // get list of user's DS
        $dsList = Auth::user()->ds;
        $dsList = $dsList->sortByDesc('created_at');
        foreach ($dsList as $ds) {
            foreach ($ds->exercisesDS as $exerciseDS) {
                $exerciseDS->multipleChapter = MultipleChapter::find($exerciseDS->multiple_chapter_id);
            }
        }
        return view('ds.myDS', compact('ds', 'timerAction', 'dsList'));
    }

    public function finish($id)
    {
        $ds = DS::find($id);
        $ds->status = "finished";
        $ds->timer = 0;
        $ds->save();
        return redirect()->route('ds.myDS', Auth::id());
    }

    // Méthode pour créer un DS
    public function create()
    {
        $chapters = MultipleChapter::where('title', 'not like', 'BONUS%')->get();
        return view('ds.create', compact('chapters'));
    }

    // Méthode pour enregistrer un DS
    public function store(Request $request)
    {
        $ds = $this->generateDS($request);
        return redirect()->route('ds.myDS', Auth::id());
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

        $user = Auth::user();

        if ($ds == null) { // if we are creating a new DS
          
            // check if user last_ds_generated_at was today
            if ($user->last_ds_generated_at != null ){
                $last_ds_generated_at = new \DateTime($user->last_ds_generated_at);
                $today = new \DateTime();
                if ($last_ds_generated_at->format('Y-m-d') == $today->format('Y-m-d')) {
                return redirect()->route('ds.create')->with('error', 'Vous avez déjà généré un DS aujourd\'hui');
            }
            }
            $ds = new DS;
            $ds->user_id = Auth::id();
        }
        $ds->type_bac =  $request->has('type_bac') ? true : false;
        $ds->exercises_number = $new_exercises_number ?? $request->exercises_number;
        $ds->harder_exercises = $request->has('harder_exercises') ? true : false;
        $ds->time = $TotalTime;
        $ds->timer = $TotalTime * 60; // timer in seconds
        $ds->chrono = "0";
        $ds->status = "not_started";
        $ds->save();

        // $ds->chapters()->attach($request->chapters);
        $ds->multipleChapters()->attach($multiple_chapters);
        $ds->exercisesDS()->attach($exercisesDS);

        $user->last_ds_generated_at = now();
        $user->save();

        return $ds;
    }

    // Méthode pour éditer un DS
    public function edit($id)
    {
        $ds = DS::find($id);
        // all chpaters but not them who start by BONUS
        $chapters = MultipleChapter::where('title', 'not like', 'BONUS%')->get();
        return view('ds.edit', compact('ds', 'chapters'));
    }

    // Méthode pour mettre à jour un DS
    public function update(Request $request, $id)
    {
        $ds = DS::find($id);
        $ds = $this->generateDS($request, $ds);
        return redirect()->route('ds.myDS', Auth::id());
    }

    private function destroyCorrectionFolder($id)
    {
        // there is a folder correction in the folder
        // $path = public_path('storage/correctionRequests/' . $id . '/correction');
        // $path2 = public_path('storage/correctionRequests/' . $id);
        $path = file_exists(public_path('storage/correctionRequests/' . $id . '/correction')) ? public_path('storage/correctionRequests/' . $id . '/correction') : null;
        $path2 = file_exists(public_path('storage/correctionRequests/' . $id)) ? public_path('storage/correctionRequests/' . $id) : null;
         // foreach path != null, delete the content of the folder and the folder
        if ($path != null) {
            $files = glob($path . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path);
        }
        if ($path2 != null) {
            $files = glob($path2 . '/*');
            foreach ($files as $file) {
                if (is_file($file)) {
                    unlink($file);
                }
            }
            rmdir($path2);
        }
    }

    // Méthode pour supprimer un DS
    public function destroy($id)
    {
        // if correction exists, delete his pictures folder
        $this->destroyCorrectionFolder($id);
        $ds = DS::find($id);
        $ds->delete();

        return redirect()->route('ds.index');
    }
}
