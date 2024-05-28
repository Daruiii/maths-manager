<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DsExercise;
use App\Models\Chapter;
use App\Models\MultipleChapter;
use Illuminate\Pagination\Paginator;

class DsExerciseController extends Controller
{
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
            // PA
            "/\\\\PA\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Première partie $1</span></div>",
            "/\\\\PA/" =>"<div class='latex latex-center'><span class='textbf'>Première partie</span></div>",
            // PB
            "/\\\\PB\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie $1</span></div>",
            "/\\\\PB/" =>"<div class='latex latex-center'><span class='textbf'>Deuxième partie</span></div>",
            // PC
            "/\\\\PC\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie $1</span></div>",
            "/\\\\PC/" =>"<div class='latex latex-center'><span class='textbf'>Troisième partie</span></div>",
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

        // Remplacer les images pour chaque \graph{0.5}{photoenbeuch.pnj} dans l'ordre des images[]
        if (count($images) > 0) {
        $imageIndex = 0;
        $cleanedContent = preg_replace_callback("/\\\\graph\{(.*?)\}\{(.*?)\}/", function ($matches) use (&$images, &$imageIndex) {
            $imagePath = $images[$imageIndex] ?? 'ds_exercises/img_placeholder.png';
            $imageIndex++;
            $percent = $matches[1]*100;
            return "<div class='latex-center'><img src='" . asset('storage/' . $imagePath) . "' alt='$matches[2]' class='png' style='width: $percent%;'></div>";
        }, $cleanedContent);
        } else {
            $cleanedContent = preg_replace("/\\\\graph\{([0-9]+)\}\{(.*?)\}/", "<img src='https://via.placeholder.com/150' alt='$2' class='png' style='width: $1%;'>", $cleanedContent);
        }

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
        }
        else {
            $filterActivated = false;
            $chapterActivated = null;
        }
        $dsExercises = $dsExercises->paginate(10)->withQueryString();
        $multipleChapters = MultipleChapter::all();

        return view('dsExercise.index', compact('dsExercises', 'multipleChapters', 'filterActivated', 'chapterActivated'));
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

    public function show(string $id, string $filter)
    {
        $dsExercise = DsExercise::findOrFail($id);
        // multiple_chapter_id 
        $multipleChapter = MultipleChapter::findOrFail($dsExercise->multiple_chapter_id);
        if ($filter == 'true') {
            $dsExercises = DsExercise::where('multiple_chapter_id', $dsExercise->multiple_chapter_id)->get();
        }
        else {
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
            'filter' => 'nullable|string',
            // 'chapters' => 'required|array',
            // 'chapters.*' => 'exists:chapters,id'
        ]);

        $dsExercise = DsExercise::findOrFail($id);
        $dsExercise->fill($request->except('images'));
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->latex_statement = $dsExercise->statement;
        $imagePaths = [];
        if ($request->hasFile('images')) {
            // remove old image
            $images = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
            if ($images) {
                foreach ($images as $image) {
                    unlink($image);
                }
            }
            foreach ($request->file('images') as $key => $image) {
                $imageName = ($key + 1) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id);
                $image->move($destinationPath, $imageName);
                $imagePaths[] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . $imageName;
            }
        }
        else {
            $imagePaths = glob(public_path('storage/ds_exercises/ds_exercise_' . $dsExercise->id . '/*'));
            foreach ($imagePaths as $key => $imagePath) {
                $imagePaths[$key] = 'ds_exercises/' . 'ds_exercise_' . $dsExercise->id . '/' . basename($imagePath);
            }
        }
        // give images to the convertCustomLatexToHtml function
        $dsExercise->statement = $this->convertCustomLatexToHtml($dsExercise->statement, $imagePaths);
        $dsExercise->save();

        // $dsExercise->chapters()->sync($request->chapters);
        return redirect()->route('ds_exercise.show', ['id' => $dsExercise->id, 'filter' => $request->filter]);
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
