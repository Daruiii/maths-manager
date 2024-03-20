<?php


namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;
use App\Models\Subchapter;
use Illuminate\Support\Facades\Storage;

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
        ]);
    
        $exercise = Exercise::create($request->all());
    
        // Génération du contenu LaTeX
        $latexContent = $request->statement;
        $fileName = "exercise_{$exercise->id}.tex";
        
        // Dossier de sortie racine pour LaTeX
        $rootOutputDir = storage_path('app/public/latex_output');
        if (!is_dir($rootOutputDir)) {
            mkdir($rootOutputDir, 0777, true);
        }
        
        // Dossier de sortie spécifique pour chaque exercice
        $exerciseOutputDir = "{$rootOutputDir}/exercise_{$exercise->id}";
        if (!is_dir($exerciseOutputDir)) {
            mkdir($exerciseOutputDir, 0777, true);
        }
        
        // Chemins de fichier
        $texFilePath = "{$exerciseOutputDir}/{$fileName}";
        Storage::disk('local')->put("public/latex_output/exercise_{$exercise->id}/{$fileName}", $latexContent);
        $absoluteTexFilePath = storage_path("app/public/latex_output/exercise_{$exercise->id}/{$fileName}");
        
        // Compilation LaTeX en PDF
        $latexCommand = "pdflatex -interaction=nonstopmode -output-directory=$exerciseOutputDir $absoluteTexFilePath";
        shell_exec($latexCommand);
    
        // Chemin du fichier PDF
        $pdfFileName = "exercise_{$exercise->id}.pdf";
        $pdfFilePath = "{$exerciseOutputDir}/{$pdfFileName}";
        
        // Conversion du PDF en PNG
        $pngFileName = "exercise_{$exercise->id}.png";
        $pngFilePath = "{$exerciseOutputDir}/{$pngFileName}";
        $convertCommand = "convert -trim -density 200 $pdfFilePath -quality 90 $pngFilePath";
        shell_exec($convertCommand);
        
        // Vérifiez si PNG a été créé
        if (!file_exists($pngFilePath)) {
            return redirect()->route('subchapter.show', $request->subchapter_id)->with('error', "Erreur lors de la génération du PNG.");
        }
    
        return redirect()->route('subchapter.show', $request->subchapter_id);
    }
    

    

    

    public function show(Exercise $exercise)
    {
        return view('exercise.show', compact('exercise'));
    }

    public function edit(Exercise $exercise)
    {
        return view('exercise.edit', compact('exercise'));
    }

    public function update(Request $request, Exercise $exercise)
    {
        $exercise->update($request->all());
        return redirect()->route('exercises.index');
    }

    public function destroy(Exercise $exercise)
    {
        $exercise->delete();
        return redirect()->route('exercises.index');
    }
}
