<?php

namespace App\Services;

use App\Models\QuizzQuestion;
use Illuminate\Support\Collection;

class QuizzSelectionService
{
    /**
     * Nombre de questions par quiz par défaut
     */
    private const QUESTIONS_PER_QUIZ = 10;

    /**
     * Sélectionne jusqu'à N questions pour un chapitre donné
     * Stratégie : 1 question par sous-chapitre d'abord (diversité), puis complète jusqu'au limit
     *
     * @param int $chapterId
     * @param int $limit Nombre de questions à sélectionner (10 par défaut)
     * @return Collection Collection de QuizzQuestion sélectionnées et mélangées
     */
    public function selectQuestionsForChapter(int $chapterId, int $limit = self::QUESTIONS_PER_QUIZ): Collection
    {
        // Récupérer toutes les questions du chapitre, groupées par sous-chapitre
        $questions = QuizzQuestion::where('chapter_id', $chapterId)
            ->get()
            ->groupBy('subchapter_id')
            ->shuffle();

        $selectedQuestions = collect();

        // Phase 1 : Sélectionner 1 question par sous-chapitre (garantit la diversité)
        foreach ($questions as $subchapterId => $subchapterQuestions) {
            $subchapterQuestions = $subchapterQuestions->shuffle();
            if (!$subchapterQuestions->isEmpty()) {
                $selectedQuestions->push($subchapterQuestions->pop());
                $questions[$subchapterId] = $subchapterQuestions;
            }
        }

        // Phase 2 : Compléter jusqu'au limit en round-robin
        while ($selectedQuestions->count() < $limit && !$questions->isEmpty()) {
            foreach ($questions as $subchapterId => $subchapterQuestions) {
                if ($selectedQuestions->count() >= $limit) {
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

        // Phase 3 : Mélanger la sélection finale pour randomiser l'ordre
        return $selectedQuestions->shuffle();
    }
}
