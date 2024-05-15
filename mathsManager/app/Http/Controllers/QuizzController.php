<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizzQuestion;
use App\Models\QuizzAnswer;
use App\Models\Chapter;
use App\Models\Subchapter;

class QuizzController extends Controller
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

    // Méthode pour commencer un quizz sur un chapitre en particulier (question par question avec bouton suivant une fois qu'on a rep)
    public function startQuizz($chapter_id)
    {
        $questionsCount = QuizzQuestion::where('chapter_id', $chapter_id)->count();
        if ($questionsCount < 10) {
            return redirect()->route('home');
        }
        $questions = QuizzQuestion::where('chapter_id', $chapter_id)
            ->inRandomOrder()
            ->get()
            ->unique('subchapter_id')
            ->shuffle()
            ->take(10);

        session(['questions' => $questions]);
        session(['currentQuestion' => 0]);
        session(['score' => 0]);

        return redirect()->route('show_question');
    }

    // Méthode pour afficher une question du quizz
    public function showQuestion()
    {
        $questions = session('questions');
        $currentQuestion = session('currentQuestion');

        if ($currentQuestion >= count($questions)) {
            return redirect()->route('show_result');
        }

        $question = $questions[$currentQuestion];
        $answers = $question->answers->shuffle();

        return view('quizz.showQuestion', compact('question', 'answers'));
    }

    // Méthode pour vérifier la réponse donnée par l'utilisateur
    public function checkAnswer(Request $request)
    {
        $questions = session('questions');
        $currentQuestion = session('currentQuestion');
        $question = $questions[$currentQuestion];
        $answer = QuizzAnswer::find($request->answer_id);

        if ($answer->quizz_question_id == $question->id && $answer->is_correct) {
            session(['score' => session('score') + 1]);
        }

        return redirect()->route('show_answer', ['answer_id' => $request->answer_id]);
    }

    // Méthode pour afficher la réponse à la question
    public function showAnswer($answer_id)
    {
        $answer = QuizzAnswer::find($answer_id);
        $explanation = $answer->explanation;
        $answerContent = $answer->answer;

        session(['currentQuestion' => session('currentQuestion') + 1]);

        return view('quizz.showAnswer', compact('explanation', 'answerContent'));
    }

    // Méthode pour afficher le résultat du quizz
    public function showResult()
    {
        $score = session('score');
        $questions = session('questions');
        $totalQuestions = count($questions);

        return view('quizz.showResult', compact('score', 'totalQuestions'));
    }

    // méthode pour mettre fin à un quizz
    public function endQuizz()
    {
        session()->forget(['questions', 'currentQuestion', 'score']);
        // get chapter and then get classe for redirect to 
        $chapter = QuizzQuestion::find(session('questions')[0]->chapter_id)->chapter;
        $classe = $chapter->classe;
        return redirect()->route('classe.show', $classe->level);
    }

    // Méthode pour afficher toutes les questions
    public function index(Request $request)
    {
        $search = $request->get('search');
        $quizzQuestions = QuizzQuestion::with('answers', 'chapter', 'subchapter')->orderBy('created_at', 'desc');

        if ($search) {
            $quizzQuestions->where(function ($query) use ($search) {
                $query->where('name', 'like', '%' . $search . '%')
                    ->orWhere('id', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('chapter_id')) {
            $quizzQuestions->where('chapter_id', $request->chapter_id);
            $filterActivated = true;
            $chapterActivated = Chapter::findOrFail($request->chapter_id);
        } else {
            $filterActivated = false;
            $chapterActivated = null;
        }

        $quizzQuestions = $quizzQuestions->paginate(10);
        $chapters = Chapter::all();

        return view('quizz.index', compact('quizzQuestions', 'chapters', 'filterActivated', 'chapterActivated'));
    }

    // Méthode pour afficher une question et ses réponses
    public function show($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        if (!$question) {
            return redirect()->route('home');
        }

        $filter = $request->get('filter');
        if ($filter == 'true') {
            $quizzQuestions = QuizzQuestion::where('chapter_id', $question->chapter_id)->get();
        } else {
            $quizzQuestions = QuizzQuestion::all();
        }

        // Récupérer la question précédente et suivante pour la navigation
        $previousQuestion = $quizzQuestions->filter(function ($value, $key) use ($question) {
            return $value->id < $question->id;
        })->last();
        $nextQuestion = $quizzQuestions->filter(function ($value, $key) use ($question) {
            return $value->id > $question->id;
        })->first();

        $answers = $question->answers;
        return view('quizz.show', compact('question', 'answers', 'quizzQuestions', 'previousQuestion', 'nextQuestion', 'filter'));
    }

    // Méthode pour afficher le formulaire de création de question
    public function createQuestion()
    {
        $chapters = Chapter::all();
        $subchapters = Subchapter::all();
        return view('quizz.createQuestion', compact('chapters', 'subchapters'));
    }

    // Méthode pour stocker une nouvelle question
    public function storeQuestion(Request $request)
    {
        $request->validate([
            'question' => 'required',
            'chapter_id' => 'required',
            'subchapter_id' => 'nullable'
        ]);

        $question = new QuizzQuestion();
        $question->latex_question = $request->question;
        $question->question = $this->convertCustomLatexToHtml($request->question);
        $question->chapter_id = $request->chapter_id;
        $question->subchapter_id = $request->subchapter_id;
        $question->save();

        return redirect()->route('quizz.index');
    }

    // Méthode pour form d'édition de question
    public function editQuestion($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        $chapters = Chapter::all();
        $subchapters = Subchapter::all();
        $filter = $request->get('filter');

        return view('quizz.editQuestion', compact('question', 'chapters', 'subchapters', 'filter'));
    }

    // Méthode pour mettre à jour une question
    public function updateQuestion(Request $request, $id)
    {
        $request->validate([
            'question' => 'required',
            'chapter_id' => 'required',
            'subchapter_id' => 'nullable'
        ]);

        $question = QuizzQuestion::find($id);
        $question->latex_question = $request->question;
        $question->question = $this->convertCustomLatexToHtml($request->question);
        $question->chapter_id = $request->chapter_id;
        $question->subchapter_id = $request->subchapter_id;
        $question->save();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $id, 'filter' => $filter]);
    }

    // Méthode pour supprimer une question
    public function destroyQuestion($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        $question->delete();

        $filter = $request->get('filter');

        if ($filter == 'true') {
            return redirect()->route('quizz.index', ['chapter_id' => $question->chapter_id]);
        } else {
            return redirect()->route('quizz.index');
        }
    }

    // Méthode pour afficher le formulaire de création de réponse
    public function createAnswer($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        $filter = $request->get('filter');
        return view('quizz.createAnswer', compact('question', 'filter'));
    }

    // Méthode pour stocker une nouvelle réponse
    public function storeAnswer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required',
            'explanation' => 'nullable',
            'is_correct' => 'required'
        ]);

        $answer = new QuizzAnswer();
        $answer->latex_answer = $request->answer;
        $answer->latex_explanation = $request->explanation;
        $answer->explanation = $this->convertCustomLatexToHtml($request->explanation);
        $answer->answer = $this->convertCustomLatexToHtml($request->answer);
        $answer->is_correct = $request->is_correct;
        $answer->quizz_question_id = $id;
        $answer->save();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $id, 'filter' => $filter]);
    }

    // Méthode pour form d'édition de réponse
    public function editAnswer($id, Request $request)
    {
        $answer = QuizzAnswer::find($id);
        $filter = $request->get('filter');
        return view('quizz.editAnswer', compact('answer', 'filter'));
    }

    // Méthode pour mettre à jour une réponse
    public function updateAnswer(Request $request, $id)
    {
        $request->validate([
            'answer' => 'required',
            'explanation' => 'nullable',
            'is_correct' => 'required'
        ]);

        $answer = QuizzAnswer::find($id);
        $answer->latex_answer = $request->answer;
        $answer->latex_explanation = $request->explanation;
        $answer->explanation = $this->convertCustomLatexToHtml($request->explanation);
        $answer->answer = $this->convertCustomLatexToHtml($request->answer);
        $answer->is_correct = $request->is_correct;
        $answer->save();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $answer->quizz_question_id, 'filter' => $filter]);
    }

    // Méthode pour supprimer une réponse
    public function destroyAnswer($id, Request $request)
    {
        $answer = QuizzAnswer::find($id);
        $answer->delete();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $answer->quizz_question_id, 'filter' => $filter]);
    }
}
