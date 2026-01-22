<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MultipleChapter;
use App\Http\Requests\MultipleChapter\StoreMultipleChapterRequest;
use App\Http\Requests\MultipleChapter\UpdateMultipleChapterRequest;

class MultipleChapterController extends Controller
{

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
        $themeColors = config('themes.chapter_colors');
        return view('multipleChapter.create', compact('themeColors'));
    }

    public function store(StoreMultipleChapterRequest $request)
    {
        MultipleChapter::create($request->validated());
        return redirect()->route('multiple_chapters.index');
    }

    public function edit($id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);
        $themeColors = config('themes.chapter_colors');
        return view('multipleChapter.edit', compact('multipleChapter', 'themeColors'));
    }

    public function update(UpdateMultipleChapterRequest $request, $id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);

        $multipleChapter->update($request->validated());
        return redirect()->route('multiple_chapters.index');
    }

    public function destroy($id)
    {
        $multipleChapter = MultipleChapter::findOrFail($id);
        $multipleChapter->delete();
        return redirect()->route('multiple_chapters.index');
    }
}
