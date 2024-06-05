<?php


namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\Subchapter;
use App\Models\Chapter;
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
        try {
            $search = request()->get('search');
    
            $exercises = Exercise::orderBy('created_at', 'desc');
            if ($search) {
                $exercises = $exercises->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            }
            $exercises = $exercises->paginate(10)->withQueryString();
    
            $subchapters = Subchapter::all();
            return view('exercise.index', compact('exercises', 'subchapters'));
        } catch (\Exception $e) {
            Log::error("Failed to load exercises: " . $e->getMessage());
            return back()->withErrors('Failed to load exercises.');
        }
    }

    public function create($id)
    {
        try {
            $subchapter_id = $id;
            $subchapters = Subchapter::all();
            return view('exercise.create', compact('subchapter_id', 'subchapters'));
        } catch (\Exception $e) {
            Log::error("Failed to load create exercise view: " . $e->getMessage());
            return back()->withErrors('Failed to load create exercise view.');
        }
    }

    private function getMaxOrder($subchapter) {
        $maxOrder = null;

        // Get the previous subchapters in the same chapter
        $previousSubchapters = Subchapter::where('chapter_id', $subchapter->chapter_id)
            ->where('order', '<', $subchapter->order)
            ->orderBy('order', 'desc')
            ->get();
        
        // Loop through the previous subchapters to find the max order
        foreach ($previousSubchapters as $previousSubchapter) {
            $maxOrder = Exercise::where('subchapter_id', $previousSubchapter->id)->max('order');
            if ($maxOrder !== null) {
                break;
            }
        }
        
        // If there are no exercises in the previous subchapters, get the last exercise of the previous chapters
        if ($maxOrder === null) {
            $previousChapters = Chapter::where('order', '<', $subchapter->chapter->order)
                ->orderBy('order', 'desc')
                ->get();
        
            // Loop through the previous chapters to find the max order
            foreach ($previousChapters as $previousChapter) {
                $maxOrder = Exercise::whereHas('subchapter', function ($query) use ($previousChapter) {
                    $query->where('chapter_id', $previousChapter->id);
                })->max('order');
        
                if ($maxOrder !== null) {
                    break;
                }
            }
        }
        
        // If there are no exercises in the previous chapters, start from 1
        if ($maxOrder === null) {
            $maxOrder = 0;
        }
        
        // Get the max order of the exercises in the current subchapter
        $currentSubchapterMaxOrder = Exercise::where('subchapter_id', $subchapter->id)->max('order');
    
        // If the max order of the exercises in the current subchapter is greater than the max order of the exercises in the previous subchapter or chapter, use it
        if ($currentSubchapterMaxOrder > $maxOrder) {
            $maxOrder = $currentSubchapterMaxOrder;
        }
    
        return $maxOrder;
    }

    public function store(Request $request)
    {
        try {
        $request->validate([
            'subchapter_id' => 'required',
            'statement' => 'required',
            'clue' => 'nullable',
            'solution' => 'nullable',
            'name' => 'nullable',
            'difficulty' => 'required|numeric|min:1|max:5',
        ]);

        // Convertir les commandes LaTeX personnalisées en HTML
        $statementHtml = $this->convertCustomLatexToHtml($request->statement);
        $solutionHtml = $request->solution ? $this->convertCustomLatexToHtml($request->solution) : null;
        $clueHtml = $request->clue ? $this->convertCustomLatexToHtml($request->clue) : null;

        $lastExercise = Exercise::latest()->first();
        $newId = $lastExercise ? $lastExercise->id + 1 : 1;

        $maxOrder = $this->getMaxOrder(Subchapter::find($request->subchapter_id));
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
        $exercise->difficulty = $request->difficulty;
        $exercise->save();

        return redirect()->route('subchapter.show', $request->subchapter_id);
        } catch (\Exception $e) {
            Log::error("Failed to store exercise: " . $e->getMessage());
            return back()->withErrors('Failed to store exercise.');
        }
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
        try {
        $exercise = Exercise::findOrFail($id);
        return view('exercise.edit', compact('exercise'));
        } catch (\Exception $e) {
            Log::error("Failed to load edit exercise view: " . $e->getMessage());
            return back()->withErrors('Failed to load edit exercise view.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
        $request->validate([
            'subchapter_id' => 'required',
            'statement' => 'required',
            'latex_statement' => 'nullable',
            'solution' => 'nullable',
            'latex_solution' => 'nullable',
            'name' => 'nullable',
            'clue' => 'nullable',
            'latex_clue' => 'nullable',
            'difficulty' => 'required|numeric|min:1|max:5'
        ]);

        $statementHtml = $this->convertCustomLatexToHtml($request->statement);
        $solutionHtml = $this->convertCustomLatexToHtml($request->solution);
        $clueHtml = $this->convertCustomLatexToHtml($request->clue);

        $exercise = Exercise::findOrFail($id);
        // dd the request all for see if difficulty is here
        $exercise->update([
            'subchapter_id' => $request->subchapter_id,
            'difficulty' => $request->difficulty,
            'latex_statement' => $request->statement,
            'latex_solution' => $request->solution,
            'latex_clue' => $request->clue,
            'statement' => $statementHtml,
            'solution' => $solutionHtml,
            'name' => $request->name,
            'clue' => $clueHtml,
        ]);

        return redirect()->route('subchapter.show', $request->subchapter_id);
        } catch (\Exception $e) {
            Log::error("Failed to update exercise: " . $e->getMessage());
            return back()->withErrors('Failed to update exercise.');
        }
    }

    public function destroy($id)
    {
        try {
        $exercise = Exercise::findOrFail($id);
        $deletedOrder = $exercise->order;
        $subchapterId = $exercise->subchapter_id;
        // Delete the exercise
        $exercise->delete();
    
        return redirect()->route('subchapter.show', $subchapterId);
        } catch (\Exception $e) {
            Log::error("Failed to destroy exercise: " . $e->getMessage());
            return back()->withErrors('Failed to destroy exercise.');
        }
    }
}
