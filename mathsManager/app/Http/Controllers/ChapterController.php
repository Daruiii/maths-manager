<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\Subchapter;
use App\Models\Exercise;

class ChapterController extends Controller
{
    protected $themeColors = [
        'analyse1' => '#318CE7',
        'analyse2' => '#CCA9DD',
        'suites' => '#80CEE1',
        'geometrie' => '#E6D07C',
        'probabilites' => '#E67C7C',
        'trigonometrie' => '#E6AA74',
        'complexes' => '#794A11',
        'arithmetique' => '#CF8FE6',
        'matrices' => '#EC9CDB',
    ];

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
        $themeColors = $this->themeColors;
    
        $previousClassId = Classe::where('id', '<', $classeActive)->max('id');
        $nextOrder = 1;
    
        if ($previousClassId) {
            $nextOrder = Chapter::where('class_id', $previousClassId)->max('order') + 1;
        }
    
        return view('chapter.create', compact('classes', 'classeActive', 'themeColors', 'nextOrder'));
    }
    public function store(Request $request) // admin
    {
        $request->validate([
            'title' => 'required',
            'class_id' => 'required',
            'order' => 'required|integer'
        ]);
    
        $classLevel = Classe::findOrFail($request->class_id)->level;
    
        Chapter::create($request->all());
    
        return redirect()->route('classe.show', $classLevel);
    }

    public function edit($id) // admin
    {
        $chapter = Chapter::findOrFail($id);
        $classes = Classe::all();
        $themeColors = $this->themeColors;
        return view('chapter.edit', compact('chapter', 'classes', 'themeColors'));
    }

    public function update(Request $request, $id) // admin
    {
        $chapter = Chapter::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'class_id' => 'required'
        ]);

        $chapter->update($request->all());
        $classLevel = Classe::findOrFail($request->class_id)->level;

        return redirect()->route('classe.show', $classLevel);
    }

    public function destroy($id) // admin
    {
        $chapter = Chapter::findOrFail($id);
        $class_id = $chapter->class_id;
        $order = $chapter->order;
    
        $chapter->delete();
    
        // Decrement the order of the chapters that come after
        Chapter::where('class_id', $class_id)
            ->where('order', '>', $order)
            ->decrement('order');
    
        $classLevel = Classe::findOrFail($class_id)->level;
    
        return redirect()->route('classe.show', $classLevel);
    }
}
