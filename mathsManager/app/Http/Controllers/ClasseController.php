<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;

class ClasseController extends Controller
{

    public function index() // admin
    {
        $classes = Classe::all();
        return view('classe.index', compact('classes'));
    }

    public function show($level) // student
    {
        $classe = Classe::where('level', $level)->firstOrFail();
        // $chapters = Chapter::where('class_id', $classe->id)->get();
        //  get chapter with there recap
        $chapters = Chapter::where('class_id', $classe->id)->with('recaps')->get();
        if ($chapters->isEmpty()) {
            return view('classe.show', compact('classe', 'chapters'));
        }
        $subchapters = Subchapter::where('chapter_id', $chapters->first()->id)->get();
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
            'level' => 'required'
        ]);

        Classe::create($request->all());

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
            'level' => 'required'
        ]);

        $classe->update($request->all());
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
