<?php


namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\Subchapter;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Filesystem\InvalidArgumentException;
use Illuminate\Support\Facades\File;

class ExerciseController extends Controller
{
    public function index()
    {
        $search = request()->get('search');
        if ($search) {
            $exercises = Exercise::where('name', 'like', '%' . $search . '%')
                ->orWhere('id', 'like', '%' . $search . '%')
                ->get();
        } else {
            $exercises = Exercise::all();
        }
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
            "/\\\\PA/" => "<div class='latex latex-center'><span class='textbf'>Première partie</span></div>",
            "/\\\\PA\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Première partie $1</span></div>",
            // PB
            "/\\\\PB/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie</span></div>",
            "/\\\\PB\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Deuxième partie $1</span></div>",
            // PC
            "/\\\\PC/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie</span></div>",
            "/\\\\PC\{(.*?)\}/" => "<div class='latex latex-center'><span class='textbf'>Troisième partie $1</span></div>",
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
        $exercises = Exercise::all();
        foreach ($exercises as $index => $exercise) {
            $exercise->id = $index + 1;
            $exercise->save();
        }
        return redirect()->route('subchapter.show', $exercise->subchapter_id);
    }

    // public function store(Request $request)
    // {
    //     $request->validate([
    //         'subchapter_id' => 'required',
    //         'statement' => 'required',
    //         'solution' => 'nullable', // Assurez-vous que ceci correspond à votre demande réelle
    //     ]);

    //     $exercise = Exercise::create($request->all());

    //     // Génération du contenu LaTeX pour l'exercice et la correction
    //     $exerciseContent = $request->statement;
    //     $solutionContent = $request->solution ?? ''; // Utilisez une chaîne vide si aucune solution n'est fournie

    //     // Dossier de sortie racine pour LaTeX
    //     $rootOutputDir = storage_path('app/public/latex_output/exercise_' . $exercise->id);
    //     if (!is_dir($rootOutputDir)) {
    //         mkdir($rootOutputDir, 0777, true);
    //     }

    //     // Dossier spécifique pour l'exercice
    //     $exerciseOutputDir = $rootOutputDir . '/exercise';
    //     if (!is_dir($exerciseOutputDir)) {
    //         mkdir($exerciseOutputDir, 0777, true);
    //     }

    //     // Dossier spécifique pour la correction
    //     $solutionOutputDir = $rootOutputDir . '/correction';
    //     if (!is_dir($solutionOutputDir)) {
    //         mkdir($solutionOutputDir, 0777, true);
    //     }

    //     // Traiter l'exercice
    //     $exerciseTexPath = $exerciseOutputDir . "/exercise_{$exercise->id}.tex";
    //     Storage::disk('local')->put("public/latex_output/exercise_{$exercise->id}/exercise/exercise_{$exercise->id}.tex", $exerciseContent);
    //     $this->generatePngFromLatex($exerciseTexPath, $exerciseOutputDir);

    //     // Traiter la correction, si disponible
    //     if (!empty($solutionContent)) {
    //         $solutionTexPath = $solutionOutputDir . "/solution_{$exercise->id}.tex";
    //         Storage::disk('local')->put("public/latex_output/exercise_{$exercise->id}/correction/solution_{$exercise->id}.tex", $solutionContent);
    //         $this->generatePngFromLatex($solutionTexPath, $solutionOutputDir);
    //     }

    //     return redirect()->route('subchapter.show', $request->subchapter_id);
    // }
    // protected function generatePngFromLatex($texPath, $outputDir)
    // {
    //     // Compilation LaTeX en PDF
    //     $latexCommand = "pdflatex -interaction=nonstopmode -output-directory={$outputDir} {$texPath}";
    //     shell_exec($latexCommand);

    //     // Convertir le PDF en PNG, en gérant plusieurs pages
    //     $pdfPath = str_replace('.tex', '.pdf', $texPath);
    //     $pngPath = str_replace('.tex', '.png', $texPath); // Cela produira 'nom_exercice.png'
    //     $convertCommand = "convert -trim -density 200 {$pdfPath} -quality 90 {$pngPath}";
    //     shell_exec($convertCommand);
    // }

    // public function update(Request $request, $id)
    // {
    //     $request->validate([
    //         'subchapter_id' => 'required',
    //         'statement' => 'required',
    //     ]);

    //     $exercise = Exercise::findOrFail($id);
    //     $exercise->update($request->all());

    //     // Chemin de base pour les sorties LaTeX
    //     $rootOutputDir = storage_path('app/public/latex_output');
    //     $exerciseOutputDir = "{$rootOutputDir}/exercise_{$exercise->id}";

    //     // Supprimer le dossier existant et son contenu
    //     if (is_dir($exerciseOutputDir)) {
    //         $this->deleteDirectory($exerciseOutputDir);
    //     }

    //     // Créer à nouveau le dossier de sortie spécifique pour l'exercice
    //     mkdir($exerciseOutputDir, 0777, true);
    //     mkdir("{$exerciseOutputDir}/exercise", 0777, true);
    //     mkdir("{$exerciseOutputDir}/correction", 0777, true);

    //     // Traitement pour l'énoncé de l'exercice
    //     $this->processLatexContent($request->statement, "{$exerciseOutputDir}/exercise", "exercise_{$exercise->id}");

    //     // Traitement pour la correction de l'exercice, si fournie
    //     if (!empty($request->solution)) {
    //         $this->processLatexContent($request->solution, "{$exerciseOutputDir}/correction", "correction_{$exercise->id}");
    //     }

    //     return redirect()->route('subchapter.show', $request->subchapter_id);
    // }

    // private function processLatexContent($latexContent, $outputDir, $fileNamePrefix)
    // {
    //     // Génération des chemins de fichier
    //     $texFilePath = "{$outputDir}/{$fileNamePrefix}.tex";
    //     $pdfFilePath = "{$outputDir}/{$fileNamePrefix}.pdf";
    //     $pngFilePath = "{$outputDir}/{$fileNamePrefix}.png";

    //     // Enregistrement du contenu LaTeX
    //     File::put($texFilePath, $latexContent);

    //     // Compilation LaTeX en PDF
    //     $latexCommand = "pdflatex -interaction=nonstopmode -output-directory=$outputDir $texFilePath";
    //     shell_exec($latexCommand);

    //     // Conversion du PDF en PNG
    //     $convertCommand = "convert -trim -density 200 $pdfFilePath -quality 90 $pngFilePath";
    //     shell_exec($convertCommand);
    // }

    // private function deleteDirectory($dirPath)
    // {
    //     if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
    //         $dirPath .= '/';
    //     }
    //     $files = glob($dirPath . '*', GLOB_MARK);
    //     foreach ($files as $file) {
    //         if (is_dir($file)) {
    //             self::deleteDirectory($file);
    //         } else {
    //             unlink($file);
    //         }
    //     }
    //     rmdir($dirPath);
    // }


    // public function destroy($id)
    // {
    //     $exercise = Exercise::findOrFail($id);

    //     // Chemin de base où les fichiers sont stockés
    //     $exerciseFilesPath = 'public/latex_output/exercise_' . $exercise->id;

    //     // Vérifie si le dossier existe et le supprime
    //     if (Storage::disk('local')->exists($exerciseFilesPath)) {
    //         Storage::disk('local')->deleteDirectory($exerciseFilesPath);
    //     }

    //     // Supprime l'entrée de l'exercice de la base de données
    //     $exercise->delete();

    //     // Redirige l'utilisateur avec un message de confirmation
    //     return redirect()->route('subchapter.show', $exercise->subchapter_id)->with('success', 'Exercise deleted successfully');
    // }
}
