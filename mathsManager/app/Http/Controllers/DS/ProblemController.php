<?php

namespace App\Http\Controllers\DS;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Problem;
use App\Models\Chapter;
use App\Models\MultipleChapter;
use App\Http\Requests\Problem\StoreProblemRequest;
use App\Http\Requests\Problem\UpdateProblemRequest;
use App\Services\FileUploadService;
use App\Services\ImageManagementService;
use App\Services\QueryFiltersService;

class ProblemController extends Controller
{
    protected FileUploadService $fileUploadService;
    protected ImageManagementService $imageManagementService;
    protected QueryFiltersService $queryFiltersService;

    public function __construct(
        FileUploadService $fileUploadService,
        ImageManagementService $imageManagementService,
        QueryFiltersService $queryFiltersService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->imageManagementService = $imageManagementService;
        $this->queryFiltersService = $queryFiltersService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $problems = Problem::with('multipleChapter.classe')->orderBy('created_at', 'desc');

        // Appliquer la recherche via le service
        $problems = $this->queryFiltersService->applySearch($problems, $search, ['name', 'id']);

        // Gérer le filtre de difficulté spécial (0 = NULL pour "non évalué")
        if ($request->filled('difficulty')) {
            if ($request->difficulty == '0') {
                $problems->whereNull('difficulty');
            } else {
                $problems->where('difficulty', $request->difficulty);
            }
        }

        // Appliquer les filtres dynamiques via le service
        $filterFields = [
            'multiple_chapter_id' => 'multiple_chapter_id',
            'type' => 'type',
            'academy' => 'academy',
        ];

        $relationFilters = [
            'classe_id' => ['relation' => 'multipleChapter', 'column' => 'classe_id'],
        ];

        $problems = $this->queryFiltersService->applyFilters($problems, $request, $filterFields, $relationFilters);

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

        $classeActivated = $request->classe_id ?? null;
        $classeFilterActivated = !empty($request->classe_id);

        $difficultyActivated = $request->difficulty ?? null;
        $difficultyFilterActivated = $request->filled('difficulty');

        $academies = Problem::distinct('academy')->pluck('academy');
        $classes = \App\Models\Classe::all();
        $problems = $problems->paginate(10)->withQueryString();
        $multipleChapters = MultipleChapter::all();

        // Legacy view - kept for old_blade reference
        return view('problem.index', [
            'problems' => $problems,
            'multipleChapters' => $multipleChapters,
            'filterActivated' => $filterActivated,
            'chapterActivated' => $chapterActivated,
            'typeActivated' => $typeActivated,
            'typeFilterActivated' => $typeFilterActivated,
            'academyActivated' => $academyActivated,
            'academyFilterActivated' => $academyFilterActivated,
            'academies' => $academies,
            'classes' => $classes,
            'classeActivated' => $classeActivated,
            'classeFilterActivated' => $classeFilterActivated,
            'difficultyActivated' => $difficultyActivated,
            'difficultyFilterActivated' => $difficultyFilterActivated,
        ]);
    }

    public function create()
    {
        $multipleChapters = MultipleChapter::all();
        return view('problem.create', compact('multipleChapters'));
    }

    public function store(StoreProblemRequest $request)
    {
        // Create and save the problem first to get auto-generated ID
        $problem = new Problem();
        $problem->fill($request->except('images'));
        $problem->latex_statement = $request->statement;
        $problem->statement = '';
        $problem->save(); // ✅ Get auto-generated ID from database

        // Now handle image uploads with the generated ID
        $imagePaths = $this->imageManagementService->handleImageUpload(
            request: $request,
            inputName: 'images',
            deleteInputName: 'delete_images',
            context: 'problems',
            identifier: 'problem-' . $problem->id,
            prefix: 'img-',
            isPublic: true
        );

        $this->imageManagementService->validateLatexReferencesOrFail(
            $request->statement,
            $imagePaths,
            'statement'
        );

        $problem->statement = '';

        // Handle PDF upload if present
        if ($request->hasFile('correction_pdf')) {
            $pdfPath = $this->fileUploadService->upload(
                file: $request->file('correction_pdf'),
                context: 'problems',
                identifier: 'problem-' . $problem->id,
                type: 'pdf',
                isPublic: true,
                customName: 'correction_problem_' . $problem->id
            );
            $problem->correction_pdf = $pdfPath;
        }

        // Save again with updated statement and PDF path
        $problem->save();

        return redirect()->route('problems.index');
    }

    public function show(string $id, string $filter)
    {
        $problem = Problem::findOrFail($id);
        $multipleChapter = MultipleChapter::findOrFail($problem->multiple_chapter_id);

        if ($filter == 'true') {
            $problems = Problem::where('multiple_chapter_id', $problem->multiple_chapter_id)->get();
        } else {
            $problems = Problem::with('chapters')->orderBy('id')->get();
        }

        $nextExercise = $problems->filter(function ($p) use ($problem) {
            return $p->id > $problem->id;
        })->first();
        $previousExercise = $problems->filter(function ($p) use ($problem) {
            return $p->id < $problem->id;
        })->last();

        // Legacy view - kept for old_blade reference
        return view('problem.show', [
            'problem' => $problem,
            'multipleChapter' => $multipleChapter,
            'nextExercise' => $nextExercise,
            'filter' => $filter,
            'previousExercise' => $previousExercise,
        ]);
    }

    public function edit(string $id, string $filter)
    {
        $problem = Problem::findOrFail($id);
        $multipleChapters = MultipleChapter::all();

        // Récupérer les images existantes pour le composant image-manager via le service
        $existingImagesFormatted = $this->imageManagementService->getFormattedImagesForComponent(
            context: 'problems',
            identifier: 'problem-' . $problem->id,
            isPublic: true,
            pattern: 'img-*'
        );

        // Legacy view - kept for old_blade reference
        return view('problem.edit', [
            'problem' => $problem,
            'multipleChapters' => $multipleChapters,
            'filter' => $filter,
            'existingImagesFormatted' => $existingImagesFormatted,
        ]);
    }

    public function update(UpdateProblemRequest $request, string $id)
    {
        $problem = Problem::findOrFail($id);
        $problem->fill($request->except('images', 'statement'));
        $problem->latex_statement = $request->statement;

        $imagePaths = $this->imageManagementService->handleImageUpload(
            request: $request,
            inputName: 'images',
            deleteInputName: 'delete_images',
            context: 'problems',
            identifier: 'problem-' . $problem->id,
            prefix: 'img-',
            isPublic: true
        );

        $this->imageManagementService->validateLatexReferencesOrFail(
            $request->statement,
            $imagePaths,
            'statement'
        );

        $problem->statement = '';

        // Suppression du PDF de correction si demandé
        if ($request->has('delete_correction_pdf') && $problem->correction_pdf) {
            $this->fileUploadService->delete($problem->correction_pdf, true);
            $problem->correction_pdf = null;
        }

        if ($request->hasFile('correction_pdf')) {
            // Supprimer l'ancien PDF s'il existe
            if ($problem->correction_pdf) {
                $this->fileUploadService->delete($problem->correction_pdf, true);
            }

            // Upload le nouveau PDF
            $pdfPath = $this->fileUploadService->upload(
                file: $request->file('correction_pdf'),
                context: 'problems',
                identifier: 'problem-' . $problem->id,
                type: 'pdf',
                isPublic: true,
                customName: 'correction_problem_' . $problem->id
            );
            $problem->correction_pdf = $pdfPath;
        }

        $problem->save();

        return redirect()->route('problems.index', ['filter' => $request->filter]);
    }

    public function destroy(string $id)
    {
        $problem = Problem::findOrFail($id);

        // Supprimer tout le dossier du problem avec FileUploadService (images + PDF de correction)
        $this->fileUploadService->deleteDirectory('problems', 'problem-' . $problem->id, true);

        // Supprimer le problem de la base de données
        $problem->delete();

        return redirect()->route('problems.index');
    }
}
