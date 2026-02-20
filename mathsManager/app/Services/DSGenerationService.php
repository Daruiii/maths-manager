<?php

namespace App\Services;

use App\Models\DS;
use App\Models\MultipleChapter;
use App\Models\User;
use App\Http\Requests\DS\StoreDSRequest;
use App\Http\Requests\DS\UpdateDSRequest;

class DSGenerationService
{
    /**
     * Chapitres exclus pour les DS type bac
     */
    private const EXCLUDED_BAC_CHAPTERS = [
        'ARITHMETIQUE (MATHS EXPERTES)',
        'COMPLEXES',
        'MATRICES (MATHS EXPERTES)',
        'Vers la prépa'
    ];
    

    /**
     * Génère un DS basé sur les critères de la requête
     *
     * @param StoreDSRequest|UpdateDSRequest $request
     * @param DS|null $ds DS existant si update, null si création
     * @param User $user Utilisateur pour qui générer le DS
     * @return DS
     * @throws \Exception
     */
    public function generate(StoreDSRequest|UpdateDSRequest $request, ?DS $ds, User $user): DS
    {
        // Détacher les relations existantes si on update
        if ($ds !== null) {
            $ds->multipleChapters()->detach();
            $ds->exercisesDS()->detach();
        }

        // Sélectionner tous les exercices des chapitres sélectionnés avec eager loading
        $multipleChapters = MultipleChapter::with('dsExercises')
            ->whereIn('id', $request->multiple_chapters)
            ->get();

        $exercisesDS = $this->collectExercises($multipleChapters, (bool) $request->harder_exercises);

        // Appliquer les règles de sélection selon le type
        if ($request->type_bac) {
            $exercisesDS = $this->applyBacRules($exercisesDS);
        } else {
            $exercisesDS = $this->applyStandardRules($exercisesDS, $request->exercises_number);
        }

        // Limiter au nombre d'exercices demandé
        $exercisesDS = array_slice($exercisesDS, 0, min(count($exercisesDS), $request->exercises_number));

        // Calculer le temps total
        $totalTime = array_sum(array_column($exercisesDS, 'time'));

        // Extraire les IDs des chapitres et exercices
        $multipleChapterIds = array_unique(array_column($exercisesDS, 'multiple_chapter_id'));
        $exerciseIds = array_column($exercisesDS, 'id');

        // Créer ou mettre à jour le DS
        if ($ds === null) {
            $ds = new DS();
            $ds->user_id = $user->id;
        }

        $ds->type_bac = $request->has('type_bac');
        $ds->exercises_number = count($exerciseIds);
        $ds->harder_exercises = $request->has('harder_exercises');
        $ds->time = $totalTime;
        $ds->timer = $totalTime * 60; // timer en secondes
        $ds->chrono = "0";
        $ds->status = "not_started";
        $ds->save();

        // Attacher les chapitres et exercices
        $ds->multipleChapters()->attach($multipleChapterIds);
        $ds->exercisesDS()->attach($exerciseIds);

        return $ds;
    }

    /**
     * Collecte les exercices des chapitres sélectionnés
     *
     * @param \Illuminate\Database\Eloquent\Collection $multipleChapters
     * @param bool $harderExercises
     * @return array
     */
    private function collectExercises($multipleChapters, bool $harderExercises): array
    {
        $exercisesDS = [];

        foreach ($multipleChapters as $multipleChapter) {
            $exercises = $multipleChapter->dsExercises;
            $exercises = $harderExercises
                ? $exercises->where('harder_exercise', 1)
                : $exercises->where('harder_exercise', 0);

            $exercisesDS = array_merge($exercisesDS, $exercises->toArray());
        }

        // Mélanger et supprimer les doublons
        shuffle($exercisesDS);
        $exercisesDS = array_unique($exercisesDS, SORT_REGULAR);

        return $exercisesDS;
    }

    /**
     * Applique les règles de sélection pour un DS type bac
     * - Exclut certains chapitres (maths expertes, prépa)
     * - Supprime les doublons basés sur le même theme
     *
     * @param array $exercisesDS
     * @return array
     */
    private function applyBacRules(array $exercisesDS): array
    {
        // Charger tous les multipleChapters en une requête
        $multipleChapterIds = array_unique(array_column($exercisesDS, 'multiple_chapter_id'));
        $multipleChaptersMap = MultipleChapter::whereIn('id', $multipleChapterIds)->get()->keyBy('id');

        // Attacher le multipleChapter à chaque exercice
        foreach ($exercisesDS as $key => $exercise) {
            $exercisesDS[$key]['multipleChapter'] = $multipleChaptersMap[$exercise['multiple_chapter_id']];
        }

        // Exclure les chapitres spécifiques
        foreach ($exercisesDS as $key => $exercise) {
            if (in_array($exercise['multipleChapter']['title'], self::EXCLUDED_BAC_CHAPTERS)) {
                unset($exercisesDS[$key]);
            }
        }

        // Supprimer les doublons basés sur le même theme (pour diversité)
        $seenThemes = [];
        foreach ($exercisesDS as $key => $exercise) {
            $theme = $exercise['multipleChapter']['theme'];
            if (in_array($theme, $seenThemes)) {
                unset($exercisesDS[$key]);
            } else {
                $seenThemes[] = $theme;
            }
        }

        return $exercisesDS;
    }

    /**
     * Applique les règles de sélection pour un DS standard
     * - Sélectionne d'abord un exercice par chapitre (diversité)
     * - Complète jusqu'au nombre d'exercices demandé
     *
     * @param array $exercisesDS
     * @param int $exercisesNumber
     * @return array
     * @throws \Exception
     */
    private function applyStandardRules(array $exercisesDS, int $exercisesNumber): array
    {
        $selectedChapters = [];
        $selectedExercises = [];

        // Optimisation : récupérer seulement les chapitres nécessaires
        $multipleChapterIds = array_unique(array_column($exercisesDS, 'multiple_chapter_id'));
        $allChapters = MultipleChapter::whereIn('id', $multipleChapterIds)->get();

        // Sélectionner un exercice par chapitre d'abord
        foreach ($exercisesDS as $key => $exercise) {
            $multipleChapter = $allChapters->firstWhere('id', $exercise['multiple_chapter_id']);

            if ($multipleChapter) {
                if (!in_array($multipleChapter->id, $selectedChapters)) {
                    $selectedChapters[] = $multipleChapter->id;
                    $selectedExercises[] = $exercise;
                    unset($exercisesDS[$key]);
                }
            } else {
                throw new \Exception("Le chapitre de l'exercice n'a pas été trouvé");
            }
        }

        // Compléter avec des exercices supplémentaires si nécessaire
        while (count($selectedExercises) < $exercisesNumber && count($exercisesDS) > 0) {
            $selectedExercises[] = array_shift($exercisesDS);
        }

        return $selectedExercises;
    }

}

