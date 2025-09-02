<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DsExercise;
use App\Models\Chapter;
use App\Models\MultipleChapter;
use Illuminate\Pagination\Paginator;
use App\Services\LatexToHtmlConverter;

class DsExerciseController extends Controller
{

    public function index(Request $request)
    {
        $search = $request->get('search');
        $dsExercises = DsExercise::with('chapters')->orderBy('created_at', 'desc');

        if ($search) {
            $dsExercises->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('multiple_chapter_id')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id);
            $filterActivated = true;
            $chapterActivated = MultipleChapter::findOrFail($request->multiple_chapter_id);
        } else {
            $filterActivated = false;
            $chapterActivated = null;
        }

        if ($request->filled('type')) {
            $dsExercises->where('type', $request->type);
            $typeActivated = $request->type;
            $typeFilterActivated = true;
        } else {
            $typeActivated = null;
            $typeFilterActivated = false;
        }

        if ($request->filled('academy')) {
            $dsExercises->where('academy', $request->academy);
            $academyActivated = $request->academy;
            $academyFilterActivated = true;
        } else {
            $academyActivated = null;
            $academyFilterActivated = false;
        }

        // Filter by both `type` and `academy` if both are provided
        if ($request->filled('type') && $request->filled('academy')) {
            $dsExercises->where('type', $request->type)
                        ->where('academy', $request->academy);
        }

        // Filter by both `multiple_chapter_id` and `type` if both are provided
        if ($request->filled('multiple_chapter_id') && $request->filled('type')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id)
                        ->where('type', $request->type);
        }

        // Filter by both `multiple_chapter_id` and `academy` if both are provided
        if ($request->filled('multiple_chapter_id') && $request->filled('academy')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id)
                        ->where('academy', $request->academy);
        }

        // Filter by all three `multiple_chapter_id`, `type`, and `academy` if all are provided
        if ($request->filled('multiple_chapter_id') && $request->filled('type') && $request->filled('academy')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id)
                        ->where('type', $request->type)
                        ->where('academy', $request->academy);
        }

        $academies = DsExercise::distinct('academy')->pluck('academy');

        $dsExercises = $dsExercises->paginate(10)->withQueryString();
        $multipleChapters = MultipleChapter::all();

        return view('dsExercise.index', compact('dsExercises', 'multipleChapters', 'filterActivated', 'chapterActivated', 
        'typeActivated', 'typeFilterActivated', 'academyActivated', 'academyFilterActivated', 'academies'));
    }

    public function create()
    {
        $multipleChapters = MultipleChapter::all();
        return view('dsExercise.create', compact('multipleChapters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'multiple_chapter_id' => 'required|exists:multiple_chapters,id',
            'harder_exercise' => 'boolean',
            'time' => 'required|integer',
            'name' => 'nullable|max:255',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'correction_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'type' => 'nullable|string',
            'year' => 'nullable|integer',
            'academy' => 'nullable|string',
            'date_data' => 'nullable|string',
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id'
        ]);
        $lastExercise = DsExercise::orderBy('id', 'desc')->first();
        $newExerciseId = $lastExercise ? $lastExercise->id + 1 : 1;
        while (DsExercise::find($newExerciseId)) {
            $newExerciseId++;
        }

        $dsExercise = new DsExercise();
        $dsExercise->fill($request->except('images'));
        $dsExercise->id = $newExerciseId;
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->latex_statement = $dsExercise->statement;
  
        // gestion des images
        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $key => $image) {
                $imageName = ($key + 1) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id);
                $image->move($destinationPath, $imageName);
                $imagePaths[] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . $imageName;
            }
        }
        // give images to the convertCustomLatexToHtml function
        $dsExercise->statement = LatexToHtmlConverter::convertForDsExercise($dsExercise->statement, $imagePaths);

        // gestion du PDF de correction
        if ($request->hasFile('correction_pdf')) {
            $pdf = $request->file('correction_pdf');
            // Définir le nom du fichier PDF
            $pdfName = 'correction_ds_' . $dsExercise->id . '.' . $pdf->getClientOriginalExtension();
            // Définir le chemin de destination
            $destinationPath = public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/correction/');
            // S'assurer que le dossier existe
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // Déplacer le fichier
            $pdf->move($destinationPath, $pdfName);
            // Enregistrer le chemin en base de données
            $dsExercise->correction_pdf = 'ds_exercises/ds_exercise_' . $dsExercise->id . '/correction/' . $pdfName;
        }
        $dsExercise->save();

        // $dsExercise->chapters()->attach($request->chapters);
        return redirect()->route('ds_exercises.index');
    }

    public function show(string $id, string $filter)
    {
        $dsExercise = DsExercise::findOrFail($id);
        // multiple_chapter_id 
        $multipleChapter = MultipleChapter::findOrFail($dsExercise->multiple_chapter_id);
        if ($filter == 'true') {
            $dsExercises = DsExercise::where('multiple_chapter_id', $dsExercise->multiple_chapter_id)->get();
        } else {
            $dsExercises = DsExercise::with('chapters')->orderBy('id')->get();
        }
        $nextExercise = $dsExercises->filter(function ($exercise) use ($dsExercise) {
            return $exercise->id > $dsExercise->id;
        })->first();
        $previousExercise = $dsExercises->filter(function ($exercise) use ($dsExercise) {
            return $exercise->id < $dsExercise->id;
        })->last();

        return view('dsExercise.show', compact('dsExercise', 'multipleChapter', 'nextExercise', 'filter', 'previousExercise'));
    }

    public function edit(string $id, string $filter)
    {
        $dsExercise = DsExercise::findOrFail($id);
        $multipleChapters = MultipleChapter::all();
        return view('dsExercise.edit', compact('dsExercise', 'multipleChapters', 'filter'));
    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'multiple_chapter_id' => 'required|exists:multiple_chapters,id',
            'harder_exercise' => 'boolean',
            'time' => 'required|integer',
            'name' => 'nullable|max:255',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'string',
            'filter' => 'nullable|string',
            'image_order' => 'nullable|string',
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id'
            'correction_pdf' => 'nullable|file|mimes:pdf|max:2048',
            'delete_correction_pdf' => 'nullable|boolean',
            'type' => 'nullable|string',
            'year' => 'nullable|integer',
            'academy' => 'nullable|string',
            'date_data' => 'nullable|string',
        ]);

        // dd($request->existing_images); // (string) "ds_exercises/ds_exercise_1/1.jpg" par exemple
        // dd($request->images); // fichier image ou null
        $dsExercise = DsExercise::findOrFail($id);
        $dsExercise->fill($request->except('images'));
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->latex_statement = $dsExercise->statement;
        $imagePaths = [];
        if ($request->hasFile('images')) {
            // remove old image
            $images = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
            $images = array_filter($images, function($image) {
            return !is_dir($image);
            });
            if ($images) {
                foreach ($images as $image) {
                    unlink($image);
                }
            }
            // ci-dessus, on récupérait les images qui ne sont pas des dossiers et qui sont dans le dossier de l'exercice pour les supprimer
            // avant de les remplacer par les nouvelles images
            foreach ($request->file('images') as $key => $image) {
                $imageName = ($key + 1) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id);
                $image->move($destinationPath, $imageName);
                $imagePaths[$key] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . $imageName;
            } // la, on ajoute les nouvelles images dans le tableau $imagePaths qui sera utilisé pour la conversion du latex en html.
            // if ($request->filled('existing_images')) {
            //     foreach ($request->existing_images as $key => $existingImage) {
            //         $imagePaths[$key] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . basename($existingImage);
            //     }
            // }
             // maintenant, ci-dessus, on ajoute les anciennes images dans le tableau $imagePaths qui sera utilisé pour la conversion du latex en html.
            // le problème, c'est l'ordre des images, elles devraient être dans l'ordre des images dans le contenu de l'exercice, mais comme on utilise deux arrays
            // différents, on ne peut pas garantir l'ordre des images. Il faudrait les fusionner dans un seul tableau pour garantir l'ordre des images.
            // qu'on prenne l'id de l'input dans lequel sont les images et qu'on les trie par ordre croissant. mais comment faire ça ? il faudrait envoyé l'id
            // de l'input dans lequel il se trouve à l'envoi du formulaire je pense.
        } else {
            $imagePaths = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
            foreach ($imagePaths as $key => $imagePath) {
                $imagePaths[$key] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . basename($imagePath);
            }
        }
        // on remet les anciennes dans le tableau $imagePaths qui sera utilisé pour la conversion du latex en html.
        // give images to the convertCustomLatexToHtml function, qui met les images dans l'order dans lequel on les veut dans le contenu de l'exercice
        // dd($imagePaths);
        $dsExercise->statement = LatexToHtmlConverter::convertForDsExercise($dsExercise->statement, $imagePaths);


        // si cocher la case pour supprimer le pdf de correction
        if ($request->has('delete_correction_pdf')) {
            // Supprimer le PDF de correction
            if ($dsExercise->correction_pdf) {
                $pdfPath = public_path('storage/' . $dsExercise->correction_pdf);
                if (file_exists($pdfPath)) {
                    unlink($pdfPath);
                }
                $dsExercise->correction_pdf = null;
            }
        }

        // gestion du PDF de correction
        if ($request->hasFile('correction_pdf')) {
            // Supprimer l'ancien PDF s'il existe
            if ($dsExercise->correction_pdf) {
                $oldPdfPath = public_path('storage/' . $dsExercise->correction_pdf);
                if (file_exists($oldPdfPath)) {
                    unlink($oldPdfPath);
                }
            }
            // Sauvegarde du nouveau PDF
            $pdf = $request->file('correction_pdf');
            $pdfName = 'correction_' . time() . '.' . $pdf->getClientOriginalExtension();
            $pdfPath = 'ds_exercises/ds_exercise_' . $dsExercise->id . '/correction/' . $pdfName;
            $pdf->move(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/correction'), $pdfName);
            $dsExercise->correction_pdf = $pdfPath;
        }

        $dsExercise->save();

        // $dsExercise->chapters()->sync($request->chapters);
        return redirect()->route('ds_exercises.index', ['filter' => $request->filter]);
    }

    public function destroy(string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);
    
        // 🔹 Supprimer les images
        $images = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
        if ($images) {
            foreach ($images as $image) {
                unlink($image);
            }
        }
    
        // 🔹 Supprimer le dossier de l'exercice si vide
        $exerciseFolder = public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id);
        if (is_dir($exerciseFolder)) {
            rmdir($exerciseFolder);
        }
    
        // 🔹 Supprimer le PDF de correction s'il existe
        if ($dsExercise->correction_pdf) {
            $pdfPath = public_path('storage/' . $dsExercise->correction_pdf);
            if (file_exists($pdfPath)) {
                unlink($pdfPath);
            }
        }
    
        // Supprimer l'exercice de la base de données
        $dsExercise->delete();
    
        return redirect()->route('ds_exercises.index');
    }
}
