<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subchapter;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\Exercise;

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
        $exercises = Exercise::where('subchapter_id', $id)->get();
        return view('subchapter.show', compact('subchapter', 'exercises'));
    }

    public function create($id) // admin
    {
        $chapter_id = $id;
        $chapters = Chapter::all();
        return view('subchapter.create', compact('chapter_id', 'chapters'));
    }

    public function store(Request $request) // admin
    {
        $request->validate([
            'title' => 'required',
            'chapter_id' => 'required'
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
        $subchapter->delete();
        $chapter_id = $subchapter->chapter_id;
        $class_id = Chapter::findOrFail($chapter_id)->class_id;
        $class_level = Classe::findOrFail($class_id)->level;

        return redirect()->route('classe.show', $class_level);
    }
}
