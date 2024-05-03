<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recap;

class RecapController extends Controller
{
    // Méthode pour show un récap
    public function show($id)
    {
        $recap = Recap::find($id);
        return view('recap.show', compact('recap'));
    }

    // Méthode pour créer un récap
    public function create()
    {
        return view('recap.create');
    }

    // Méthode pour stocker un récap
    public function store(Request $request)
    {
        // title et chapter
        $request->validate([
            'title' => 'nullable',
            'chapter_id' => 'required'
        ]);

        // Création du récap
        $recap = new Recap();
        $recap->title = $request->title;
        $recap->chapter_id = $request->chapter_id;
        $recap->save();

        return redirect()->route('recap.show', $recap->id);
    }
}
