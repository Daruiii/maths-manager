<?php

namespace App\Http\Controllers\DS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DsExercise;
use App\Models\Chapter;
use App\Models\MultipleChapter;
use App\Services\LatexToHtmlConverter;
use App\Http\Requests\DsExercise\StoreDsExerciseRequest;
use App\Http\Requests\DsExercise\UpdateDsExerciseRequest;

class DsExerciseController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;
    protected \App\Services\ImageManagementService $imageManagementService;
    protected \App\Services\QueryFiltersService $queryFiltersService;

    public function __construct(
        \App\Services\FileUploadService $fileUploadService,
        \App\Services\ImageManagementService $imageManagementService,
        \App\Services\QueryFiltersService $queryFiltersService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->imageManagementService = $imageManagementService;
        $this->queryFiltersService = $queryFiltersService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $dsExercises = DsExercise::with('chapters')->orderBy('created_at', 'desc');

        // Appliquer la recherche via le service
        $dsExercises = $this->queryFiltersService->applySearch($dsExercises, $search, ['name', 'id']);

        // Appliquer les filtres dynamiques via le service (remplace les 51 lignes de duplication)
        $filterFields = [
            'multiple_chapter_id' => 'multiple_chapter_id',
            'type' => 'type',
            'academy' => 'academy',
        ];
        $dsExercises = $this->queryFiltersService->applyFilters($dsExercises, $request, $filterFields);

        // Récupérer les filtres actifs pour la vue
        $activeFilters = $this->queryFiltersService->getActiveFilters($request, $filterFields);

        // Construire les variables pour la vue
        $filterActivated = isset($activeFilters['multiple_chapter_id']);
        $chapterActivated = $filterActivated
            ? MultipleChapter::findOrFail($activeFilters['multiple_chapter_id'])
            : null;

        $typeActivated = $activeFilters['type'] ?? null;
        $typeFilterActivated = !empty($activeFilters['type']);

        $academyActivated = $activeFilters['academy'] ?? null;
        $academyFilterActivated = !empty($activeFilters['academy']);

        $academies = DsExercise::distinct('academy')->pluck('academy');
        $dsExercises = $dsExercises->paginate(10)->withQueryString();
        $multipleChapters = MultipleChapter::all();

        return view('dsExercise.index', compact('dsExercises', 'multipleChapters', 'filterActivated', 'chapterActivated',
        'typeActivated', 'typeFilterActivated', 'academyActivated', 'academyFilterActivated', 'academies'));
    }

    public function create()
    {
        $multipleChapters = MultipleChapter::all();
        return view('dsExercise.create', compact('multipleChapters'));
    }

    public function store(StoreDsExerciseRequest $request)
    {
        $lastExercise = DsExercise::orderBy('id', 'desc')->first();
        $newExerciseId = $lastExercise ? $lastExercise->id + 1 : 1;
        while (DsExercise::find($newExerciseId)) {
            $newExerciseId++;
        }

        $dsExercise = new DsExercise();
        $dsExercise->fill($request->except('images'));
        $dsExercise->id = $newExerciseId;
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->latex_statement = $dsExercise->statement;

        $imagePaths = $this->imageManagementService->handleImageUpload(
            request: $request,
            inputName: 'images',
            deleteInputName: 'delete_images',
            context: 'ds-exercises',
            identifier: 'ds-exercise-' . $dsExercise->id,
            prefix: 'img-',
            isPublic: true
        );

        $this->imageManagementService->validateLatexReferencesOrFail(
            $request->statement,
            $imagePaths,
            'statement'
        );

        $dsExercise->statement = LatexToHtmlConverter::convertForDsExercise($dsExercise->statement, $imagePaths);
        if ($request->hasFile('correction_pdf')) {
            $pdfPath = $this->fileUploadService->upload(
                file: $request->file('correction_pdf'),
                context: 'ds-exercises',
                identifier: 'ds-exercise-' . $dsExercise->id,
                type: 'pdf',
                isPublic: true,
                customName: 'correction_ds_' . $dsExercise->id
            );
            $dsExercise->correction_pdf = $pdfPath;
        }
        $dsExercise->save();

        return redirect()->route('ds_exercises.index');
    }

    public function show(string $id, string $filter)
    {
        $dsExercise = DsExercise::findOrFail($id);
        // multiple_chapter_id 
        $multipleChapter = MultipleChapter::findOrFail($dsExercise->multiple_chapter_id);
        if ($filter == 'true') {
            $dsExercises = DsExercise::where('multiple_chapter_id', $dsExercise->multiple_chapter_id)->get();
        } else {
            $dsExercises = DsExercise::with('chapters')->orderBy('id')->get();
        }
        $nextExercise = $dsExercises->filter(function ($exercise) use ($dsExercise) {
            return $exercise->id > $dsExercise->id;
        })->first();
        $previousExercise = $dsExercises->filter(function ($exercise) use ($dsExercise) {
            return $exercise->id < $dsExercise->id;
        })->last();

        return view('dsExercise.show', compact('dsExercise', 'multipleChapter', 'nextExercise', 'filter', 'previousExercise'));
    }

    public function edit(string $id, string $filter)
    {
        $dsExercise = DsExercise::findOrFail($id);
        $multipleChapters = MultipleChapter::all();

        // Récupérer les images existantes pour le composant image-manager via le service
        $existingImagesFormatted = $this->imageManagementService->getFormattedImagesForComponent(
            context: 'ds-exercises',
            identifier: 'ds-exercise-' . $dsExercise->id,
            isPublic: true,
            pattern: 'img-*'
        );

        return view('dsExercise.edit', compact('dsExercise', 'multipleChapters', 'filter', 'existingImagesFormatted'));
    }

    public function update(UpdateDsExerciseRequest $request, string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);
        $dsExercise->fill($request->except('images', 'statement'));
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->latex_statement = $request->statement;

        $imagePaths = $this->imageManagementService->handleImageUpload(
            request: $request,
            inputName: 'images',
            deleteInputName: 'delete_images',
            context: 'ds-exercises',
            identifier: 'ds-exercise-' . $dsExercise->id,
            prefix: 'img-',
            isPublic: true
        );

        $this->imageManagementService->validateLatexReferencesOrFail(
            $request->statement,
            $imagePaths,
            'statement'
        );

        $dsExercise->statement = LatexToHtmlConverter::convertForDsExercise($request->statement, $imagePaths);

        // Suppression du PDF de correction si demandé
        if ($request->has('delete_correction_pdf') && $dsExercise->correction_pdf) {
            $this->fileUploadService->delete($dsExercise->correction_pdf, true);
            $dsExercise->correction_pdf = null;
        }

        if ($request->hasFile('correction_pdf')) {
            // Supprimer l'ancien PDF s'il existe
            if ($dsExercise->correction_pdf) {
                $this->fileUploadService->delete($dsExercise->correction_pdf, true);
            }

            // Upload le nouveau PDF
            $pdfPath = $this->fileUploadService->upload(
                file: $request->file('correction_pdf'),
                context: 'ds-exercises',
                identifier: 'ds-exercise-' . $dsExercise->id,
                type: 'pdf',
                isPublic: true,
                customName: 'correction_ds_' . $dsExercise->id
            );
            $dsExercise->correction_pdf = $pdfPath;
        }

        $dsExercise->save();

        return redirect()->route('ds_exercises.index', ['filter' => $request->filter]);
    }

    public function destroy(string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);

        // Supprimer tout le dossier de l'exercice avec FileUploadService (images + PDF de correction)
        $this->fileUploadService->deleteDirectory('ds-exercises', 'ds-exercise-' . $dsExercise->id, true);

        // Supprimer l'exercice de la base de données
        $dsExercise->delete();

        return redirect()->route('ds_exercises.index');
    }
}
