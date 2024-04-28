<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultipleChapter;
use Illuminate\Contracts\Support\ValidatedData;

class MultipleChapterController extends Controller
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

    public function changeAnalyse2Color() // admin
    {
        $chapters = MultipleChapter::where('theme', '#6CAAEE')->get();
        foreach ($chapters as $chapter) {
            $chapter->theme = '#CCA9DD';
            $chapter->save();
        }

        return redirect()->route('classe.show', 2);
    }

    public function changeSuitesColor() // admin
    {
        $chapters = MultipleChapter::where('theme', '#7CE6D0')->get();
        foreach ($chapters as $chapter) {
            $chapter->theme = '#80CEE1';
            $chapter->save();
        }

        return redirect()->route('classe.show', 2);
    }
    
    public function changeAnalyse1Color() // admin
    {
        $chapters = MultipleChapter::where('theme', '#87CBEA')->get();
        foreach ($chapters as $chapter) {
            $chapter->theme = '#318CE7';
            $chapter->save();
        }

        return redirect()->route('classe.show', 2);
    }

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
            'theme' => 'nullable'
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
            'theme' => 'nullable'
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
