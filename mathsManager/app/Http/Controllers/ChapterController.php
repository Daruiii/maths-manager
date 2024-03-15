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
        return view('chapter.create');
    }

    public function store(Request $request) // admin
    {
        $request->validate([
            'title' => 'required',
            'class_id' => 'required'
        ]);

        Chapter::create($request->all());

        return redirect()->route('chapter.index');
    }

    public function edit($id) // admin
    {
        $chapter = Chapter::findOrFail($id);
        return view('chapter.edit', compact('chapter'));
    }

    public function update(Request $request, $id) // admin
    {
        $chapter = Chapter::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'class_id' => 'required'
        ]);

        $chapter->update($request->all());

        return redirect()->route('chapter.index');
    }

    public function destroy($id) // admin
    {
        $chapter = Chapter::findOrFail($id);
        $chapter->delete();
        $classLevel = Classe::findOrFail($chapter->class_id)->level;

        return redirect()->route('classe.show', $classLevel);
    }
}
