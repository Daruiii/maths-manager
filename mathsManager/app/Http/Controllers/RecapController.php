<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recap;
use App\Models\RecapPart;
use App\Models\RecapPartBlock;
use App\Models\Chapter;

class RecapController extends Controller
{

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

    // Méthode pour show un récap
    public function show($id)
    {
        $recap = Recap::find($id);
        $recapParts = $recap->recapParts;
        $recapPartBlocks = [];
        foreach ($recapParts as $recapPart) {
            $recapPartBlocks[$recapPart->id] = $recapPart->recapPartBlocks;
        }
        return view('recap.show', compact('recap'));
    }

    // Méthode pour créer un récap
    public function create($id)
    {
        $chapter_id = $id;
        return view('recap.create',  compact('chapter_id'));
    }

    // Méthode pour stocker un récap
    public function store(Request $request)
    {
        // title et chapter
        $request->validate([
            'title' => 'nullable',
            'chapter_id' => 'required'
        ]);

        // Création du récap
        $recap = new Recap();
        $recap->title = $request->title ?? 'Récapitulatif';
        $recap->chapter_id = $request->chapter_id;
        $recap->save();

        return redirect()->route('recap.show', $recap->id);
    }

    // Méthode pour destroy un récap
    public function destroy($id)
    {
        $recap = Recap::find($id);
        $recap->delete();
        return redirect()->route('classe.show', $recap->chapter->classe->level);
    }

    // Méthode pour créer une partie de récap
    public function createPart($id)
    {
        $recap_id = $id;
        return view('recap.createPart', compact('recap_id'));
    }

    // Méthode pour stocker une partie de récap
    public function storePart(Request $request)
    {
        // title et description
        $request->validate([
            'title' => 'required',
            'description' => 'nullable',
            'recap_id' => 'required'
        ]);

        // Création de la partie de récap
        $recapPart = new RecapPart();
        $recapPart->title = $request->title;
        $recapPart->description = $request->description;
        $recapPart->recap_id = $request->recap_id;
        $recapPart->save();

        return redirect()->route('recap.show', $recapPart->recap_id);
    }

    // Méthode pour éditer une partie de récap
    public function editPart($id)
    {
        $recapPart = RecapPart::find($id);
        return view('recap.editPart', compact('recapPart'));
    }

    // Méthode pour mettre à jour une partie de récap
    public function updatePart(Request $request, $id)
    {
        // title et description
        $request->validate([
            'title' => 'required',
            'description' => 'nullable'
        ]);

        // Mise à jour de la partie de récap
        $recapPart = RecapPart::find($id);
        $recapPart->title = $request->title;
        $recapPart->description = $request->description;
        $recapPart->save();

        return redirect()->route('recap.show', $recapPart->recap_id);
    }

    // Méthode pour destroy une partie de récap
    public function destroyPart($id)
    {
        $recapPart = RecapPart::find($id);
        $recapPart->delete();
        return redirect()->route('recap.show', $recapPart->recap_id);
    }

    // Méthode pour créer un bloc de partie de récap
    public function createPartBlock($id)
    {
        $recapPart_id = $id;
        $chapter_id = RecapPart::find($recapPart_id)->recap->chapter_id;
        $subchapters = Chapter::find($chapter_id)->subchapters;
        return view('recap.createPartBlock', compact('recapPart_id', 'subchapters'));
    }

    // Méthode pour stocker un bloc de partie de récap
    public function storePartBlock(Request $request)
    {
        // title et theme
        $request->validate([
            'title' => 'required',
            'theme' => 'nullable',
            'content' => 'nullable',
            'example' => 'nullable',
            'recap_part_id' => 'required',
            'subchapter_id' => 'nullable'
        ]);

        // Création du bloc de partie de récap
        $recapPartBlock = new RecapPartBlock();
        $recapPartBlock->title = $request->title;
        $recapPartBlock->theme = $request->theme ?? 'grey';
        $recapPartBlock->latex_content = $request->content;
        $recapPartBlock->latex_example = $request->example;
        $recapPartBlock->content = $this->convertCustomLatexToHtml($request->content);
        $recapPartBlock->example = $this->convertCustomLatexToHtml($request->example);
        $recapPartBlock->recap_part_id = $request->recap_part_id;
        $recapPartBlock->subchapter_id = $request->subchapter_id;
        $recapPartBlock->save();

        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }

    // Méthode pour éditer un bloc de partie de récap
    public function editPartBlock($id)
    {
        $recapPartBlock = RecapPartBlock::find($id);
        $subchapters = Chapter::find($recapPartBlock->recapPart->recap->chapter_id)->subchapters;

        return view('recap.editPartBlock', compact('recapPartBlock', 'subchapters'));
    }

    // Méthode pour mettre à jour un bloc de partie de récap
    public function updatePartBlock(Request $request, $id)
    {
        // title et theme
        $request->validate([
            'title' => 'required',
            'theme' => 'nullable',
            'example' => 'nullable',
            'latex_example' => 'nullable',
            'content' => 'nullable',
            'latex_content' => 'nullable',
            'subchapter_id' => 'nullable'
        ]);

        // Mise à jour du bloc de partie de récap
        $recapPartBlock = RecapPartBlock::find($id);
        $recapPartBlock->title = $request->title;
        $recapPartBlock->theme = $request->theme ?? 'grey';
        $recapPartBlock->latex_example = $request->example;
        $recapPartBlock->example = $this->convertCustomLatexToHtml($request->example);
        $recapPartBlock->latex_content = $request->content;
        $recapPartBlock->content = $this->convertCustomLatexToHtml($request->content);
        $recapPartBlock->subchapter_id = $request->subchapter_id;
        $recapPartBlock->save();

        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }

    // Méthode pour destroy un bloc de partie de récap
    public function destroyPartBlock($id)
    {
        $recapPartBlock = RecapPartBlock::find($id);
        $recapPartBlock->delete();
        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }
}
