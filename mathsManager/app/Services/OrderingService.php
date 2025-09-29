<?php

namespace App\Services;

use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Models\Exercise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class OrderingService
{
    /**
     * Recalcule TOUS les ordres globaux des exercices
     * À appeler après toute modification de structure
     */
    public function recalculateAllGlobalExerciseOrders(): void
    {
        Log::info('Starting global exercise order recalculation');
        
        $globalOrder = 1;
        
        DB::transaction(function () use (&$globalOrder) {
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
    public function moveSubchapter(int $subchapterId, int $newChapterId, int $newPosition): void
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
                $this->reorderSubchaptersInChapter($oldChapterId);
            }
            
            // 3. Réorganiser les ordres dans le nouveau chapitre
            $this->reorderSubchaptersInChapter($newChapterId);
            
            // 4. Recalculer tous les ordres globaux d'exercices
            $this->recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Déplace un chapitre vers une nouvelle classe
     */
    public function moveChapter(int $chapterId, int $newClassId, int $newPosition): void
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
                $this->reorderChaptersInClass($oldClassId);
            }
            
            // 3. Réorganiser les ordres dans la nouvelle classe
            $this->reorderChaptersInClass($newClassId);
            
            // 4. Recalculer tous les ordres globaux d'exercices
            $this->recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Réorganise une classe dans l'ordre d'affichage
     */
    public function moveClass(int $classId, int $newDisplayOrder): void
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
            $this->recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Réorganise les sous-chapitres dans un chapitre (drag interne)
     */
    public function reorderSubchaptersInChapter(int $chapterId, array $subchapterOrders = null): void
    {
        DB::transaction(function () use ($chapterId, $subchapterOrders) {
            if ($subchapterOrders) {
                // Mise à jour avec l'ordre spécifié
                foreach ($subchapterOrders as $data) {
                    $subchapter = Subchapter::find($data['id']);
                    $subchapter->order = $data['order'];
                    $subchapter->save();
                }
            } else {
                // Réorganisation automatique pour fermer les trous
                $subchapters = Subchapter::where('chapter_id', $chapterId)->orderBy('order')->get();
                
                foreach ($subchapters as $index => $subchapter) {
                    $subchapter->order = $index + 1;
                    $subchapter->save();
                }
            }
            
            $this->recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Réorganise les chapitres dans une classe (drag interne)
     */
    public function reorderChaptersInClass(int $classId, array $chapterOrders = null): void
    {
        DB::transaction(function () use ($classId, $chapterOrders) {
            if ($chapterOrders) {
                // Mise à jour avec l'ordre spécifié
                foreach ($chapterOrders as $data) {
                    $chapter = Chapter::find($data['id']);
                    $chapter->order = $data['order'];
                    $chapter->save();
                }
            } else {
                // Réorganisation automatique pour fermer les trous
                $chapters = Chapter::where('class_id', $classId)->orderBy('order')->get();
                
                foreach ($chapters as $index => $chapter) {
                    $chapter->order = $index + 1;
                    $chapter->save();
                }
            }
            
            $this->recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Réorganise l'ordre d'affichage des classes (drag interne)
     */
    public function reorderClasses(array $classOrders): void
    {
        DB::transaction(function () use ($classOrders) {
            foreach ($classOrders as $data) {
                $classe = Classe::find($data['id']);
                $classe->display_order = $data['display_order'];
                $classe->save();
            }
            
            $this->recalculateAllGlobalExerciseOrders();
        });
    }

    /**
     * Compte le nombre d'exercices qui seront impactés par une réorganisation
     */
    public function countAffectedExercises(string $type, int $id): int
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

    /**
     * Détermine le niveau d'alerte selon le nombre d'exercices impactés
     */
    public function getWarningLevel(int $count): string
    {
        if ($count < 50) return 'low';
        if ($count < 200) return 'medium'; 
        return 'high';
    }
}
