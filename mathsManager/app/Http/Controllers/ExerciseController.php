<?php


namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\Subchapter;
use App\Models\Classe;
use Illuminate\Support\Facades\Log;

class ExerciseController extends Controller
{
    public function updateOrder(Request $request)
    {
        $orderData = $request->input('order');
        Log::info('updateOrder called with request: ', $request->all());
    
        foreach ($orderData as $data) {
            $id = $data['id'];
            $order = $data['order'];
            Log::info("Updating exercise $id with order $order");
    
            $exercise = Exercise::find($id);
            
            if ($exercise === null) {
                Log::info("Exercise $id not found");
                continue;
            }
    
            try {
                $exercise->order = $order;
                $exercise->save();
                Log::info("Saved exercise $id with order $order");
            } catch (\Exception $e) {
                Log::error("Failed to save exercise $id: " . $e->getMessage());
            }
        }
    
        return response()->json(['status' => 'success']);
    }

    public function index()
    {
        $search = request()->get('search');

        $exercises = Exercise::orderBy('created_at', 'desc');
        if ($search) {
            $exercises = $exercises->where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%');
        }
        $exercises = $exercises->paginate(10)->withQueryString();

        $subchapters = Subchapter::all();
        return view('exercise.index', compact('exercises', 'subchapters'));
    }

    public function create($id)
    {
        $subchapter_id = $id;
        $subchapters = Subchapter::all();
        return view('exercise.create', compact('subchapter_id', 'subchapters'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'subchapter_id' => 'required',
            'statement' => 'required',
            'clue' => 'nullable',
            'solution' => 'nullable',
            'name' => 'nullable',
        ]);

        // Convertir les commandes LaTeX personnalisées en HTML
        $statementHtml = $this->convertCustomLatexToHtml($request->statement);
        $solutionHtml = $request->solution ? $this->convertCustomLatexToHtml($request->solution) : null;
        $clueHtml = $request->clue ? $this->convertCustomLatexToHtml($request->clue) : null;

        $lastExercise = Exercise::latest()->first();
        $newId = $lastExercise ? $lastExercise->id + 1 : 1;

        $maxOrder = Exercise::where('subchapter_id', $request->subchapter_id)->max('order');
        // increment all the orders of the exercises
        $exercises = Exercise::where('order', '>=', $maxOrder + 1)->orderBy('order', 'desc')->get();
        // Increment the order of each exercise
        foreach ($exercises as $exercise) {
            $exercise->increment('order');
        }

        $exercise = new Exercise();
        $exercise->id = $newId;
        $exercise->clue = $clueHtml;
        $exercise->latex_clue = $request->clue;
        $exercise->name = $request->name;
        $exercise->subchapter_id = $request->subchapter_id;
        $exercise->statement = $statementHtml;
        $exercise->latex_statement = $request->statement;
        $exercise->solution = $solutionHtml;
        $exercise->latex_solution = $request->solution;
        $exercise->order = $maxOrder + 1;
        $exercise->save();

        return redirect()->route('subchapter.show', $request->subchapter_id);
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
            "/\\\\begin\{center\}/" => "<div class='latex-center'>",
            "/\\\\end\{center\}/" => " </div>",
            "/\\\\begin\{minipage\}/" => "<div class='latex-minipage'>",
            "/\\\\end\{minipage\}/" => "</div>",
            "/\\\\begin\{tabularx\}\{(.+?)\}/" => "<span class='latex latex-tabularx' style='width: $1%;'>",
            "/\\\\end\{tabularx\}/" => "</span>",
            "/\\\\begin\{boxed\}/" => "<span class='latex latex-boxed'>",
            "/\\\\end\{boxed\}/" => "</span>",
            // "/\\\\\\\/" => "<br>",
            "/\{([0-9.]+)\\\\linewidth\}/" => "<style='width: calc($1% - 2em);'> </style>",
            "/\{\\\\linewidth\}\{(.+?)\}/" => "<style='width:'$1';'> </style>",
            "/\\\\hline/" => "<hr>",
            "/\\\\renewcommand\\\\arraystretch\{0.9\}/" => "",
            // PA
            "/\\\\PA\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Première partie $1</span></div>",
            "/\\\\PA/" => "<div class='latex latex-center'><span class='textbf'>Première partie</span></div>",
            // PB
            "/\\\\PB\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie $1</span></div>",
            "/\\\\PB/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie</span></div>",
            // PC
            "/\\\\PC\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie $1</span></div>",
            "/\\\\PC/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie</span></div>",
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

    public function show(Exercise $exercise)
    {
        return view('exercise.show', compact('exercise'));
    }

    public function edit($id)
    {
        $exercise = Exercise::findOrFail($id);
        return view('exercise.edit', compact('exercise'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'subchapter_id' => 'required',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'solution' => 'nullable',
            'latex_solution' => 'nullable',
            'name' => 'nullable',
            'clue' => 'nullable',
            'latex_clue' => 'nullable',
        ]);

        $statementHtml = $this->convertCustomLatexToHtml($request->statement);
        $solutionHtml = $this->convertCustomLatexToHtml($request->solution);
        $clueHtml = $this->convertCustomLatexToHtml($request->clue);

        $exercise = Exercise::findOrFail($id);
        $exercise->update([
            'subchapter_id' => $request->subchapter_id,
            'latex_statement' => $request->statement,
            'latex_solution' => $request->solution,
            'latex_clue' => $request->clue,
            'statement' => $statementHtml,
            'solution' => $solutionHtml,
            'name' => $request->name,
            'clue' => $clueHtml,

        ]);

        return redirect()->route('subchapter.show', $request->subchapter_id);
    }

    public function destroy($id)
    {
        $exercise = Exercise::findOrFail($id);
        $exercise->delete();
    
        // Reorder all remaining exercises
        $classes = Classe::orderBy('id')->get();
        $order = 1;
    
        foreach ($classes as $class) {
            $chapters = $class->chapters()->orderBy('id')->get();
    
            foreach ($chapters as $chapter) {
                $subchapters = $chapter->subchapters()->orderBy('id')->get();
    
                foreach ($subchapters as $subchapter) {
                    $remainingExercises = $subchapter->exercises()->orderBy('id')->get();
    
                    foreach ($remainingExercises as $remainingExercise) {
                        $remainingExercise->order = $order++;
                        $remainingExercise->save();
                    }
                }
            }
        }

        return redirect()->route('subchapter.show', $exercise->subchapter_id);
    }
}
