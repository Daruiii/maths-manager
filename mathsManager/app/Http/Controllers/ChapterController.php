<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Classe;

class ChapterController extends Controller
{
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

    public function create() // admin
    {
        $classes = Classe::all();
        return view('chapter.create', compact('classes'));
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

        return view('chapter.edit', compact('chapter', 'classes'));
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

        return redirect()->route('classe.show', $classLevel);
    }
}
