<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DS;
use App\Models\Chapter;
use App\Models\DsExercise;
use App\Models\MultipleChapter;
use App\Models\CorrectionRequest;
use App\Models\User;
use Illuminate\Pagination\Paginator;
use App\Mail\AssignDSMail;
use Illuminate\Support\Facades\Mail;

class DSController extends Controller
{
    // Méthode pour afficher tous les DS
    public function index(Request $request)
    {
        $sort_by_student = request()->query('sort_by_student');
        $sort_by_status = request()->query('sort_by_status');

        $dsList = DS::query();

        // Check if the request has sort_by_student
        if ($request->filled('sort_by_student')) {
            $dsList = $dsList->orderBy('user_id');
        }

        // Check if the request has sort_by_status
        if ($request->filled('sort_by_status')) {
            $dsList = $dsList->orderByRaw("FIELD(status, 'sent', 'ongoing', 'not_started', 'finished', 'corrected')");
        }

        // Default sort by created_at
        $dsList = $dsList->orderBy('created_at', 'desc');

        // Existing search functionality
        if (request()->query('search')) {
            $dsList = $dsList->where('type_bac', 'like', '%' . request()->query('search') . '%')
                ->orWhere('exercises_number', 'like', '%' . request()->query('search') . '%')
                ->orWhere('status', 'like', '%' . request()->query('search') . '%')
                ->orWhereHas('user', function ($query) {
                    $query->where('name', 'like', '%' . request()->query('search') . '%');
                });
        }

        $dsList = $dsList->paginate(10)->withQueryString();

        return view('ds.index', compact('dsList', 'sort_by_student', 'sort_by_status'));
    }

    // Méthode pour afficher le formulaire de re assign 
    public function reAssignForm($id)
    {
        $ds = DS::find($id);
        $students = User::all();
        return view('ds.reAssign', compact('ds', 'students'));
    }

    // Méthode pour assigner un DS existant (soit avec les mêmes exercices) à un autre élève
    public function reAssign(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'ds_id' => 'required|exists:DS,id',
        ]);

        $oldDs = DS::find($request->input('ds_id'));
        $newDs = $oldDs->replicate();
        $newDs->user_id = $request->input('user_id');

        // Réinitialiser les autres colonnes
        $newDs->chrono = "0";
        $newDs->status = "not_started";

        $newDs->save();

        // Copier les relations d'exercices de l'ancien DS vers le nouveau
        foreach ($oldDs->exercisesDS as $exercise) {
            $newDs->exercisesDS()->attach($exercise->id);
        }
        // Copier les relations de multiple_chapters de l'ancien DS vers le nouveau
        foreach ($oldDs->multipleChapters as $multipleChapter) {
            $newDs->multipleChapters()->attach($multipleChapter->id);
        }
        // Calculer TotalTime comme la somme du temps de tous les exercices
        $totalTime = $newDs->exercisesDS->sum('time');
        $newDs->time = $totalTime;
        $newDs->timer = $totalTime * 60; // timer in seconds
        $newDs->save();

        // Envoyez un e-mail à l'élève
        $student = User::find($request->input('user_id'));
        Mail::to($student->email)->send(new AssignDSMail($newDs));

        return redirect()->route('ds.index');
    }


    // Méthode pour afficher les DS de l'utilisateur connecté
    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('ds.myDS', Auth::id());
        }
        // with chapters and exercisesDS
        $dsList = DS::where('user_id', $id)->orderBy('created_at', 'desc')->paginate(10)->withQueryString();
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
        // $dsList = Auth::user()->ds;
        // $dsList = $dsList->sortByDesc('created_at');
        // foreach ($dsList as $ds) {
        //     foreach ($ds->exercisesDS as $exerciseDS) {
        //         $exerciseDS->multipleChapter = MultipleChapter::find($exerciseDS->multiple_chapter_id);
        //     }
        // }
        return response()->json(['timerAction' => $timerAction, 'ds' => $ds]);
        // return view('ds.myDS', compact('ds', 'timerAction', 'dsList'));
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
        ]);

        if ($ds != null) {
            $ds->multipleChapters()->detach();
            $ds->exercisesDS()->detach();
        }

        // Select all exercises from the selected chapters
        $exercisesDS = [];
        foreach ($request->multiple_chapters as $multiple_chapter) {
            $exercises = MultipleChapter::find($multiple_chapter)->dsExercises;
            $exercises = $request->harder_exercises ? $exercises->where('harder_exercise', 1) : $exercises->where('harder_exercise', 0);
            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }
        // Shuffle the exercisesDS and remove duplicates
        shuffle($exercisesDS);
        $exercisesDS = array_unique($exercisesDS, SORT_REGULAR);

        if ($request->type_bac) {
            foreach ($exercisesDS as $key => $exercise) {
            $exercisesDS[$key]['multipleChapter'] = MultipleChapter::find($exercise['multiple_chapter_id']);
            }
            // Exclude specific multipleChapters
            $excludedChapters = [
            'ARITHMETIQUE (MATHS EXPERTES)',
            'COMPLEXES',
            'MATRICES (MATHS EXPERTES)',
            'Vers la prépa'
            ];
            foreach ($exercisesDS as $key => $exercise) {
            if (in_array($exercise['multipleChapter']['title'], $excludedChapters)) {
                unset($exercisesDS[$key]);
            }
            }
            // Remove duplicates based on the same multipleChapter->theme
            foreach ($exercisesDS as $key => $exercise) {
            foreach ($exercisesDS as $key2 => $exercise2) {
                if ($key != $key2 && $exercise['multipleChapter']['theme'] == $exercise2['multipleChapter']['theme']) {
                unset($exercisesDS[$key]);
                }
            }
            }
        } else {
            // Créez un tableau pour stocker les id des chapitres déjà sélectionnés
            $selectedChapters = [];
            $selectedExercises = [];

            // Récupérez tous les chapitres en une seule requête
            $allChapters = MultipleChapter::all();

            foreach ($exercisesDS as $key => $exercise) {
                $multipleChapter = $allChapters->firstWhere('id', $exercise['multiple_chapter_id']);
                if ($multipleChapter) {
                    // Si le chapitre n'a pas encore été sélectionné, ajoutez l'exercice
                    if (!in_array($multipleChapter->id, $selectedChapters)) {
                        $selectedChapters[] = $multipleChapter->id;
                        $selectedExercises[] = $exercise;
                        unset($exercisesDS[$key]);
                    }
                } else {
                    throw new \Exception("Le chapitre de l'exercice n'a pas été trouvé");
                }
            }
            // Si nous n'avons pas encore atteint le nombre d'exercices demandé, ajoutez des exercices supplémentaires
            while (count($selectedExercises) < $request->exercises_number && count($exercisesDS) > 0) {
                $selectedExercises[] = array_shift($exercisesDS);
            }
            $exercisesDS = $selectedExercises;
        }

        // If there are fewer exercises than the number of exercises, select the number of exercises we have and reduce the number of exercises
        $exercisesDS = array_slice($exercisesDS, 0, min(count($exercisesDS), $request->exercises_number));

        // Calculate the total time of all exercises
        $TotalTime = array_sum(array_column($exercisesDS, 'time'));

        // il y aura la plus part du temps plus de multiple_chapters que nécessaire
        // on va donc sélectionner seulement les multiple_chapters des exos sélectionnés
        $multiple_chapters = array_unique(array_column($exercisesDS, 'multiple_chapter_id'));

        // Store only the id of the exercisesDS
        $exercisesDS = array_column($exercisesDS, 'id');

        $user = Auth::user();

        if ($ds == null) { // if we are creating a new DS

            // check if user last_ds_generated_at was today
            if ($user->last_ds_generated_at != null) {
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
        $ds->exercises_number = count($exercisesDS);
        $ds->harder_exercises = $request->has('harder_exercises') ? true : false;
        $ds->time = $TotalTime;
        $ds->timer = $TotalTime * 60; // timer in seconds
        $ds->chrono = "0";
        $ds->status = "not_started";
        $ds->save();

        // Attach the multiple chapters and exercises to the DS
        $ds->multipleChapters()->attach($multiple_chapters);
        $ds->exercisesDS()->attach($exercisesDS);

        // Update the user's last_ds_generated_at property and save the user
        $user->last_ds_generated_at = ($user->role == 'admin' || $user->role == 'teacher') ? null : now();
        $user->save();

        return $ds;
    }

    // méthode pour créer un ds manuellement et l'assigner à un élève
    public function assignDS(Request $request)
    {
        if ($request->isMethod('post')) {
            $request->validate([
                'exercisesDS' => 'required|array',
                'exercisesDS.*' => 'exists:ds_exercises,id',
                'user_id' => 'required|exists:users,id',
            ]);

            // Récupérez les IDs des exercices sélectionnés
            $exercisesDSIds = $request->input('exercisesDS');

            $number_exercises = count($exercisesDSIds);

            // Récupérez les exercices correspondants de la base de données
            $exercises = DsExercise::findMany($exercisesDSIds);

            // récupérez les multiple_chapters_id des exercices sélectionnés
            $multiple_chapters = array_unique(array_column($exercises->toArray(), 'multiple_chapter_id'));
            // Calculez la somme de leur temps
            $time = $exercises->sum('time');

            // Créez un nouveau DS avec les exercices sélectionnés
            $ds = new DS;
            $ds->user_id = $request->input('user_id');
            $ds->type_bac = false;
            $ds->exercises_number = $number_exercises;
            $ds->harder_exercises = false;
            $ds->time = $time; // Ajoutez cette ligne
            $ds->timer = $time * 60; // timer in seconds
            $ds->chrono = 10;
            $ds->status = "not_started";
            $ds->save();

            $ds->multipleChapters()->attach($multiple_chapters);
            $ds->exercisesDS()->attach($exercisesDSIds);

            // Envoyez un e-mail à l'élève
            $student = User::find($request->input('user_id'));
            Mail::to($student->email)->send(new AssignDSMail($ds));

            return redirect()->route('students.show')->with('success', 'DS assigned successfully');
        }

        // Récupérez tous les exercices et tous les utilisateurs
        $exercises = DsExercise::with('multipleChapter')->get();
        $users = User::all();
        $student = $request->input('student_id');

        // Passez les données à la vue
        return view('ds.assign', compact('exercises', 'users', 'student'));
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
