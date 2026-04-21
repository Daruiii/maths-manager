<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bureau\StorePrivateExerciseRequest;
use App\Http\Requests\Bureau\UpdatePrivateExerciseRequest;
use App\Http\Traits\ProvidesCatalogueData;
use App\Models\PrivateExercise;
use App\Services\FileUploadService;
use App\Services\ImageManagementService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class PrivateExerciseController extends Controller
{
    use ProvidesCatalogueData;

    public function __construct(
        private FileUploadService $fileUploadService,
        private ImageManagementService $imageManagementService,
    ) {}

    // ──────────────────────────────────────────────────────────────────────────
    // PAGES
    // ──────────────────────────────────────────────────────────────────────────

    public function index(Request $request): Response
    {
        $this->authorize('viewAny', PrivateExercise::class);

        $teacher = Auth::user();
        $sort = in_array($request->query('sort'), ['recent', 'old'], true)
            ? $request->query('sort')
            : 'recent';

        $query = PrivateExercise::forTeacher($teacher->id)
            ->with(['subchapter.chapter.classe', 'tags'])
            ->orderBy('created_at', $sort === 'old' ? 'asc' : 'desc');

        if ($search = $request->query('search')) {
            $query->where('name', 'like', "%{$search}%");
        }
        if ($type = $request->query('type')) {
            $query->where('type', $type);
        }
        if ($difficulty = $request->query('difficulty')) {
            $query->where('difficulty', $difficulty);
        }
        if ($subchapterId = $request->query('subchapter_id')) {
            $query->where('subchapter_id', $subchapterId);
        }
        if ($tagId = $request->query('tag_id')) {
            $query->whereHas('tags', fn ($q) => $q->where('teacher_tags.id', $tagId));
        }

        $exercises = $query->paginate(20)->withQueryString();
        $exercises->getCollection()->transform(fn (PrivateExercise $e) => $this->withImages($e));

        return Inertia::render('Teacher/Exercices/Index', [
            'exercises' => $exercises,
            'tags'      => $teacher->teacherTags()->orderBy('name')->get(['id', 'name', 'color']),
            'filters'   => $request->only(['search', 'type', 'difficulty', 'subchapter_id', 'tag_id', 'sort']),
        ]);
    }

    public function create(): Response
    {
        $this->authorize('create', PrivateExercise::class);

        return Inertia::render('Teacher/Exercices/Create', [
            ...$this->catalogueData(),
            'tags' => Auth::user()->teacherTags()->orderBy('name')->get(['id', 'name', 'color']),
        ]);
    }

    public function edit(PrivateExercise $exercise): Response
    {
        $this->authorize('update', $exercise);

        $exercise->load(['tags']);
        $exercise->image_paths = $this->loadImagePaths($exercise->id);

        return Inertia::render('Teacher/Exercices/Edit', [
            'exercise' => $exercise,
            ...$this->catalogueData(),
            'tags'     => Auth::user()->teacherTags()->orderBy('name')->get(['id', 'name', 'color']),
        ]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // CRUD
    // ──────────────────────────────────────────────────────────────────────────

    public function store(StorePrivateExerciseRequest $request)
    {
        $this->authorize('create', PrivateExercise::class);

        $exercise = PrivateExercise::create([
            ...$request->safe()->except(['tag_ids', 'pending_images']),
            'teacher_id' => Auth::id(),
        ]);

        if ($tagIds = $request->input('tag_ids', [])) {
            $exercise->tags()->sync($tagIds);
        }

        $pendingImages = $request->file('pending_images', []);
        if (is_array($pendingImages) && !empty($pendingImages)) {
            foreach ($pendingImages as $customName => $file) {
                $this->fileUploadService->upload(
                    file: $file,
                    context: 'private-exercises',
                    identifier: 'private-exercise-' . $exercise->id,
                    type: 'image',
                    isPublic: true,
                    customName: (string) $customName,
                );
            }
        }

        if ($request->wantsJson()) {
            return response()->json($exercise);
        }

        return redirect()
            ->route('teacher.exercices.index')
            ->with('success', 'Exercice créé avec succès.');
    }

    public function update(UpdatePrivateExerciseRequest $request, PrivateExercise $exercise)
    {
        $this->authorize('update', $exercise);

        $exercise->update($request->safe()->except('tag_ids'));

        if ($request->has('tag_ids')) {
            $exercise->tags()->sync($request->input('tag_ids', []));
        }

        return back()->with('success', 'Exercice mis à jour.');
    }

    public function destroy(PrivateExercise $exercise)
    {
        $this->authorize('delete', $exercise);

        $this->fileUploadService->deleteDirectory('private-exercises', 'private-exercise-' . $exercise->id);
        $exercise->delete();

        return redirect()
            ->route('teacher.exercices.index')
            ->with('success', 'Exercice supprimé.');
    }

    // ──────────────────────────────────────────────────────────────────────────
    // IMAGES
    // ──────────────────────────────────────────────────────────────────────────

    public function uploadImage(Request $request, PrivateExercise $exercise): JsonResponse
    {
        $this->authorize('update', $exercise);

        $request->validate(['image' => 'required|image|max:2048']);

        $identifier = 'private-exercise-' . $exercise->id;
        $name       = $this->imageManagementService->getNextImageName('private-exercises', $identifier);

        $path = $this->fileUploadService->upload(
            file: $request->file('image'),
            context: 'private-exercises',
            identifier: $identifier,
            type: 'image',
            isPublic: true,
            customName: $name,
        );

        return response()->json([
            'name' => $name,
            'url'  => $this->fileUploadService->getPublicUrl($path),
            'path' => $path,
        ]);
    }

    public function deleteImage(PrivateExercise $exercise, string $imageName): JsonResponse
    {
        $this->authorize('update', $exercise);

        $files = $this->fileUploadService->getFiles(
            'private-exercises',
            'private-exercise-' . $exercise->id,
            true,
            $imageName . '.*'
        );

        foreach ($files as $path) {
            $this->fileUploadService->delete($path, true);
        }

        return response()->json(['success' => true]);
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers privés
    // ──────────────────────────────────────────────────────────────────────────

    private function loadImagePaths(int $id): ?array
    {
        $files = $this->fileUploadService->getFiles(
            'private-exercises',
            'private-exercise-' . $id,
            true,
            'img-*'
        );

        if (empty($files)) {
            return null;
        }

        $paths = [];
        foreach ($files as $path) {
            $paths[pathinfo($path, PATHINFO_FILENAME)] = $path;
        }

        return $paths;
    }

    private function withImages(PrivateExercise $exercise): PrivateExercise
    {
        $exercise->image_paths = $this->loadImagePaths($exercise->id);
        return $exercise;
    }
}
