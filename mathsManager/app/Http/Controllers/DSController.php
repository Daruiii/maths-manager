<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DS;
use App\Models\DsExercise;
use App\Models\MultipleChapter;
use App\Models\User;
use App\Mail\AssignDSMail;
use Illuminate\Support\Facades\Mail;
use App\Http\Requests\DS\ReAssignDSRequest;
use App\Http\Requests\DS\StoreDSRequest;
use App\Http\Requests\DS\UpdateDSRequest;
use App\Http\Requests\DS\AssignDSRequest;

class DSController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;
    protected \App\Services\DSGenerationService $dsGenerationService;
    protected \App\Services\TimerFormattingService $timerService;

    public function __construct(
        \App\Services\FileUploadService $fileUploadService,
        \App\Services\DSGenerationService $dsGenerationService,
        \App\Services\TimerFormattingService $timerService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->dsGenerationService = $dsGenerationService;
        $this->timerService = $timerService;
    }

    // Méthode pour afficher tous les DS
    public function index(Request $request)
    {
        $sort_by_student = request()->query('sort_by_student');
        $sort_by_status = request()->query('sort_by_status');

        $dsList = DS::query()->with(['exercisesDS.multipleChapter', 'user', 'correctionRequest']);

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
    public function reAssign(ReAssignDSRequest $request)
    {

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
        try {
            Mail::to($student->email)->send(new AssignDSMail($newDs));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email DS re-assign: ' . $e->getMessage());
        }

        return redirect()->route('ds.index')->with('success', 'DS réassigné avec succès.');
    }


    // Méthode pour afficher les DS de l'utilisateur connecté
    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('ds.myDS', Auth::id());
        }
        // Eager loading pour éviter N+1 queries (fix #14.2)
        $dsList = DS::where('user_id', $id)
            ->with([
                'exercisesDS.multipleChapter',  // Charge exercices + leurs chapitres
                'correctionRequest'              // Charge les demandes de correction
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ds.myDS', compact('dsList'));
    }

    // Méthode pour afficher un DS
    public function show($id)
    {
        $ds = DS::find($id);

        if (!$ds) {
            return redirect()->route('ds.myDS', Auth::id())->with('error', 'DS non trouvé.');
        }

        $timerFormatted = $this->timerService->format($ds->timer);
        $timerAction = "show";
        return view('ds.show', compact('ds', 'timerFormatted', 'timerAction'));
    }

    // Méthode pour démarrer un DS
    public function start($id)
    {
        $ds = DS::find($id);
        $timerFormatted = $this->timerService->format($ds->timer);
        $ds->status = "ongoing";
        $ds->save();
        $timerAction = "start";

        return view('ds.show', compact('ds', 'timerAction', 'timerFormatted'));
    }

    // Méthode pour mettre en pause un DS
    public function pause($id, $timer)
    {
        $timerInSeconds = $this->timerService->parseToSeconds($timer);
        $ds = DS::find($id);
        $ds->timer = $timerInSeconds;
        // timer = 0 means the DS is finished so set status to finished
        if ($timerInSeconds == 0) {
            $ds->status = "finished";
        }
        $ds->save();
        $timerAction = "pause";
        return response()->json(['timerAction' => $timerAction, 'ds' => $ds]);
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
    public function store(StoreDSRequest $request)
    {
        try {
            $ds = $this->dsGenerationService->generate($request, null, Auth::user());
            return redirect()->route('ds.myDS', Auth::id());
        } catch (\DomainException $e) {
            return redirect()->route('ds.create')->with('error', $e->getMessage());
        }
    }

    // méthode pour afficher le formulaire d'assignation manuelle de DS
    public function assignDSForm(Request $request)
    {
        // Récupérez tous les exercices et tous les utilisateurs
        $exercises = DsExercise::with('multipleChapter')->get();
        $users = User::all();
        $student = $request->input('student_id');

        // Passez les données à la vue
        return view('ds.assign', compact('exercises', 'users', 'student'));
    }

    // méthode pour créer un ds manuellement et l'assigner à un élève
    public function assignDS(AssignDSRequest $request)
    {
        // Récupérez les IDs des exercices sélectionnés
        $exercisesDSIds = $request->input('exercisesDS');

        $number_exercises = count($exercisesDSIds);

        // Récupérez les exercices correspondants de la base de données
        $exercises = DsExercise::findMany($exercisesDSIds);

        // récupérez les multiple_chapters_id des exercices sélectionnés
        $multiple_chapters = array_unique(array_column($exercises->toArray(), 'multiple_chapter_id'));
        // Calculez la somme de leur temps
        $time = $exercises->sum('time');

        $ds = new DS;
        $ds->user_id = $request->input('user_id');
        $ds->type_bac = false;
        $ds->exercises_number = $number_exercises;
        $ds->harder_exercises = false;
        $ds->time = $time;
        $ds->timer = $time * 60;
        $ds->chrono = 10;
        $ds->status = "not_started";
        $ds->save();

        $ds->multipleChapters()->attach($multiple_chapters);
        $ds->exercisesDS()->attach($exercisesDSIds);

        $student = User::find($request->input('user_id'));
        try {
            Mail::to($student->email)->send(new AssignDSMail($ds));
        } catch (\Exception $e) {
            \Log::error('Erreur envoi email DS assign: ' . $e->getMessage());
        }

        return redirect()->route('students.show')->with('success', 'DS assigné avec succès.');
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
    public function update(UpdateDSRequest $request, $id)
    {
        $ds = DS::find($id);
        $this->dsGenerationService->generate($request, $ds, Auth::user());
        return redirect()->route('ds.myDS', Auth::id());
    }

    // Méthode pour supprimer un DS
    public function destroy($id)
    {
        $ds = DS::find($id);

        // Supprimer le dossier de correction s'il existe
        $this->fileUploadService->deleteDirectory('corrections', 'ds-' . $id, false);

        $ds->delete();

        return redirect()->route('ds.index');
    }
}
