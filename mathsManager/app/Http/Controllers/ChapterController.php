<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\Subchapter;
use App\Models\Exercise;
use App\Services\OrderingService;
use App\Http\Requests\Chapter\StoreChapterRequest;
use App\Http\Requests\Chapter\UpdateChapterRequest;

class ChapterController extends Controller
{
    protected OrderingService $orderingService;
    
    public function __construct(OrderingService $orderingService)
    {
        $this->orderingService = $orderingService;
    }

    public function index() // students
    {
        $chapters = Chapter::all();
        return view('chapter.index', compact('chapters'));
    }

    public function show($id) // students
    {
        $chapter = Chapter::findOrFail($id);
        return view('chapter.show', compact('chapter'));
    }

    public function create($id) // admin
    {
        $classes = Classe::all();
        $classeActive = Classe::findOrFail($id)->id;
        $themeColors = config('themes.chapter_colors');
    
        // Ordre local dans la classe : prochaine position disponible
        $nextOrder = Chapter::where('class_id', $classeActive)->max('order') + 1;
        if (!$nextOrder) {
            $nextOrder = 1;
        }
    
        return view('chapter.create', compact('classes', 'classeActive', 'themeColors', 'nextOrder'));
    }
    public function store(StoreChapterRequest $request) // admin
    {
        $classId = $request->class_id;
        $newOrder = $request->order;

        // Décaler les chapitres existants dans la classe si nécessaire
        Chapter::where('class_id', $classId)
               ->where('order', '>=', $newOrder)
               ->increment('order');

        // Créer le nouveau chapitre
        Chapter::create($request->validated());

        // Recalculer les ordres globaux des exercices
        $this->orderingService->recalculateAllGlobalExerciseOrders();

        $classLevel = Classe::findOrFail($classId)->level;
        return redirect()->route('classe.show', $classLevel);
    }

    public function edit($id) // admin
    {
        $chapter = Chapter::findOrFail($id);
        $classes = Classe::all();
        $themeColors = config('themes.chapter_colors');
        return view('chapter.edit', compact('chapter', 'classes', 'themeColors'));
    }

    public function update(UpdateChapterRequest $request, $id) // admin
    {
        $chapter = Chapter::findOrFail($id);

        $chapter->update($request->validated());
        $classLevel = Classe::findOrFail($request->class_id)->level;

        return redirect()->route('classe.show', $classLevel);
    }

    public function destroy($id) // admin
    {
        $chapter = Chapter::findOrFail($id);
        $class_id = $chapter->class_id;
        $order = $chapter->order;
    
        $chapter->delete();
    
        // Décaler les chapitres suivants dans la même classe
        Chapter::where('class_id', $class_id)
               ->where('order', '>', $order)
               ->decrement('order');
        
        // Recalculer les ordres globaux des exercices
        $this->orderingService->recalculateAllGlobalExerciseOrders();
    
        $classLevel = Classe::findOrFail($class_id)->level;
        return redirect()->route('classe.show', $classLevel);
    }
}
