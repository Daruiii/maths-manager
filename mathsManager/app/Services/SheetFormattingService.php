<?php

namespace App\Services;

use App\Models\ExercisesSheet;

class SheetFormattingService
{
    /**
     * Formate les exercices d'une fiche en les groupant par sous-chapitre
     * avec indices globaux et tri par ordre des sous-chapitres
     *
     * @param ExercisesSheet $exercisesSheet
     * @return \Illuminate\Support\Collection
     */
    public function formatExercisesBySubchapter(ExercisesSheet $exercisesSheet)
    {
        $globalIndex = 0;
        $subChapterIndex = 0;

        return $exercisesSheet->exercises
            ->groupBy('subchapter_id')
            ->map(function ($group) use (&$globalIndex, &$subChapterIndex) {
                $group->each(function ($item) use (&$globalIndex) {
                    $item->globalIndex = ++$globalIndex;
                });
                $subChapterIndex++;
                return [
                    'subChapterIndex' => $subChapterIndex,
                    'subChapterTitle' => $group->first()->subchapter->title,
                    'exercises' => $group,
                    'subChapterOrder' => $group->first()->subchapter->order,
                ];
            })
            ->sortBy('subChapterOrder');
    }
}
