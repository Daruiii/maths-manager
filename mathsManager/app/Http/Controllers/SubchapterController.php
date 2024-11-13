<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subchapter;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\Exercise;
use App\Models\QuizzQuestion;
use App\Models\QuizzDetail;

class SubchapterController extends Controller
{
    public function index() // students
    {
        $subchapters = Subchapter::all();
        return view('subchapter.index', compact('subchapters'));
    }

    public function show($id) // students
    {
        $subchapter = Subchapter::findOrFail($id);
        $exercises = Exercise::where('subchapter_id', $id)->orderBy('order', 'asc')->get();
        // get classe lvl
        $chapter_id = $subchapter->chapter_id;
        $classe_id = Chapter::findOrFail($chapter_id)->class_id;
        $classe = Classe::findOrFail($classe_id);
        return view('subchapter.show', compact('subchapter', 'exercises', 'classe'));
    }

    public function create($id) // admin
    {
        $chapter_id = $id;
        $chapters = Chapter::all();
        $nextOrder = Subchapter::where('chapter_id', $chapter_id)->max('order') + 1;
        return view('subchapter.create', compact('chapter_id', 'chapters', 'nextOrder'));
    }

    public function store(Request $request) // admin
    {
        $request->validate([
            'title' => 'required',
            'chapter_id' => 'required',
            'order' => 'required'
        ]);

        Subchapter::create($request->all());
        $chapter_id = $request->chapter_id;
        $class_id = Chapter::findOrFail($chapter_id)->class_id;
        $class_level = Classe::findOrFail($class_id)->level;

        return redirect()->route('classe.show', $class_level);
    }

    public function edit($id) // admin
    {
        $subchapter = Subchapter::findOrFail($id);
        $chapter_id = $subchapter->chapter_id;
        $chapters = Chapter::all();
        return view('subchapter.edit', compact('subchapter', 'chapter_id', 'chapters'));
    }

    public function update(Request $request, $id) // admin
    {
        $subchapter = Subchapter::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'chapter_id' => 'required'
        ]);

        $subchapter->update($request->all());
        $chapter_id = $subchapter->chapter_id;
        $class_id = Chapter::findOrFail($chapter_id)->class_id;
        $class_level = Classe::findOrFail($class_id)->level;

        return redirect()->route('classe.show', $class_level);
    }

    public function destroy($id) // admin
    {
        $subchapter = Subchapter::findOrFail($id);
        
        // Delete associated quiz details with questions
        $quizzDetails = QuizzDetail::whereHas('question', function($query) use ($id) {
            $query->where('subchapter_id', $id);
        })->get();

        foreach ($quizzDetails as $quizzDetail) {
            $quizzDetail->delete();
        }
        
        // Delete associated quiz questions
        QuizzQuestion::where('subchapter_id', $id)->delete();
        
        $subchapter->delete();
        $chapter_id = $subchapter->chapter_id;
        $class_id = Chapter::findOrFail($chapter_id)->class_id;
        $class_level = Classe::findOrFail($class_id)->level;

        return redirect()->route('classe.show', $class_level);
    }
}
