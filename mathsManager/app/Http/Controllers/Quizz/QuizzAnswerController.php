<?php

namespace App\Http\Controllers\Quizz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QuizzAnswer;
use App\Models\QuizzQuestion;
use App\Models\QuizzDetail;
use App\Services\LatexToHtmlConverter;
use App\Http\Requests\Quizz\StoreAnswerRequest;
use App\Http\Requests\Quizz\UpdateAnswerRequest;

class QuizzAnswerController extends Controller
{
    // Méthode pour afficher le formulaire de création de réponse
    public function create($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        $filter = $request->get('filter');
        return view('quizz.createAnswer', compact('question', 'filter'));
    }

    // Méthode pour stocker une nouvelle réponse
    public function store(StoreAnswerRequest $request, $id)
    {
        $answer = new QuizzAnswer();
        $answer->latex_answer = $request->answer;
        $answer->latex_explanation = $request->explanation;
        $answer->explanation = LatexToHtmlConverter::convertForQuiz($request->explanation);
        $answer->answer = LatexToHtmlConverter::convertForQuiz($request->answer);
        $answer->is_correct = $request->is_correct;
        $answer->quizz_question_id = $id;
        $answer->save();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $id, 'filter' => $filter]);
    }

    // Méthode pour form d'édition de réponse
    public function edit($id, Request $request)
    {
        $answer = QuizzAnswer::find($id);
        $filter = $request->get('filter');
        return view('quizz.editAnswer', compact('answer', 'filter'));
    }

    // Méthode pour mettre à jour une réponse
    public function update(UpdateAnswerRequest $request, $id)
    {
        $answer = QuizzAnswer::find($id);
        $answer->latex_answer = $request->answer;
        $answer->latex_explanation = $request->explanation;
        $answer->explanation = LatexToHtmlConverter::convertForQuiz($request->explanation);
        $answer->answer = LatexToHtmlConverter::convertForQuiz($request->answer);
        $answer->is_correct = $request->is_correct;
        $answer->save();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $answer->quizz_question_id, 'filter' => $filter]);
    }

    // Méthode pour supprimer une réponse
    public function destroy($id, Request $request)
    {
        $answer = QuizzAnswer::find($id);
        $questionId = $answer->quizz_question_id;

        // Delete associated QuizzDetails before deleting the answer
        QuizzDetail::where('chosen_answer_id', $answer->id)->delete();

        $answer->delete();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $questionId, 'filter' => $filter]);
    }
}
