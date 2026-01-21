<?php

namespace App\Http\Controllers;

use App\Models\Content;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Services\FileUploadService;
use App\Http\Requests\Content\UpdateContentRequest;

class ContentController extends Controller
{
    protected FileUploadService $fileUploadService;

    public function __construct(FileUploadService $fileUploadService)
    {
        $this->fileUploadService = $fileUploadService;
    }
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
    
    public function update(UpdateContentRequest $request, $section)
    {
        // Récupérer la section spécifique
        $content = Content::where('section', $section)->firstOrFail();

        // Gestion de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($content->image) {
                $this->fileUploadService->delete($content->image, true);
            }

            // Upload la nouvelle image via le service
            try {
                $imagePath = $this->fileUploadService->upload(
                    file: $request->file('image'),
                    context: 'contents',
                    identifier: $section,
                    type: 'image',
                    isPublic: true,
                    customName: $section
                );
                $content->image = $imagePath;
            } catch (\Exception $e) {
                return back()->withErrors('Échec de l\'upload de l\'image : ' . $e->getMessage());
            }
        } elseif ($request->input('remove_image') === 'true' && $content->image) {
            // Supprimer l'image actuelle
            $this->fileUploadService->delete($content->image, true);
            $content->image = null;
        }

        // Mise à jour des autres champs
        $content->title = $request->input('title');
        $content->content = $request->input('content');
        $content->save();

        return Redirect::route('content.edit', $section)->with('success', 'Contenu mis à jour avec succès');
    }
    
}
