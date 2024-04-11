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
            $dsExercises->where(function($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
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
    
    protected function convertCustomLatexToHtml($latexContent)
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
        // "\textbf{hello world}" will be "\textbf{hello \ world}" pour ajouter un espace en gros
        // $allTexttags = ["textbf", "textit", "textup", "texttt"];
        // // on content of each {}, replace space with \
        // foreach ($allTexttags as $tag) {
        //     $pattern = "/\\\\{$tag}\{(.*?)\}/";
        //     $cleanedContent = preg_replace_callback($pattern, function ($matches) {
        //         return str_replace(" ", " \\ ", $matches[0]);
        //     }, $cleanedContent);
        // }
        // Convertir les commandes personnalisées en HTML
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
        $chapters = Chapter::all();
        $multipleChapters = MultipleChapter::all();
        return view('dsExercise.create', compact('chapters', 'multipleChapters'));
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
            'chapters' => 'required|array',
            'chapters.*' => 'exists:chapters,id'
        ]);

        $dsExercise = new DsExercise();
        $dsExercise->harder_exercise = $request->has('harder_exercise') ? true : false;
        $dsExercise->fill($request->all());
        $dsExercise->statement = $this->convertCustomLatexToHtml($dsExercise->statement);
        $dsExercise->latex_statement = $dsExercise->statement;
        $dsExercise->save();

        $dsExercise->chapters()->attach($request->chapters);
        return redirect()->route('ds_exercises.index');
    }

    public function show(string $id)
    {
        $dsExercise = DsExercise::with('chapters')->findOrFail($id);
        // multiple_chapter_id 
        $multipleChapter = MultipleChapter::findOrFail($dsExercise->multiple_chapter_id);
        return view('dsExercise.show', compact('dsExercise', 'multipleChapter'));
    }

    public function edit(string $id)
    {
        $dsExercise = DsExercise::with('chapters')->findOrFail($id);
        $chapters = Chapter::all();
        $multipleChapters = MultipleChapter::all();
        return view('dsExercise.edit', compact('dsExercise', 'chapters', 'multipleChapters'));
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
            'chapters' => 'required|array',
            'chapters.*' => 'exists:chapters,id'
        ]);

        $harder_exercise = $request->has('harder_exercise') ? true : false;
        $statement = $this->convertCustomLatexToHtml($request->statement);

        $dsExercise = DsExercise::findOrFail($id);
        $dsExercise->update([
            'harder_exercise' => $harder_exercise,
            'time' => $request->time,
            'name' => $request->name,
            'statement' => $statement,
            'latex_statement' => $request->statement,
            'multiple_chapter_id' => $request->multiple_chapter_id
        ]);

        $dsExercise->chapters()->sync($request->chapters);
        return redirect()->route('ds_exercise.show', $id);
    }

    public function destroy(string $id)
    {
        $dsExercise = DsExercise::findOrFail($id);
        $dsExercise->delete();
        return redirect()->route('ds_exercises.index');
    }
}
