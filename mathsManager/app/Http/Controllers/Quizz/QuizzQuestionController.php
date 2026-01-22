<?php

namespace App\Http\Controllers\Quizz;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\QuizzQuestion;
use App\Models\QuizzAnswer;
use App\Models\QuizzDetail;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Services\LatexToHtmlConverter;
use App\Http\Requests\Quizz\StoreQuestionRequest;
use App\Http\Requests\Quizz\UpdateQuestionRequest;

class QuizzQuestionController extends Controller
{
    protected \App\Services\QueryFiltersService $queryFiltersService;

    public function __construct(\App\Services\QueryFiltersService $queryFiltersService)
    {
        $this->queryFiltersService = $queryFiltersService;
    }

    // Méthode pour afficher toutes les questions
    public function index(Request $request)
    {
        $search = $request->get('search');
        $quizzQuestions = QuizzQuestion::with('answers', 'chapter', 'subchapter');

        // Appliquer la recherche via le service
        $quizzQuestions = $this->queryFiltersService->applySearch($quizzQuestions, $search, ['question', 'id']);

        // Appliquer le filtre de chapitre via le service
        $filterFields = ['chapter_id' => 'chapter_id'];
        $quizzQuestions = $this->queryFiltersService->applyFilters($quizzQuestions, $request, $filterFields);

        // Récupérer les filtres actifs
        $activeFilters = $this->queryFiltersService->getActiveFilters($request, $filterFields);
        $filterActivated = isset($activeFilters['chapter_id']);
        $chapterActivated = $filterActivated ? Chapter::findOrFail($activeFilters['chapter_id']) : null;

        // Gestion du tri
        if ($request->filled('sort_by_subchapter')) {
            $quizzQuestions = $quizzQuestions->orderBy('subchapter_id', 'asc')->orderBy('created_at', 'desc');
            $sort_by_subchapter = true;
        } else {
            $quizzQuestions = $quizzQuestions->orderBy('created_at', 'desc');
            $sort_by_subchapter = false;
        }

        $quizzQuestions = $quizzQuestions->paginate(10)->withQueryString();
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
    public function create()
    {
        $chapters = Chapter::all();
        $subchapters = Subchapter::all();
        return view('quizz.createQuestion', compact('chapters', 'subchapters'));
    }

    // Méthode pour stocker une nouvelle question
    public function store(StoreQuestionRequest $request)
    {
        $question = new QuizzQuestion();
        $question->name = $request->name;
        $question->latex_question = $request->question;
        $question->question = LatexToHtmlConverter::convertForQuiz($request->question);
        $question->chapter_id = $request->chapter_id;
        $question->subchapter_id = $request->subchapter_id;
        $question->save();

        return redirect()->route('quizz.show', $question->id);
    }

    // Méthode pour dupliquer une question
    public function duplicate(int $id)
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
    public function edit($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        $chapters = Chapter::all();
        $subchapters = Subchapter::all();
        $filter = $request->get('filter');

        return view('quizz.editQuestion', compact('question', 'chapters', 'subchapters', 'filter'));
    }

    // Méthode pour mettre à jour une question
    public function update(UpdateQuestionRequest $request, $id)
    {
        $question = QuizzQuestion::find($id);
        $question->name = $request->name;
        $question->latex_question = $request->question;
        $question->question = LatexToHtmlConverter::convertForQuiz($request->question);
        $question->chapter_id = $request->chapter_id;
        $question->subchapter_id = $request->subchapter_id;
        $question->save();

        $filter = $request->get('filter');

        return redirect()->route('quizz.show', ['id' => $id, 'filter' => $filter]);
    }

    // Méthode pour supprimer une question
    public function destroy($id, Request $request)
    {
        $question = QuizzQuestion::find($id);
        // delete before the answers bcs linked to the question
        // Delete quizz details that reference the answers
        foreach ($question->answers as $answer) {
            QuizzDetail::where('chosen_answer_id', $answer->id)->delete();
        }
        QuizzDetail::where('question_id', $question->id)->delete();
        $question->answers()->delete();
        $question->delete();

        $filter = $request->get('filter');

        if ($filter == 'true') {
            return redirect()->route('quizz.index', ['chapter_id' => $question->chapter_id]);
        } else {
            return redirect()->route('quizz.index');
        }
    }
}
