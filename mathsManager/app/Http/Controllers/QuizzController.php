<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\QuizzQuestion;
use App\Models\QuizzAnswer;
use App\Models\Quizze;
use App\Models\QuizzDetail;
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
        $questions = QuizzQuestion::where('chapter_id', $chapter_id)
            ->get()
            ->groupBy('subchapter_id')
            ->shuffle();

        $selectedQuestions = collect();

        foreach ($questions as $subchapterId => $subchapterQuestions) {
            $subchapterQuestions = $subchapterQuestions->shuffle();
            if (!$subchapterQuestions->isEmpty()) {
                $selectedQuestions->push($subchapterQuestions->pop());
                $questions[$subchapterId] = $subchapterQuestions;
            }
        }

        while ($selectedQuestions->count() < 10 && !$questions->isEmpty()) {
            foreach ($questions as $subchapterId => $subchapterQuestions) {
                if ($selectedQuestions->count() >= 10) {
                    break;
                }
                if (!$subchapterQuestions->isEmpty()) {
                    $selectedQuestions->push($subchapterQuestions->pop());
                    $questions[$subchapterId] = $subchapterQuestions;
                } else {
                    $questions->forget($subchapterId);
                }
            }
        }

        $selectedQuestions = $selectedQuestions->shuffle();

        session(['questions' => $selectedQuestions]);
        session(['currentQuestion' => 0]);
        session(['score' => 0]);

        // dd($selectedQuestions->pluck('subchapter_id'));

        return redirect()->route('show_question');
    }

    // Méthode pour afficher une question du quizz
    public function showQuestion()
    {
        $questions = session('questions');
        $currentQuestion = session('currentQuestion');
        $score = session('score');

        if ($currentQuestion >= count($questions)) {
            return redirect()->route('show_result');
        }

        $question = $questions[$currentQuestion];

        // Get one correct answer
        $correctAnswers = $question->answers->where('is_correct', true);
        if ($correctAnswers->isEmpty()) {
            // Handle the error, e.g., redirect to an error page or throw an exception
            return redirect()->route('classe.show', ['level' => $question->chapter->classe->level]);
        }
        $correctAnswer = $correctAnswers->random(1);

        // Get three incorrect answers
        $incorrectAnswers = $question->answers->where('is_correct', false);
        if ($incorrectAnswers->count() < 3) {
            // Handle the error, e.g., redirect to an error page or throw an exception
            return redirect()->route('classe.show', ['level' => $question->chapter->classe->level]);
        }
        $incorrectAnswers = $incorrectAnswers->random(min(3, $incorrectAnswers->count()));

        // Merge and shuffle the answers
        session()->forget('answers');
        $answers = $correctAnswer->merge($incorrectAnswers)->shuffle();
        session(['answers' => $answers]);

        return view('quizz.showQuestion', compact('question', 'answers', 'currentQuestion', 'questions', 'score', 'correctAnswer'));
    }

    protected function createOrUpdateQuiz($currentQuestion, $score, $question, $questions, $answer) 
    {
        // Check if it's the first question
        if ($currentQuestion == 0) {
            // Get the count of quizzes for the student
            $quizzesCount = Quizze::where('student_id', auth()->id())->count();

            // If the student already has 10 quizzes, delete the oldest one
            if ($quizzesCount >= 10) {
                $oldestQuiz = Quizze::where('student_id', auth()->id())
                    ->oldest()
                    ->first();

                // Delete the associated QuizzDetails
                $oldestQuiz->details()->delete();

                // Delete the Quizze
                $oldestQuiz->delete();
            }

            // Create a new quizz
            $quizz = Quizze::create([
                'student_id' => auth()->id(),
                'chapter_id' => $question->chapter_id,
                'score' => $score
            ]);

            // Create QuizzDetails for all questions
            foreach ($questions as $quizQuestion) {
                $quizzDetail = new QuizzDetail();
                $quizzDetail->quizz_id = $quizz->id;
                $quizzDetail->question_id = $quizQuestion->id;
                $quizzDetail->chosen_answer_id = $answer ?? null;
                $quizzDetail->save();
            }

            // Store the quiz ID in the session
            session(['quizz_id' => $quizz->id]);
        } else {
            // Update the existing quizz
            $quizz = Quizze::where('id', session('quizz_id'))->first();
            $quizz->score = $score;
            $quizz->save();

            // Update the QuizzDetail when the user answers a question
            $quizzDetail = QuizzDetail::where('quizz_id', $quizz->id)
                ->where('question_id', $question->id)
                ->first();
            $quizzDetail->chosen_answer_id = $answer;
            $quizzDetail->save();
        }

        return $quizz;
    }

    // Méthode pour vérifier la réponse donnée par l'utilisateur
    public function checkAnswer(Request $request)
    {
        $questions = session('questions');
        $currentQuestion = session('currentQuestion');
        $question = $questions[$currentQuestion];
        $answer = QuizzAnswer::findOrFail($request->answer);
        $correctAnswer = json_decode($request->get('correct_answer'))[0]->id;
        if ($answer->quizz_question_id == $question->id && $answer->is_correct) {
            session()->increment('score');
        }

        $quizz = $this->createOrUpdateQuiz($currentQuestion, session('score'), $question, $questions, $answer->id);

        return redirect()->route('show_answer', ['answer_id' => $request->answer, 'correct_answer' => $correctAnswer]);
    }

    public function showAnswer(int $answer_id, int $correctAnswer)
    {
        $answers = session('answers');
        $answer = QuizzAnswer::findOrFail($answer_id);
        $correctAnswer = QuizzAnswer::findOrFail($correctAnswer);
        $question = $answer->question;

        session()->increment('currentQuestion');

        return view('quizz.showAnswer', compact('answer', 'correctAnswer', 'question', 'answers'));
    }

    // Méthode pour afficher le résultat du quizz
    public function showResult()
    {
        $score = session('score');
        $questions = session('questions');
        $chapter = QuizzQuestion::find($questions[0]->id)->chapter;
        $totalQuestions = count($questions);
        $messagesUnder02 = [
            "T'étais malade pendant ce chapitre ?",
            "Outch, j'en ai mal au cerveau",
            "J'ai connu mieux, mais jamais pire ...",
            "J'en connais un(e) qui aura pas son bac",
            "On est le premier avril ?",
            "Les classes de 6ème ne sont pas encore disponible sur le site",
            "Le bac c'est dans moins d'un mois au cas ou"
        ];
        $messagesUnder35 = [
            "Aie aie aie...",
            "Tu as encore du boulot",
            "Il serait temps d'ouvrir un cahier",
            "Le bac c'est dans moins d'un mois au cas ou",
            "C'était facile alors imagine quand c'est dur"
        ];
        $messagesUnder67 = [
            "Bon aller, on avance !",
            "Pas mal, mais tu peux mieux faire",
            "Mouais...",
            "Pas excellent mais potable",
            "T'as eu de la chance alors ressaisi toi",
            "Ça va ça va"
        ];
        $messagesUnder89 = [
            "C'est bien mais pas parfait",
            "Oui on y est allez !",
            "On avance bien là",
            "Ah là, on est bien",
            "Bah voila c'était dur ?",
            "Ça y est, nous y est",
            "Ça y est les bonnes notes arrivent",
            "Bien joué !"
        ];

        $messages10 = [
            "Merci ChatGPT hein",
            "Excellent travail !",
            "Une grande carrière s'annonce",
            "Même moi j'aurais pas fait mieux",
            "T'as changé",
            "Toutes mes félicitations",
            "Refais en un pour voir ...",
        ];

        if ($score <= 2) {
            $message = $messagesUnder02[array_rand($messagesUnder02)];
        } elseif ($score <= 5) {
            $message = $messagesUnder35[array_rand($messagesUnder35)];
        } elseif ($score <= 7) {
            $message = $messagesUnder67[array_rand($messagesUnder67)];
        } elseif ($score <= 9) {
            $message = $messagesUnder89[array_rand($messagesUnder89)];
        } else {
            $message = $messages10[array_rand($messages10)];
        }



        return view('quizz.showResult', compact('score', 'totalQuestions', 'chapter', 'message'));
    }

    // méthode pour mettre fin à un quizz
    public function endQuizz()
    {
        $chapter = Chapter::find(session('questions')[0]->chapter_id);
        $classe = $chapter->classe;
        session()->forget(['questions', 'currentQuestion', 'score']);
        return redirect()->route('classe.show', $classe->level);
    }

    // Méthode pour afficher toutes les questions
    public function index(Request $request)
    {
        $search = $request->get('search');
        $quizzQuestions = QuizzQuestion::with('answers', 'chapter', 'subchapter');

        if ($search) {
            $quizzQuestions->where(function ($query) use ($search) {
                $query->where('question', 'like', '%' . $search . '%')
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

        if ($request->filled('sort_by_subchapter')) {
            $quizzQuestions->orderBy('subchapter_id', 'asc')->orderBy('created_at', 'desc');
            $sort_by_subchapter = true;
        } else {
            $quizzQuestions->orderBy('created_at', 'desc');
            $sort_by_subchapter = false;
        }

        $quizzQuestions = $quizzQuestions->paginate(10);

        $chapters = Chapter::all();

        return view('quizz.index', compact('quizzQuestions', 'chapters', 'filterActivated', 'chapterActivated', 'sort_by_subchapter'));
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
            'name' => 'nullable',
            'question' => 'required',
            'chapter_id' => 'required',
            'subchapter_id' => 'nullable'
        ]);

        $question = new QuizzQuestion();
        $question->name = $request->name;
        $question->latex_question = $request->question;
        $question->question = $this->convertCustomLatexToHtml($request->question);
        $question->chapter_id = $request->chapter_id;
        $question->subchapter_id = $request->subchapter_id;
        $question->save();

        return redirect()->route('quizz.show', $question->id);
    }

    // Méthode pour dupliquer une question
    public function duplicateQuestion(int $id)
    {
        $question = QuizzQuestion::findOrFail($id);
        $newQuestion = $question->replicate();
        $newQuestion->save();

        foreach ($question->answers as $answer) {
            $newAnswer = $answer->replicate();
            $newAnswer->quizz_question_id = $newQuestion->id;
            $newAnswer->save();
        }

        return redirect()->back()->with('success', 'Question dupliquée avec succès');
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
            'name' => 'nullable',
            'question' => 'required',
            'chapter_id' => 'required',
            'subchapter_id' => 'nullable'
        ]);

        $question = QuizzQuestion::find($id);
        $question->name = $request->name;
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
