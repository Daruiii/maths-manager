<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class ContentController extends Controller
{
    public function index()
    {
        // Récupérer toutes les sections de contenu
        $contents = Content::all();

        // Passer les contenus à la vue
        return view('content.index', compact('contents'));
    }
    /**
     * Affiche le formulaire de modification pour une section de contenu.
     */
    public function edit($section)
    {
        // Récupérer le contenu correspondant à la section
        $content = Content::where('section', $section)->firstOrFail();
        return view('content.edit', compact('content'));
    }

    /**
     * Met à jour une section de contenu dans la base de données.
     */
    
    public function update(Request $request, $section)
    {
        // Validation des données du formulaire
        $request->validate([
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Limité à 2 Mo
        ]);
    
        // Récupérer la section spécifique
        $content = Content::where('section', $section)->firstOrFail();
    
        // Chemin du dossier pour stocker l'image
        $imageDirectory = 'storage/contents/';
    
        // Supprimer l'ancienne image si une nouvelle est téléchargée
        if ($request->hasFile('image')) {
            // Supprimer l'image existante si elle est présente
            if ($content->image && file_exists(public_path($content->image))) {
                unlink(public_path($content->image));
            }
    
            // Enregistrer la nouvelle image
            $newImage = $request->file('image');
            $imageName = $content->section . '.' . $newImage->getClientOriginalExtension();
            $newImage->move(public_path($imageDirectory), $imageName);
    
            // Mettre à jour le chemin de l'image dans la base de données
            $content->image = $imageDirectory . $imageName;
        } elseif ($request->input('remove_image') === 'true' && $content->image) {
            // Si l'utilisateur a choisi de supprimer l'image actuelle
            if (file_exists(public_path($content->image))) {
                unlink(public_path($content->image));
            }
            $content->image = null; // Retirer l'image de la base de données
        }
    
        // Mise à jour des autres champs
        $content->title = $request->input('title');
        $content->content = $request->input('content');
        $content->save();
    
        return Redirect::route('content.edit', $section)->with('success', 'Contenu mis à jour avec succès');
    }
    
}
