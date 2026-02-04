<?php


namespace App\Http\Controllers\Exercise;

use App\Http\Controllers\Controller;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;
use App\Models\Subchapter;
use App\Models\Chapter;
use App\Models\Classe;
use Illuminate\Support\Facades\Log;
use App\Services\LatexToHtmlConverter;
use App\Services\OrderingService;
use App\Helpers\ErrorResponseHelper;
use App\Http\Requests\Exercise\StoreExerciseRequest;
use App\Http\Requests\Exercise\UpdateExerciseRequest;

class ExerciseController extends Controller
{
    protected OrderingService $orderingService;
    protected \App\Services\FileUploadService $fileUploadService;
    protected \App\Services\ImageManagementService $imageManagementService;

    public function __construct(
        OrderingService $orderingService,
        \App\Services\FileUploadService $fileUploadService,
        \App\Services\ImageManagementService $imageManagementService
    ) {
        $this->orderingService = $orderingService;
        $this->fileUploadService = $fileUploadService;
        $this->imageManagementService = $imageManagementService;
    }
    public function decrementAllExercises()
    {
        try {
            // Get the minimum order value
            $minOrder = Exercise::min('order');

            // If the minimum order is greater than 1, decrement all exercises
            if ($minOrder > 1) {
                Exercise::where('order', '>=', $minOrder)
                    ->decrement('order');
                    
                // Recalculer tous les ordres pour maintenir la cohérence
                $this->orderingService->recalculateAllGlobalExerciseOrders();
            }

            return redirect()->back()->with('success', 'Exercises order decremented successfully');
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Decrement exercises');
        }
    }
    public function updateOrder(Request $request)
    {
        $orderData = $request->input('order');
        Log::info('updateOrder called with request: ', $request->all());

        foreach ($orderData as $data) {
            $id = $data['id'];
            $order = $data['order'];
            Log::info("Updating exercise $id with order $order");

            $exercise = Exercise::find($id);

            if ($exercise === null) {
                Log::info("Exercise $id not found");
                continue;
            }

            try {
                $exercise->order = $order;
                $exercise->save();
                Log::info("Saved exercise $id with order $order");
            } catch (\Exception $e) {
                Log::error("Failed to save exercise $id: " . $e->getMessage());
                // Continue processing other exercises
            }
        }

        return response()->json(['status' => 'success']);
    }


    public function index(): View|RedirectResponse
    {
        try {
            $search = request()->get('search');
            $filter = request()->get('filter', 'all');

            $query = Exercise::query();
            
            // Filtrer selon rôle et option de filtre
            if (Auth::user()->role !== 'admin') {
                $query->visible(); // Non-admin voient que les visibles
            } else {
                // Admin peut filtrer
                if ($filter === 'visible') {
                    $query->where('is_hidden', false);
                } elseif ($filter === 'hidden') {
                    $query->where('is_hidden', true);
                }
                // Si 'all', on ne filtre pas
            }
            
            // Search
            if ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            }
            
            $exercises = $query->orderBy('created_at', 'desc')->paginate(10)->withQueryString();

            $subchapters = Subchapter::all();
            return view('exercise.index', compact('exercises', 'subchapters', 'filter'));
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Load exercises index');
        }
    }

    public function create($id): View|RedirectResponse
    {
        try {
            $subchapter_id = $id;
            $subchapters = Subchapter::all();
            return view('exercise.create', compact('subchapter_id', 'subchapters'));
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Load create exercise view');
        }
    }

    private function getMaxOrder($subchapter)
    {
        $maxOrder = null;

        // Get the previous subchapters in the same chapter
        $previousSubchapters = Subchapter::where('chapter_id', $subchapter->chapter_id)
            ->where('order', '<', $subchapter->order)
            ->orderBy('order', 'desc')
            ->get();

        // Loop through the previous subchapters to find the max order
        foreach ($previousSubchapters as $previousSubchapter) {
            $maxOrder = Exercise::where('subchapter_id', $previousSubchapter->id)->max('order');
            if ($maxOrder !== null) {
                break;
            }
        }

        // If there are no exercises in the previous subchapters, get the last exercise of the previous chapters
        if ($maxOrder === null) {
            $previousChapters = Chapter::where('order', '<', $subchapter->chapter->order)
                ->orderBy('order', 'desc')
                ->get();

            // Loop through the previous chapters to find the max order
            foreach ($previousChapters as $previousChapter) {
                $maxOrder = Exercise::whereHas('subchapter', function ($query) use ($previousChapter) {
                    $query->where('chapter_id', $previousChapter->id);
                })->max('order');

                if ($maxOrder !== null) {
                    break;
                }
            }
        }

        // If there are no exercises in the previous chapters, start from 1
        if ($maxOrder === null) {
            $maxOrder = 0;
        }

        // Get the max order of the exercises in the current subchapter
        $currentSubchapterMaxOrder = Exercise::where('subchapter_id', $subchapter->id)->max('order');

        // If the max order of the exercises in the current subchapter is greater than the max order of the exercises in the previous subchapter or chapter, use it
        if ($currentSubchapterMaxOrder > $maxOrder) {
            $maxOrder = $currentSubchapterMaxOrder;
        }

        return $maxOrder;
    }

    public function store(StoreExerciseRequest $request): RedirectResponse
    {
        try {
    
            $maxOrder = $this->getMaxOrder(Subchapter::find($request->subchapter_id));
            // Décaler les exercices existants
            Exercise::where('order', '>', $maxOrder)->increment('order');
    
            // Étape 1 : Enregistrer l'exercice pour obtenir son ID
            $exercise = new Exercise();
            $exercise->clue = $request->clue ? LatexToHtmlConverter::convertForExercise($request->clue) : null;
            $exercise->latex_clue = $request->clue;
            $exercise->name = $request->name;
            $exercise->subchapter_id = $request->subchapter_id;
            $exercise->difficulty = $request->difficulty;
            $exercise->order = $maxOrder + 1;
            $exercise->statement = 'temp'; // Temporaire pour éviter les erreurs de validation
            $exercise->save(); // Sauvegarde pour générer l'ID
    
            // Étape 2 : Gestion des images pour statement via ImageManagementService
            $imagePathsStatement = $this->imageManagementService->handleImageUpload(
                request: $request,
                inputName: 'images_statement',
                deleteInputName: 'delete_images_statement',
                context: 'exercises',
                identifier: 'exercise-' . $exercise->id . '/statement',
                prefix: 'img-',
                isPublic: true
            );

            $this->imageManagementService->validateLatexReferencesOrFail(
                $request->statement,
                $imagePathsStatement,
                'statement'
            );

            $exercise->statement = LatexToHtmlConverter::convertForExercise($request->statement, $imagePathsStatement);
            $exercise->latex_statement = $request->statement;

            // Étape 3 : Gestion des images pour solution via ImageManagementService
            $imagePathsSolution = $this->imageManagementService->handleImageUpload(
                request: $request,
                inputName: 'images_solution',
                deleteInputName: 'delete_images_solution',
                context: 'exercises',
                identifier: 'exercise-' . $exercise->id . '/solution',
                prefix: 'img-',
                isPublic: true
            );

            if ($request->solution) {
                $this->imageManagementService->validateLatexReferencesOrFail(
                    $request->solution,
                    $imagePathsSolution,
                    'solution'
                );
            }

            $exercise->solution = $request->solution ? LatexToHtmlConverter::convertForExercise($request->solution, $imagePathsSolution) : null;
            $exercise->latex_solution = $request->solution;
    
            // Étape 4 : Mise à jour des images et sauvegarde finale
            $exercise->save();
            
            // Recalculer tous les ordres globaux des exercices
            $this->orderingService->recalculateAllGlobalExerciseOrders();
    
            return redirect()->route('subchapter.show', [
                'id' => $request->subchapter_id,
                'exercise' => $exercise->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Relancer les ValidationException (validation LaTeX)
            throw $e;
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Store exercise');
        }
    }    

    public function show($id): View
    {
        $exercise = Exercise::findOrFail($id);
        return view('exercise.show', compact('exercise'));
    }
    

    public function edit($id): View|RedirectResponse
    {
        try {
            $exercise = Exercise::findOrFail($id);

            // Récupérer les images existantes via ImageManagementService
            $existingImagesStatementFormatted = $this->imageManagementService->getFormattedImagesForComponent(
                context: 'exercises',
                identifier: 'exercise-' . $exercise->id . '/statement',
                isPublic: true,
                pattern: 'img-*'
            );

            $existingImagesSolutionFormatted = $this->imageManagementService->getFormattedImagesForComponent(
                context: 'exercises',
                identifier: 'exercise-' . $exercise->id . '/solution',
                isPublic: true,
                pattern: 'img-*'
            );

            return view('exercise.edit', compact('exercise', 'existingImagesStatementFormatted', 'existingImagesSolutionFormatted'));
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Load edit exercise view');
        }
    }

    public function update(UpdateExerciseRequest $request, $id): RedirectResponse
    {
        try {
    
            $exercise = Exercise::findOrFail($id);
    
            $imagePathsStatement = $this->imageManagementService->handleImageUpload(
                request: $request,
                inputName: 'images_statement',
                deleteInputName: 'delete_images_statement',
                context: 'exercises',
                identifier: 'exercise-' . $exercise->id . '/statement',
                prefix: 'img-',
                isPublic: true
            );

            $this->imageManagementService->validateLatexReferencesOrFail(
                $request->statement,
                $imagePathsStatement,
                'statement'
            );

            $exercise->statement = LatexToHtmlConverter::convertForExercise($request->statement, $imagePathsStatement);

            $imagePathsSolution = $this->imageManagementService->handleImageUpload(
                request: $request,
                inputName: 'images_solution',
                deleteInputName: 'delete_images_solution',
                context: 'exercises',
                identifier: 'exercise-' . $exercise->id . '/solution',
                prefix: 'img-',
                isPublic: true
            );

            if ($request->solution) {
                $this->imageManagementService->validateLatexReferencesOrFail(
                    $request->solution,
                    $imagePathsSolution,
                    'solution'
                );
            }

            $exercise->solution = $request->solution ? LatexToHtmlConverter::convertForExercise($request->solution, $imagePathsSolution) : null;
    
            // Mise à jour des indices (`clue`)
            $exercise->clue = $request->clue ? LatexToHtmlConverter::convertForExercise($request->clue) : null;
            $exercise->latex_clue = $request->clue;
    
            // Mise à jour des autres champs
            $exercise->update([
                'subchapter_id' => $request->subchapter_id,
                'difficulty' => $request->difficulty,
                'name' => $request->name,
                'latex_statement' => $request->statement,
                'latex_solution' => $request->solution,
            ]);
    
            return redirect()->route('subchapter.show', [
                'id' => $request->subchapter_id,
                'exercise' => $exercise->id,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Relancer les ValidationException (validation LaTeX)
            throw $e;
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Update exercise');
        }
    }
    
    public function destroy($id): RedirectResponse
    {
        try {
            $exercise = Exercise::findOrFail($id);

            // Supprimer tout le dossier de l'exercice avec FileUploadService
            $this->fileUploadService->deleteDirectory('exercises', 'exercise-' . $exercise->id, true);

            $deletedOrder = $exercise->order;
            $subchapterId = $exercise->subchapter_id;

            $exercise->delete();
    
            // Décaler les exercices suivants
            Exercise::where('order', '>', $deletedOrder)->decrement('order');
            
            // Recalculer tous les ordres globaux des exercices
            $this->orderingService->recalculateAllGlobalExerciseOrders();
    
            return redirect()->route('subchapter.show', $subchapterId);
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Destroy exercise');
        }
    }

    /**
     * Toggle visibility of an exercise (admin only)
     * Recalculates global order after toggle
     */
    public function toggleHidden($id): RedirectResponse
    {
        try {
            if (Auth::user()->role !== 'admin') {
                abort(403, 'Unauthorized');
            }

            $exercise = Exercise::findOrFail($id);
            $exercise->is_hidden = !$exercise->is_hidden;
            $exercise->save();

            $this->orderingService->recalculateAllGlobalExerciseOrders();

            $status = $exercise->is_hidden ? 'masqué' : 'visible';

            return back()->with('success', "Exercice {$status} avec succès");
        } catch (\Exception $e) {
            return ErrorResponseHelper::systemError($e, 'Toggle exercise visibility');
        }
    }
    
}
