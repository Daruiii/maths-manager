<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultipleChapter;
use Illuminate\Contracts\Support\ValidatedData;

class MultipleChapterController extends Controller
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

    public function index()
    {
        $multipleChapters = MultipleChapter::all();
        return view('multipleChapter.index', compact('multipleChapters'));
    }

    public function show($id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);
        return view('multipleChapter.show', compact('multipleChapter'));
    }

    public function create()
    {
        $themeColors = $this->themeColors;
        return view('multipleChapter.create', compact('themeColors'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'theme' => 'required'
        ]);

        MultipleChapter::create($validatedData);
        return redirect()->route('multiple_chapters.index');
    }

    public function edit($id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);
        $themeColors = $this->themeColors;
        return view('multipleChapter.edit', compact('multipleChapter', 'themeColors'));
    }

    public function update(Request $request, $id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'theme' => 'required'
        ]);

        $multipleChapter->update($request->all());
        return redirect()->route('multiple_chapters.index');
    }

    public function destroy($id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);
        $multipleChapter->delete();
        return redirect()->route('multiple_chapters.index');
    }
}
