<?php

namespace App\Services;

use App\Models\Quizze;
use App\Models\QuizzDetail;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class QuizzManagementService
{
    /**
     * Quota maximum de quiz par étudiant
     */
    private const MAX_QUIZZES_PER_STUDENT = 10;

    /**
     * Applique le quota de quiz : supprime le plus ancien si limite atteinte
     * Utilise une transaction avec lock pour éviter les race conditions
     *
     * @param int $studentId
     * @param int $maxQuizzes
     * @return void
     */
    public function enforceQuizQuota(int $studentId, int $maxQuizzes = self::MAX_QUIZZES_PER_STUDENT): void
    {
        DB::transaction(function () use ($studentId, $maxQuizzes) {
            // Lock pour éviter race condition si 2 quiz démarrent simultanément
            $quizzesCount = Quizze::where('student_id', $studentId)
                ->lockForUpdate()
                ->count();

            if ($quizzesCount >= $maxQuizzes) {
                $oldestQuiz = Quizze::where('student_id', $studentId)
                    ->oldest()
                    ->first();

                if ($oldestQuiz) {
                    // Supprimer les détails associés
                    $oldestQuiz->details()->delete();

                    Log::info('Quiz quota enforced: oldest quiz deleted', [
                        'student_id' => $studentId,
                        'deleted_quiz_id' => $oldestQuiz->id,
                        'chapter_id' => $oldestQuiz->chapter_id,
                    ]);

                    // Supprimer le quiz
                    $oldestQuiz->delete();
                }
            }
        });
    }

    /**
     * Crée un nouveau quiz avec pré-création de tous les QuizzDetail
     *
     * @param int $studentId
     * @param int $chapterId
     * @param Collection $questions Collection de QuizzQuestion
     * @param int $score Score initial (0 par défaut)
     * @return Quizze
     */
    public function createQuiz(
        int $studentId,
        int $chapterId,
        Collection $questions,
        int $score = 0
    ): Quizze {
        // Appliquer le quota avant de créer
        $this->enforceQuizQuota($studentId);

        // Créer le quiz
        $quizz = Quizze::create([
            'student_id' => $studentId,
            'chapter_id' => $chapterId,
            'score' => $score
        ]);

        // Pré-créer tous les QuizzDetail (stratégie actuelle)
        foreach ($questions as $question) {
            QuizzDetail::create([
                'quizz_id' => $quizz->id,
                'question_id' => $question->id,
                'chosen_answer_id' => null, // Sera rempli au fur et à mesure
            ]);
        }

        Log::info('Quiz created', [
            'quiz_id' => $quizz->id,
            'student_id' => $studentId,
            'chapter_id' => $chapterId,
            'questions_count' => $questions->count(),
        ]);

        return $quizz;
    }

    /**
     * Met à jour la progression du quiz (score + réponse choisie)
     *
     * @param int $quizzId
     * @param int $questionId
     * @param int $answerId
     * @param int $score
     * @return void
     */
    public function updateQuizProgress(
        int $quizzId,
        int $questionId,
        int $answerId,
        int $score
    ): void {
        // Mettre à jour le score du quiz
        $quizz = Quizze::findOrFail($quizzId);
        $quizz->score = $score;
        $quizz->save();

        // Mettre à jour le QuizzDetail avec la réponse choisie
        $quizzDetail = QuizzDetail::where('quizz_id', $quizzId)
            ->where('question_id', $questionId)
            ->firstOrFail();

        $quizzDetail->chosen_answer_id = $answerId;
        $quizzDetail->save();
    }
}
