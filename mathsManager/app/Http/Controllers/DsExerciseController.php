<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DsExercise;
use App\Models\Chapter;
use App\Models\MultipleChapter;
use App\Services\LatexToHtmlConverter;

class DsExerciseController extends Controller
{
    protected \App\Services\FileUploadService $fileUploadService;
    protected \App\Services\ImageManagementService $imageManagementService;

    public function __construct(
        \App\Services\FileUploadService $fileUploadService,
        \App\Services\ImageManagementService $imageManagementService
    ) {
        $this->fileUploadService = $fileUploadService;
        $this->imageManagementService = $imageManagementService;
    }

    public function index(Request $request)
    {
        $search = $request->get('search');
        $dsExercises = DsExercise::with('chapters')->orderBy('created_at', 'desc');

        if ($search) {
            $dsExercises->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('multiple_chapter_id')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id);
            $filterActivated = true;
            $chapterActivated = MultipleChapter::findOrFail($request->multiple_chapter_id);
        } else {
            $filterActivated = false;
            $chapterActivated = null;
        }

        if ($request->filled('type')) {
            $dsExercises->where('type', $request->type);
            $typeActivated = $request->type;
            $typeFilterActivated = true;
        } else {
            $typeActivated = null;
            $typeFilterActivated = false;
        }

        if ($request->filled('academy')) {
            $dsExercises->where('academy', $request->academy);
            $academyActivated = $request->academy;
            $academyFilterActivated = true;
        } else {
            $academyActivated = null;
            $academyFilterActivated = false;
        }

        // Filter by both `type` and `academy` if both are provided
        if ($request->filled('type') && $request->filled('academy')) {
            $dsExercises->where('type', $request->type)
                        ->where('academy', $request->academy);
        }

        // Filter by both `multiple_chapter_id` and `type` if both are provided
        if ($request->filled('multiple_chapter_id') && $request->filled('type')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id)
                        ->where('type', $request->type);
        }

        // Filter by both `multiple_chapter_id` and `academy` if both are provided
        if ($request->filled('multiple_chapter_id') && $request->filled('academy')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id)
                        ->where('academy', $request->academy);
        }

        // Filter by all three `multiple_chapter_id`, `type`, and `academy` if all are provided
        if ($request->filled('multiple_chapter_id') && $request->filled('type') && $request->filled('academy')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id)
                        ->where('type', $request->type)
                        ->where('academy', $request->academy);
        }

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

    public function store(Request $request)
    {
        $request->validate([
            'multiple_chapter_id' => 'required|exists:multiple_chapters,id',
            'harder_exercise' => 'boolean',
            'time' => 'required|integer',
            'name' => 'nullable|max:255',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correction_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'type' => 'nullable|string',
            'year' => 'nullable|integer',
            'academy' => 'nullable|string',
            'date_data' => 'nullable|string',
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id'
        ]);
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

    public function update(Request $request, string $id)
    {
        $request->validate([
            'multiple_chapter_id' => 'required|exists:multiple_chapters,id',
            'harder_exercise' => 'boolean',
            'time' => 'required|integer',
            'name' => 'nullable|max:255',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'filter' => 'nullable|string',
            'image_order' => 'nullable|string',
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id'
            'correction_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'delete_correction_pdf' => 'nullable|boolean',
            'type' => 'nullable|string',
            'year' => 'nullable|integer',
            'academy' => 'nullable|string',
            'date_data' => 'nullable|string',
        ]);

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
