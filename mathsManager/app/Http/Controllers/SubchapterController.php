<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Subchapter;

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
        return view('subchapter.show', compact('subchapter'));
    }

    public function create() // admin
    {
        return view('subchapter.create');
    }

    public function store(Request $request) // admin
    {
        $request->validate([
            'title' => 'required',
            'chapter_id' => 'required'
        ]);

        Subchapter::create($request->all());

        return redirect()->route('subchapter.index');
    }

    public function edit($id) // admin
    {
        $subchapter = Subchapter::findOrFail($id);
        return view('subchapter.edit', compact('subchapter'));
    }

    public function update(Request $request, $id) // admin
    {
        $subchapter = Subchapter::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'chapter_id' => 'required'
        ]);

        $subchapter->update($request->all());

        return redirect()->route('subchapter.index');
    }

    public function destroy($id) // admin
    {
        $subchapter = Subchapter::findOrFail($id);
        $subchapter->delete();

        return redirect()->route('subchapter.index');
    }
}
