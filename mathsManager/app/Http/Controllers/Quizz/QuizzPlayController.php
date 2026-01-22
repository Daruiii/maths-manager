<?php

namespace App\Http\Controllers\Quizz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QuizzQuestion;
use App\Models\QuizzAnswer;
use App\Models\Chapter;

class QuizzPlayController extends Controller
{
    protected \App\Services\QuizzManagementService $quizzManagementService;
    protected \App\Services\QuizzSelectionService $quizzSelectionService;

    public function __construct(
        \App\Services\QuizzManagementService $quizzManagementService,
        \App\Services\QuizzSelectionService $quizzSelectionService
    ) {
        $this->quizzManagementService = $quizzManagementService;
        $this->quizzSelectionService = $quizzSelectionService;
    }

    // Méthode pour commencer un quizz sur un chapitre en particulier (question par question avec bouton suivant une fois qu'on a rep)
    public function start($chapter_id)
    {
        // Sélectionner les questions via le service (algorithme de sélection intelligent)
        $selectedQuestions = $this->quizzSelectionService->selectQuestionsForChapter($chapter_id);

        session(['questions' => $selectedQuestions]);
        session(['currentQuestion' => 0]);
        session(['score' => 0]);

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
            // Créer le quiz via le service (gère le quota automatiquement)
            $quizz = $this->quizzManagementService->createQuiz(
                auth()->id(),
                $question->chapter_id,
                $questions,
                $score
            );

            // Store the quiz ID in the session
            session(['quizz_id' => $quizz->id]);
        } else {
            // Mettre à jour la progression via le service
            $this->quizzManagementService->updateQuizProgress(
                session('quizz_id'),
                $question->id,
                $answer,
                $score
            );
        }
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
    public function end()
    {
        $chapter = Chapter::find(session('questions')[0]->chapter_id);
        $classe = $chapter->classe;
        session()->forget(['questions', 'currentQuestion', 'score']);
        return redirect()->route('classe.show', $classe->level);
    }
}
