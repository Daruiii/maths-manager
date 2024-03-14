<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Chapter;

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
        // display chapters of the class
        $chapters = Chapter::where('class_id', $classe->id)->get();
        return view('classe.show', compact('classe', 'chapters'));
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

    public function edit($level) // admin
    {
        $classe = Classe::where('level', $level)->firstOrFail();
        return view('classe.edit', compact('classe'));
    }

    public function update(Request $request, $level)
    {
        $classe = Classe::where('level', $level)->firstOrFail();

        $request->validate([
            'name' => 'required',
            'level' => 'required'
        ]);

        $classe->update($request->all());

        return redirect()->route('classe.index');
    }

    public function destroy($level)
    {
        $classe = Classe::where('level', $level)->firstOrFail();
        $classe->delete();

        return redirect()->route('classe.index');
    }

}
