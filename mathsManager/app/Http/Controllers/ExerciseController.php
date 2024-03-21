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
        $exercises = Exercise::all();
        return view('exercise.index', compact('exercises'));
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
        'solution' => 'nullable', // Assurez-vous que ceci correspond à votre demande réelle
    ]);

    $exercise = Exercise::create($request->all());

    // Génération du contenu LaTeX pour l'exercice et la correction
    $exerciseContent = $request->statement;
    $solutionContent = $request->solution ?? ''; // Utilisez une chaîne vide si aucune solution n'est fournie

    // Dossier de sortie racine pour LaTeX
    $rootOutputDir = storage_path('app/public/latex_output/exercise_' . $exercise->id);
    if (!is_dir($rootOutputDir)) {
        mkdir($rootOutputDir, 0777, true);
    }

    // Dossier spécifique pour l'exercice
    $exerciseOutputDir = $rootOutputDir . '/exercise';
    if (!is_dir($exerciseOutputDir)) {
        mkdir($exerciseOutputDir, 0777, true);
    }

    // Dossier spécifique pour la correction
    $solutionOutputDir = $rootOutputDir . '/correction';
    if (!is_dir($solutionOutputDir)) {
        mkdir($solutionOutputDir, 0777, true);
    }

    // Traiter l'exercice
    $exerciseTexPath = $exerciseOutputDir . "/exercise_{$exercise->id}.tex";
    Storage::disk('local')->put("public/latex_output/exercise_{$exercise->id}/exercise/exercise_{$exercise->id}.tex", $exerciseContent);
    $this->generatePngFromLatex($exerciseTexPath, $exerciseOutputDir);

    // Traiter la correction, si disponible
    if (!empty($solutionContent)) {
        $solutionTexPath = $solutionOutputDir . "/solution_{$exercise->id}.tex";
        Storage::disk('local')->put("public/latex_output/exercise_{$exercise->id}/correction/solution_{$exercise->id}.tex", $solutionContent);
        $this->generatePngFromLatex($solutionTexPath, $solutionOutputDir);
    }

    return redirect()->route('subchapter.show', $request->subchapter_id);
}

protected function generatePngFromLatex($texPath, $outputDir)
{
    // Compilation LaTeX en PDF
    $latexCommand = "pdflatex -interaction=nonstopmode -output-directory={$outputDir} {$texPath}";
    shell_exec($latexCommand);

    // Convertir le PDF en PNG, en gérant plusieurs pages
    $pdfPath = str_replace('.tex', '.pdf', $texPath);
    $pngPath = str_replace('.tex', '.png', $texPath); // Cela produira 'nom_exercice.png'
    $convertCommand = "convert -trim -density 200 {$pdfPath} -quality 90 {$pngPath}";
    shell_exec($convertCommand);
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
        ]);
    
        $exercise = Exercise::findOrFail($id);
        $exercise->update($request->all());
    
        // Chemin de base pour les sorties LaTeX
        $rootOutputDir = storage_path('app/public/latex_output');
        $exerciseOutputDir = "{$rootOutputDir}/exercise_{$exercise->id}";
    
        // Supprimer le dossier existant et son contenu
        if (is_dir($exerciseOutputDir)) {
            $this->deleteDirectory($exerciseOutputDir);
        }
    
        // Créer à nouveau le dossier de sortie spécifique pour l'exercice
        mkdir($exerciseOutputDir, 0777, true);
        mkdir("{$exerciseOutputDir}/exercise", 0777, true);
        mkdir("{$exerciseOutputDir}/correction", 0777, true);
    
        // Traitement pour l'énoncé de l'exercice
        $this->processLatexContent($request->statement, "{$exerciseOutputDir}/exercise", "exercise_{$exercise->id}");
    
        // Traitement pour la correction de l'exercice, si fournie
        if (!empty($request->solution)) {
            $this->processLatexContent($request->solution, "{$exerciseOutputDir}/correction", "correction_{$exercise->id}");
        }
    
        return redirect()->route('subchapter.show', $request->subchapter_id);
    }
    
    private function processLatexContent($latexContent, $outputDir, $fileNamePrefix)
    {
        // Génération des chemins de fichier
        $texFilePath = "{$outputDir}/{$fileNamePrefix}.tex";
        $pdfFilePath = "{$outputDir}/{$fileNamePrefix}.pdf";
        $pngFilePath = "{$outputDir}/{$fileNamePrefix}.png";
    
        // Enregistrement du contenu LaTeX
        File::put($texFilePath, $latexContent);
    
        // Compilation LaTeX en PDF
        $latexCommand = "pdflatex -interaction=nonstopmode -output-directory=$outputDir $texFilePath";
        shell_exec($latexCommand);
    
        // Conversion du PDF en PNG
        $convertCommand = "convert -trim -density 150 $pdfFilePath -quality 90 $pngFilePath";
        shell_exec($convertCommand);
    }
    
    private function deleteDirectory($dirPath)
    {
        if (substr($dirPath, strlen($dirPath) - 1, 1) != '/') {
            $dirPath .= '/';
        }
        $files = glob($dirPath . '*', GLOB_MARK);
        foreach ($files as $file) {
            if (is_dir($file)) {
                self::deleteDirectory($file);
            } else {
                unlink($file);
            }
        }
        rmdir($dirPath);
    }
    

    public function destroy($id)
    {
        $exercise = Exercise::findOrFail($id);
    
        // Chemin de base où les fichiers sont stockés
        $exerciseFilesPath = 'public/latex_output/exercise_' . $exercise->id;
    
        // Vérifie si le dossier existe et le supprime
        if (Storage::disk('local')->exists($exerciseFilesPath)) {
            Storage::disk('local')->deleteDirectory($exerciseFilesPath);
        }
    
        // Supprime l'entrée de l'exercice de la base de données
        $exercise->delete();
    
        // Redirige l'utilisateur avec un message de confirmation
        return redirect()->route('subchapter.show', $exercise->subchapter_id)->with('success', 'Exercise deleted successfully');
}

}