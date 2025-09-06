<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;
use Illuminate\Support\Facades\Log;

class ClasseController extends Controller
{

    public function reorderAllElements()
    {
        try {
            // Get all classes ordered by id
            $classes = Classe::orderBy('id')->get();

            $order = 1;

            foreach ($classes as $class) {
                // Get all chapters of the class ordered by id
                $chapters = Chapter::where('class_id', $class->id)->orderBy('id')->get();

                foreach ($chapters as $chapter) {
                    // Assign the order to the chapter
                    $chapter->order = $order++;
                    $chapter->save();

                    // Get all subchapters of the chapter ordered by id
                    $subchapters = Subchapter::where('chapter_id', $chapter->id)->orderBy('id')->get();

                    foreach ($subchapters as $index => $subchapter) {
                        // Assign the order to the subchapter
                        $subchapter->order = $index + 1;
                        $subchapter->save();
                    }
                }
            }
            // Get all classes ordered by id and update the order of the exercises in each subchapter of each chapter of each class.
            $order = 1;

            foreach ($classes as $class) {
                $chapters = Chapter::where('class_id', $class->id)->orderBy('order')->get();
                foreach ($chapters as $chapter) {
                    $subchapters = Subchapter::where('chapter_id', $chapter->id)->orderBy('order')->get();

                    foreach ($subchapters as $subchapter) {
                        $exercises = $subchapter->exercises()->orderBy('order')->get();

                        foreach ($exercises as $exercise) {
                            $exercise->order = $order++;
                            $exercise->save();
                        }
                    }
                }
            }

            return redirect()->route('classe.index')->with('success', 'Elements reordered successfully');
        } catch (\Exception $e) {
            Log::error("Failed to reorder elements: " . $e->getMessage());
            return back()->withErrors('Failed to reorder elements.');
        }
    }
    public function index() // admin
    {
        $classes = Classe::all();
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
