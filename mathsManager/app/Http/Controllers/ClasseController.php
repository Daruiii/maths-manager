<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;
use Illuminate\Support\Facades\Log;
use App\Services\OrderingService;

class ClasseController extends Controller
{
    protected OrderingService $orderingService;
    
    public function __construct(OrderingService $orderingService)
    {
        $this->orderingService = $orderingService;
    }

    public function reorderAllElements()
    {
        try {
            // Utiliser le service pour recalculer tous les ordres globaux
            $this->orderingService->recalculateAllGlobalExerciseOrders();

            return redirect()->route('classe.index')->with('success', 'Elements reordered successfully');
        } catch (\Exception $e) {
            Log::error("Failed to reorder elements: " . $e->getMessage());
            return back()->withErrors('Failed to reorder elements.');
        }
    }
    public function index() // admin
    {
        $classes = Classe::orderBy('display_order')->get();
        return view('classe.index', compact('classes'));
    }

    public function show($level) // student
    {
        $classe = Classe::where('level', $level)->firstOrFail();
        // Get chapters with their recaps, ordered by local order
        $chapters = Chapter::where('class_id', $classe->id)
            ->with('recaps')
            ->orderBy('order') // Maintenant c'est l'ordre local dans la classe
            ->get();
            
        if ($chapters->isEmpty()) {
            return view('classe.show', compact('classe', 'chapters'));
        }
        
        // Get subchapters for the first chapter (if needed)
        $subchapters = Subchapter::where('chapter_id', $chapters->first()->id)
            ->orderBy('order')
            ->get();
            
        return view('classe.show', compact('classe', 'chapters', 'subchapters'));
    }

    public function create() // admin
    {
        return view('classe.create');
    }

    public function store(Request $request) // admin
    {
        $request->validate([
            'name' => 'required',
            'level' => 'required',
            'hidden' => 'boolean'
        ]);

        Classe::create($request->only(['name', 'level', 'hidden']));

        return redirect()->route('classe.index');
    }

    public function edit($id) // admin
    {
        $classe = Classe::where('id', $id)->firstOrFail();
        return view('classe.edit', compact('classe'));
    }

    public function update(Request $request, $id)
    {
        $classe = Classe::where('id', $id)->firstOrFail();
        $request->validate([
            'name' => 'required',
            'level' => 'required',
        ]);
    
        $classe->name = $request->name;
        $classe->level = $request->level;
        $classe->hidden = $request->has('hidden');

        $classe->save();
        $classes = Classe::all();
        return redirect()->route('classe.index', compact('classes'));
    }

    public function destroy($id)
    {
        $classe = Classe::where('id', $id)->firstOrFail();
        $classe->delete();

        return redirect()->route('classe.index');
    }
}
