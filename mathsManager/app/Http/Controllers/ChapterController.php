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
        'analyse1' => '#87CBEA',
        'analyse2' => '#6CAAEE',
        'suites' => '#7CE6D0',
        'geometrie' => '#E6D07C',
        'probabilites' => '#E67C7C',
        'trigonometrie' => '#E6AA74',
        'complexes' => '#794A11',
        'arithmetique' => '#CF8FE6',
        'matrices' => '#EC9CDB',
    ];

    public function changeAnalyse2Color() // admin
    {
        $chapters = Chapter::where('theme', '#6CAAEE')->get();
        foreach ($chapters as $chapter) {
            $chapter->theme = '#CCA9DD';
            $chapter->save();
        }

        return redirect()->route('classe.show', 2);
    }

    public function changeSuitesColor() // admin
    {
        $chapters = Chapter::where('theme', '#7CE6D0')->get();
        foreach ($chapters as $chapter) {
            $chapter->theme = '#80CEE1';
            $chapter->save();
        }

        return redirect()->route('classe.show', 2);
    }
    
    public function changeAnalyse1Color() // admin
    {
        $chapters = Chapter::where('theme', '#244D7E')->get();
        foreach ($chapters as $chapter) {
            $chapter->theme = '#318CE7';
            $chapter->save();
        }

        return redirect()->route('classe.show', 2);
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
        $themeColors = $this->themeColors;
        return view('chapter.create', compact('classes', 'classeActive', 'themeColors'));
    }

    public function store(Request $request) // admin
    {
        $request->validate([
            'title' => 'required',
            'class_id' => 'required'
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
        $chapter->delete();
        $classLevel = Classe::findOrFail($chapter->class_id)->level;
        // renumérotation des id des exercices restants
        $exercises = Exercise::all();
        foreach ($exercises as $index => $exercise) {
            $exercise->id = $index + 1;
            $exercise->save();
        }

        return redirect()->route('classe.show', $classLevel);
    }
}
