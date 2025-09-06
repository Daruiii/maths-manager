<?php

namespace App\Traits;

use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

trait ManagesOrdering
{
    /**
     * Recalcule TOUS les ordres globaux des exercices
     * À appeler après toute modification de structure
     */
    public static function recalculateAllGlobalExerciseOrders()
    {
        Log::info('Starting global exercise order recalculation');
        
        $globalOrder = 1; // Déclarer la variable AVANT la transaction
        
        DB::transaction(function () use (&$globalOrder) { // Passer par référence
            // Parcourir seulement les classes VISIBLES par display_order
            $classes = Classe::where('hidden', false)->orderBy('display_order')->get();
            
            foreach ($classes as $classe) {
                // Parcourir tous les chapitres par order (maintenant local)
                $chapters = $classe->chapters()->orderBy('order')->get();
                
                foreach ($chapters as $chapter) {
                    // Parcourir tous les sous-chapitres par order (déjà local)
                    $subchapters = $chapter->subchapters()->orderBy('order')->get();
                    
                    foreach ($subchapters as $subchapter) {
                        // Assigner l'ordre global aux exercices
                        $exercises = $subchapter->exercises()->orderBy('order')->get();
                        
                        foreach ($exercises as $exercise) {
                            $exercise->order = $globalOrder++;
                            $exercise->save();
                        }
                    }
                }
            }
        });
        
        Log::info("Global exercise order recalculation completed. New max order: " . ($globalOrder - 1));
    }

    /**
     * Déplace un sous-chapitre vers un nouveau chapitre
     */
    public static function moveSubchapter($subchapterId, $newChapterId, $newPosition)
    {
        Log::info("Moving subchapter $subchapterId to chapter $newChapterId at position $newPosition");
        
        DB::transaction(function () use ($subchapterId, $newChapterId, $newPosition) {
            $subchapter = Subchapter::findOrFail($subchapterId);
            $oldChapterId = $subchapter->chapter_id;
            
            // 1. Déplacer le sous-chapitre
            $subchapter->chapter_id = $newChapterId;
            $subchapter->order = $newPosition;
            $subchapter->save();
            
            // 2. Réorganiser les ordres dans l'ancien chapitre
            if ($oldChapterId != $newChapterId) {
                static::reorderSubchaptersInChapter($oldChapterId);
            }
            
            // 3. Réorganiser les ordres dans le nouveau chapitre
            static::reorderSubchaptersInChapter($newChapterId);
            
            // 4. Recalculer tous les ordres globaux d'exercices
            static::recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Déplace un chapitre vers une nouvelle classe
     */
    public static function moveChapter($chapterId, $newClassId, $newPosition)
    {
        Log::info("Moving chapter $chapterId to class $newClassId at position $newPosition");
        
        DB::transaction(function () use ($chapterId, $newClassId, $newPosition) {
            $chapter = Chapter::findOrFail($chapterId);
            $oldClassId = $chapter->class_id;
            
            // 1. Déplacer le chapitre
            $chapter->class_id = $newClassId;
            $chapter->order = $newPosition;
            $chapter->save();
            
            // 2. Réorganiser les ordres dans l'ancienne classe
            if ($oldClassId != $newClassId) {
                static::reorderChaptersInClass($oldClassId);
            }
            
            // 3. Réorganiser les ordres dans la nouvelle classe
            static::reorderChaptersInClass($newClassId);
            
            // 4. Recalculer tous les ordres globaux d'exercices
            static::recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Réorganise une classe dans l'ordre d'affichage
     */
    public static function moveClass($classId, $newDisplayOrder)
    {
        Log::info("Moving class $classId to display order $newDisplayOrder");
        
        DB::transaction(function () use ($classId, $newDisplayOrder) {
            $classe = Classe::findOrFail($classId);
            $oldDisplayOrder = $classe->display_order;
            
            // Si on monte la classe, décaler les autres vers le bas
            if ($newDisplayOrder < $oldDisplayOrder) {
                Classe::where('display_order', '>=', $newDisplayOrder)
                    ->where('display_order', '<', $oldDisplayOrder)
                    ->increment('display_order');
            }
            // Si on descend la classe, décaler les autres vers le haut
            else if ($newDisplayOrder > $oldDisplayOrder) {
                Classe::where('display_order', '>', $oldDisplayOrder)
                    ->where('display_order', '<=', $newDisplayOrder)
                    ->decrement('display_order');
            }
            
            // Assigner la nouvelle position
            $classe->display_order = $newDisplayOrder;
            $classe->save();
            
            // Recalculer tous les ordres globaux d'exercices
            static::recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Réorganise les ordres des sous-chapitres dans un chapitre
     */
    private static function reorderSubchaptersInChapter($chapterId)
    {
        $subchapters = Subchapter::where('chapter_id', $chapterId)->orderBy('order')->get();
        
        foreach ($subchapters as $index => $subchapter) {
            $subchapter->order = $index + 1;
            $subchapter->save();
        }
    }

    /**
     * Réorganise les ordres des chapitres dans une classe
     */
    private static function reorderChaptersInClass($classId)
    {
        $chapters = Chapter::where('class_id', $classId)->orderBy('order')->get();
        
        foreach ($chapters as $index => $chapter) {
            $chapter->order = $index + 1;
            $chapter->save();
        }
    }

    /**
     * Compte le nombre d'exercices qui seront impactés par une réorganisation
     */
    public static function countAffectedExercises($type, $id)
    {
        switch ($type) {
            case 'subchapter':
                return Subchapter::findOrFail($id)->exercises()->count();
            
            case 'chapter':
                $chapter = Chapter::findOrFail($id);
                return Exercise::whereIn('subchapter_id', 
                    $chapter->subchapters()->pluck('id')
                )->count();
            
            case 'class':
                $classe = Classe::findOrFail($id);
                return Exercise::whereIn('subchapter_id', 
                    Subchapter::whereIn('chapter_id',
                        $classe->chapters()->pluck('id')
                    )->pluck('id')
                )->count();
            
            default:
                return 0;
        }
    }
}
