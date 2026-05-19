<?php

namespace App\Http\Controllers\DS;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use App\Models\DS;
use App\Models\Problem;
use App\Models\MultipleChapter;
use App\Models\User;
use App\Mail\AssignDSMail;
use App\Helpers\ErrorResponseHelper;
use App\Http\Requests\DS\ReAssignDSRequest;
use App\Http\Requests\DS\StoreDSRequest;
use App\Http\Requests\DS\UpdateDSRequest;
use App\Http\Requests\DS\AssignDSRequest;

class DSManagementController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;
    protected \App\Services\DSGenerationService $dsGenerationService;

    public function __construct(
        \App\Services\FileUploadService $fileUploadService,
        \App\Services\DSGenerationService $dsGenerationService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->dsGenerationService = $dsGenerationService;
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
        $ds = DS::find($request->ds_id);

        // Créer un nouveau DS avec les mêmes caractéristiques
        $newDs = $ds->replicate();
        $newDs->user_id = $request->user_id;
        $newDs->status = "not_started";
        $newDs->save();

        // Copier les relations many-to-many
        $newDs->multipleChapters()->attach($ds->multipleChapters->pluck('id'));
        $newDs->problems()->attach($ds->problems->pluck('id'));

        $student = User::find($request->user_id);
        try {
            Mail::to($student->email)->send(new AssignDSMail($newDs));
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, 'DS re-assign');
        }

        return redirect()->route('students.show')->with('success', 'DS réassigné avec succès.');
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
    public function assignForm(Request $request)
    {
        // Récupérez tous les exercices et tous les utilisateurs
        $exercises = Problem::with('multipleChapter')->get();
        $users = User::all();
        $student = $request->input('student_id');

        // Passez les données à la vue
        return view('ds.assign', compact('exercises', 'users', 'student'));
    }

    // méthode pour créer un ds manuellement et l'assigner à un élève
    public function assign(AssignDSRequest $request)
    {
        // Récupérez les IDs des exercices sélectionnés
        $problemsIds = $request->input('problems');

        $number_exercises = count($problemsIds);

        // Récupérez les exercices correspondants de la base de données
        $exercises = Problem::findMany($problemsIds);

        // récupérez les multiple_chapters_id des exercices sélectionnés
        $multiple_chapters = array_unique(array_column($exercises->toArray(), 'multiple_chapter_id'));
        // Calculez la somme de leur temps
        $time = $exercises->sum('time');

        $ds = new DS;
        $ds->user_id = $request->input('user_id');
        $ds->teacher_id = Auth::id();
        $ds->type_bac = false;
        $ds->exercises_number = $number_exercises;
        $ds->harder_exercises = false;
        $ds->time = $time;
        $ds->timer = $time * 60;
        $ds->chrono = 10;
        $ds->status = "not_started";
        $ds->save();

        $ds->multipleChapters()->attach($multiple_chapters);
        $ds->problems()->attach($problemsIds);

        $student = User::find($request->input('user_id'));
        try {
            Mail::to($student->email)->send(new AssignDSMail($ds));
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, 'DS assign');
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
