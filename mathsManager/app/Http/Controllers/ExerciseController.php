<?php


namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\Subchapter;
use App\Models\Chapter;
use App\Models\Classe;
use Illuminate\Support\Facades\Log;
use App\Services\LatexToHtmlConverter;
use App\Services\OrderingService;

class ExerciseController extends Controller
{
    protected OrderingService $orderingService;
    protected \App\Services\FileUploadService $fileUploadService;

    public function __construct(OrderingService $orderingService, \App\Services\FileUploadService $fileUploadService)
    {
        $this->orderingService = $orderingService;
        $this->fileUploadService = $fileUploadService;
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
            Log::error("Failed to decrement exercises: " . $e->getMessage());
            return back()->withErrors('Failed to decrement exercises.');
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
            }
        }

        return response()->json(['status' => 'success']);
    }

    public function index()
    {
        try {
            $search = request()->get('search');

            $exercises = Exercise::orderBy('created_at', 'desc');
            if ($search) {
                $exercises = $exercises->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            }
            $exercises = $exercises->paginate(10)->withQueryString();

            $subchapters = Subchapter::all();
            return view('exercise.index', compact('exercises', 'subchapters'));
        } catch (\Exception $e) {
            Log::error("Failed to load exercises: " . $e->getMessage());
            return back()->withErrors('Failed to load exercises.');
        }
    }

    public function create($id)
    {
        try {
            $subchapter_id = $id;
            $subchapters = Subchapter::all();
            return view('exercise.create', compact('subchapter_id', 'subchapters'));
        } catch (\Exception $e) {
            Log::error("Failed to load create exercise view: " . $e->getMessage());
            return back()->withErrors('Failed to load create exercise view.');
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

    public function store(Request $request)
    {
        try {
            $request->validate([
                'subchapter_id' => 'required',
                'statement' => 'required',
                'clue' => 'nullable',
                'solution' => 'nullable',
                'name' => 'nullable',
                'difficulty' => 'required|numeric|min:1|max:5',
                'images_statement' => 'nullable|array',
                'images_statement.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'images_solution' => 'nullable|array',
                'images_solution.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
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
    
            // Étape 2 : Gestion des images pour `statement` avec FileUploadService
            $imagePathsStatement = [];
            if ($request->hasFile('images_statement')) {
                $uploadedPaths = $this->fileUploadService->uploadMultiple(
                    files: $request->file('images_statement'),
                    context: 'exercises',
                    identifier: 'exercise-' . $exercise->id,
                    type: 'image',
                    isPublic: true,
                    prefix: 'statement_'
                );

                // Créer un tableau associatif pour la nouvelle syntaxe \graph{statement-1}{0.5}{description}
                // Utilise le nom de fichier réel comme clé (ex: "statement-1" depuis "statement-1.png")
                foreach ($uploadedPaths as $index => $path) {
                    $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                    $imagePathsStatement[$filename] = $path;
                }
            }
            $exercise->statement = LatexToHtmlConverter::convertForExercise($request->statement, $imagePathsStatement);
            $exercise->latex_statement = $request->statement;
    
            // Étape 3 : Gestion des images pour `solution` avec FileUploadService
            $imagePathsSolution = [];
            if ($request->hasFile('images_solution')) {
                $uploadedPaths = $this->fileUploadService->uploadMultiple(
                    files: $request->file('images_solution'),
                    context: 'exercises',
                    identifier: 'exercise-' . $exercise->id,
                    type: 'image',
                    isPublic: true,
                    prefix: 'solution_'
                );

                // Créer un tableau associatif pour la nouvelle syntaxe \graph{solution-1}{0.5}{description}
                // Utilise le nom de fichier réel comme clé (ex: "solution-1" depuis "solution-1.png")
                foreach ($uploadedPaths as $index => $path) {
                    $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                    $imagePathsSolution[$filename] = $path;
                }
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
        } catch (\Exception $e) {
            Log::error("Failed to store exercise: " . $e->getMessage());
            return back()->withErrors('Failed to store exercise. ' . $e->getMessage());
        }
    }    

    public function show($id)
    {
        $exercise = Exercise::findOrFail($id);
        return view('exercise.show', compact('exercise'));
    }
    

    public function edit($id)
    {
        try {
            $exercise = Exercise::findOrFail($id);
            return view('exercise.edit', compact('exercise'));
        } catch (\Exception $e) {
            Log::error("Failed to load edit exercise view: " . $e->getMessage());
            return back()->withErrors('Failed to load edit exercise view.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $request->validate([
                'subchapter_id' => 'required',
                'statement' => 'required',
                'solution' => 'nullable',
                'clue' => 'nullable',
                'name' => 'nullable',
                'difficulty' => 'required|numeric|min:1|max:5',
                'images_statement' => 'nullable|array',
                'images_statement.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
                'images_solution' => 'nullable|array',
                'images_solution.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
    
            $exercise = Exercise::findOrFail($id);
    
            // Mise à jour des images pour `statement` avec FileUploadService
            $imagePathsStatement = [];
            if ($request->hasFile('images_statement')) {
                // Supprimer les anciennes images statement
                $oldImages = $this->fileUploadService->getFiles('exercises', 'exercise-' . $exercise->id, true, 'statement-*');
                $this->fileUploadService->deleteMultiple($oldImages, true);

                // Upload les nouvelles images
                $uploadedPaths = $this->fileUploadService->uploadMultiple(
                    files: $request->file('images_statement'),
                    context: 'exercises',
                    identifier: 'exercise-' . $exercise->id,
                    type: 'image',
                    isPublic: true,
                    prefix: 'statement_'
                );

                // Créer tableau associatif pour nouvelle syntaxe
                foreach ($uploadedPaths as $index => $path) {
                    $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                    $imagePathsStatement[$filename] = $path;
                }
            } else {
                // Garder les images existantes
                $existingImages = $this->fileUploadService->getFiles('exercises', 'exercise-' . $exercise->id, true, 'statement-*');
                foreach ($existingImages as $index => $path) {
                    $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                    $imagePathsStatement[$filename] = $path;
                }
            }
            $exercise->statement = LatexToHtmlConverter::convertForExercise($request->statement, $imagePathsStatement);
    
            // Mise à jour des images pour `solution` avec FileUploadService
            $imagePathsSolution = [];
            if ($request->hasFile('images_solution')) {
                // Supprimer les anciennes images solution
                $oldImages = $this->fileUploadService->getFiles('exercises', 'exercise-' . $exercise->id, true, 'solution-*');
                $this->fileUploadService->deleteMultiple($oldImages, true);

                // Upload les nouvelles images
                $uploadedPaths = $this->fileUploadService->uploadMultiple(
                    files: $request->file('images_solution'),
                    context: 'exercises',
                    identifier: 'exercise-' . $exercise->id,
                    type: 'image',
                    isPublic: true,
                    prefix: 'solution_'
                );

                // Créer tableau associatif pour nouvelle syntaxe
                foreach ($uploadedPaths as $index => $path) {
                    $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                    $imagePathsSolution[$filename] = $path;
                }
            } else {
                // Garder les images existantes
                $existingImages = $this->fileUploadService->getFiles('exercises', 'exercise-' . $exercise->id, true, 'solution-*');
                foreach ($existingImages as $index => $path) {
                    $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                    $imagePathsSolution[$filename] = $path;
                }
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
        } catch (\Exception $e) {
            Log::error("Failed to update exercise: " . $e->getMessage());
            return back()->withErrors('Failed to update exercise.');
        }
    }
    
    public function destroy($id)
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
            Log::error("Failed to destroy exercise: " . $e->getMessage());
            return back()->withErrors('Failed to destroy exercise.');
        }
    }
    
}
