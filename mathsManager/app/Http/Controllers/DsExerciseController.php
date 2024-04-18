<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DsExercise;
use App\Models\Chapter;
use App\Models\MultipleChapter;

class DsExerciseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->get('search');
        $dsExercises = DsExercise::with('chapters')->orderBy('multiple_chapter_id', 'asc');

        if ($search) {
            $dsExercises->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('multiple_chapter_id')) {
            $dsExercises->where('multiple_chapter_id', $request->multiple_chapter_id);
        }

        $dsExercises = $dsExercises->get();
        $multipleChapters = MultipleChapter::all();

        return view('dsExercise.index', compact('dsExercises', 'multipleChapters'));
    }

    protected function convertCustomLatexToHtml($latexContent, $images = [])
    {
        // Nettoyage initial du contenu et remplacement des espaces non sécables
        $cleanedContent = str_replace("\xc2\xa0", " ", $latexContent);

        // Unification de la syntaxe LaTeX vers des spans et des divs pour le rendu que KATEX ne gère pas ou mal
        $patterns = [
            "/\\\\begin\{itemize\}/" => "<ul>",
            "/\\\\end\{itemize\}/" => "</ul>",
            "/\\\\begin\{enumerate\}/" => "<ol>",
            "/\\\\end\{enumerate\}/" => "</ol>",
            "/\\\\item/" => "<li>",
            "/\\\\begin\{center\}/" => "<div class='latex latex-center'>",
            "/\\\\end\{center\}/" => "</div>",
            "/\\\\begin\{minipage\}/" => "<div class='latex-minipage'>",
            "/\\\\end\{minipage\}/" => "</div>",
            "/\\\\begin\{tabularx\}\{(.+?)\}/" => "<table class='latex-tabularx' style='width: $1%;'>",
            "/\\\\end\{tabularx\}/" => "</table>",
            "/\\\\begin\{boxed\}/" => "<span class='latex latex-boxed'>",
            "/\\\\end\{boxed\}/" => "</span>",
            // "/\\\\\\\/" => "<br>",
            "/\{([0-9.]+)\\\\linewidth\}/" => "<style='width: calc($1% - 2em);'>",
            "/\{\\\\linewidth\}\{(.+?)\}/" => "<style='width: $1;'>",
            "/\\\\hline/" => "<hr>",
            "/\\\\renewcommand\\\\arraystretch\{0.9\}/" => "",
            // for all text like texttt textit textbf
            "/\\\\(textbf|textit|texttt|textup)\{(.*?)\}/" => "<span class='$1'>$2</span>",
            // "/\\\\listpart\{(.*?)\}/" => "<div class='listpart'>$1</div>",
            // "/\\\\abs\{(.*?)\}/" => "<span class='abs'>| $1 |</span>",
            // "/\\\\norm\{(.*?)\}/" => "<span class='norm'>‖ $1 ‖</span>",
            // "/\\\\times/" => "×",
            // "/\\\\qquad/" => "&nbsp;&nbsp;&nbsp;&nbsp;",
            // "/\\\\quad/" => "&nbsp;&nbsp;",
        ];

        // Appliquer les remplacements pour les maths et les listes
        foreach ($patterns as $pattern => $replacement) {
            $cleanedContent = preg_replace($pattern, $replacement, $cleanedContent);
        }

        // Remplacer les images pour chaque \includegraphics{25}{photoenbeuch.pnj} dans l'ordre des images[]
        $imageIndex = 0;
        $cleanedContent = preg_replace_callback("/\\\\includegraphics\{([0-9]+)\}\{(.*?)\}/", function ($matches) use (&$imageIndex, $images) {
            $imagePath = $images[$imageIndex];
            $imageIndex++;
            return "<img src='" . asset('storage/' . $imagePath) . "' alt='$matches[2]' class='png' style='width: $matches[1]%;'>";
        }, $cleanedContent);

        $customCommands = [
            "\\enmb" => "<ol class='enumb'>", "\\fenmb" => "</ol>",
            "\\enm" => "<ol>", "\\fenm" => "</ol>",
            "\\itm" => "<ul class='point'>", "\\fitm" => "</ul>",
            // Convertir les environnements théoriques
            // "/\\\\(prop|cor|thm|definition|rappels|rem)\\b/" => "<div class='latex-$1'>",
            // "\\finboite" => "</div>",
        ];

        foreach ($customCommands as $command => $html) {
            $cleanedContent = str_replace($command, $html, $cleanedContent);
        }

        return $cleanedContent;
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
        $dsExercise->statement = $this->convertCustomLatexToHtml($dsExercise->statement, $imagePaths);
        $dsExercise->save();

        // $dsExercise->chapters()->attach($request->chapters);
        return redirect()->route('ds_exercises.index');
    }

    public function show(string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);
        // multiple_chapter_id 
        $multipleChapter = MultipleChapter::findOrFail($dsExercise->multiple_chapter_id);
        return view('dsExercise.show', compact('dsExercise', 'multipleChapter'));
    }

    public function edit(string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);
        $oldImagesPath = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
        $oldImages = [];
        // pour chaque fichier dans le dossier ds_exercise_id must equal http://localhost:8000/storage/ds_exercises/ds_exercise_1/2.jpg
        
        foreach ($oldImagesPath as $oldImagePath) {
            $oldImages[] = asset('storage/' . $oldImagePath);
        }
        dd($oldImages);
        $multipleChapters = MultipleChapter::all();
        return view('dsExercise.edit', compact('dsExercise', 'multipleChapters', 'oldImages'));
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
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id'
        ]);

        $dsExercise = DsExercise::findOrFail($id);
        $dsExercise->fill($request->except('images'));
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->latex_statement = $dsExercise->statement;
        $imagePaths = [];
        if ($request->hasFile('images')) {
            // delete old images
            $oldImages = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
            foreach ($oldImages as $oldImage) {
                unlink($oldImage);
            }
            foreach ($request->file('images') as $key => $image) {
                $imageName = ($key + 1) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id);
                $image->move($destinationPath, $imageName);
                $imagePaths[] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . $imageName;
            }
        }
        // give images to the convertCustomLatexToHtml function
        $dsExercise->statement = $this->convertCustomLatexToHtml($dsExercise->statement, $imagePaths);
        $dsExercise->save();

        // $dsExercise->chapters()->sync($request->chapters);
        return redirect()->route('ds_exercises.index');
    }

    public function destroy(string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);
        // delete images
        $images = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
        if ($images) {
            foreach ($images as $image) {
                unlink($image);
            }
            rmdir(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id));
        }
        $dsExercise->delete();
        return redirect()->route('ds_exercises.index');
    }
}
