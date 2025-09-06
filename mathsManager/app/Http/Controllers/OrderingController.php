<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Traits\ManagesOrdering;
use Illuminate\Support\Facades\Log;

class OrderingController extends Controller
{
    use ManagesOrdering;

    /**
     * Déplace un sous-chapitre vers un nouveau chapitre
     */
    public function moveSubchapter(Request $request)
    {
        $request->validate([
            'subchapter_id' => 'required|integer|exists:subchapters,id',
            'new_chapter_id' => 'required|integer|exists:chapters,id', 
            'new_position' => 'required|integer|min:1'
        ]);

        try {
            // Compter les exercices impactés pour info
            $affectedExercises = static::countAffectedExercises('subchapter', $request->subchapter_id);
            
            static::moveSubchapter(
                $request->subchapter_id,
                $request->new_chapter_id,
                $request->new_position
            );

            return response()->json([
                'status' => 'success',
                'message' => "Sous-chapitre déplacé avec succès",
                'affected_exercises' => $affectedExercises
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to move subchapter: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du déplacement du sous-chapitre'
            ], 500);
        }
    }

    /**
     * Déplace un chapitre vers une nouvelle classe
     */
    public function moveChapter(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required|integer|exists:chapters,id',
            'new_class_id' => 'required|integer|exists:classes,id',
            'new_position' => 'required|integer|min:1'
        ]);

        try {
            // Compter les exercices impactés pour info
            $affectedExercises = static::countAffectedExercises('chapter', $request->chapter_id);
            
            static::moveChapter(
                $request->chapter_id,
                $request->new_class_id,
                $request->new_position
            );

            return response()->json([
                'status' => 'success',
                'message' => "Chapitre déplacé avec succès",
                'affected_exercises' => $affectedExercises
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to move chapter: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors du déplacement du chapitre'
            ], 500);
        }
    }

    /**
     * Réorganise l'ordre d'affichage des classes
     */
    public function moveClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'new_display_order' => 'required|integer|min:1'
        ]);

        try {
            // Compter les exercices impactés pour info
            $affectedExercises = static::countAffectedExercises('class', $request->class_id);
            
            static::moveClass(
                $request->class_id,
                $request->new_display_order
            );

            return response()->json([
                'status' => 'success', 
                'message' => "Classe réorganisée avec succès",
                'affected_exercises' => $affectedExercises
            ]);

        } catch (\Exception $e) {
            Log::error("Failed to move class: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la réorganisation de la classe'
            ], 500);
        }
    }

    /**
     * Réorganise les sous-chapitres dans un chapitre (drag interne)
     */
    public function reorderSubchaptersInChapter(Request $request)
    {
        $request->validate([
            'chapter_id' => 'required|integer|exists:chapters,id',
            'subchapter_orders' => 'required|array',
            'subchapter_orders.*.id' => 'required|integer|exists:subchapters,id',
            'subchapter_orders.*.order' => 'required|integer|min:1'
        ]);

        try {
            foreach ($request->subchapter_orders as $data) {
                $subchapter = Subchapter::find($data['id']);
                $subchapter->order = $data['order'];
                $subchapter->save();
            }

            // Recalculer tous les ordres globaux d'exercices
            static::recalculateAllGlobalExerciseOrders();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error("Failed to reorder subchapters: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la réorganisation'
            ], 500);
        }
    }

    /**
     * Réorganise les chapitres dans une classe (drag interne)
     */
    public function reorderChaptersInClass(Request $request)
    {
        $request->validate([
            'class_id' => 'required|integer|exists:classes,id',
            'chapter_orders' => 'required|array',
            'chapter_orders.*.id' => 'required|integer|exists:chapters,id',
            'chapter_orders.*.order' => 'required|integer|min:1'
        ]);

        try {
            foreach ($request->chapter_orders as $data) {
                $chapter = Chapter::find($data['id']);
                $chapter->order = $data['order'];
                $chapter->save();
            }

            // Recalculer tous les ordres globaux d'exercices
            static::recalculateAllGlobalExerciseOrders();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error("Failed to reorder chapters: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la réorganisation'
            ], 500);
        }
    }

    /**
     * Réorganise l'ordre d'affichage des classes (drag interne)
     */
    public function reorderClasses(Request $request)
    {
        $request->validate([
            'class_orders' => 'required|array',
            'class_orders.*.id' => 'required|integer|exists:classes,id',
            'class_orders.*.display_order' => 'required|integer|min:1'
        ]);

        try {
            foreach ($request->class_orders as $data) {
                $classe = Classe::find($data['id']);
                $classe->display_order = $data['display_order'];
                $classe->save();
            }

            // Recalculer tous les ordres globaux d'exercices
            static::recalculateAllGlobalExerciseOrders();

            return response()->json(['status' => 'success']);

        } catch (\Exception $e) {
            Log::error("Failed to reorder classes: " . $e->getMessage());
            return response()->json([
                'status' => 'error',
                'message' => 'Erreur lors de la réorganisation'
            ], 500);
        }
    }

    /**
     * Preview du nombre d'exercices qui seront impactés
     */
    public function previewMove(Request $request)
    {
        $request->validate([
            'type' => 'required|in:subchapter,chapter,class',
            'id' => 'required|integer'
        ]);

        try {
            $affectedCount = static::countAffectedExercises($request->type, $request->id);
            
            return response()->json([
                'affected_exercises' => $affectedCount,
                'warning_level' => $this->getWarningLevel($affectedCount)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'affected_exercises' => 0,
                'warning_level' => 'none'
            ]);
        }
    }

    /**
     * Détermine le niveau d'alerte selon le nombre d'exercices impactés
     */
    private function getWarningLevel($count)
    {
        if ($count < 50) return 'low';
        if ($count < 200) return 'medium'; 
        return 'high';
    }
}
