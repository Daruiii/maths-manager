<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Classe;
use App\Models\Chapter;
use App\Models\Subchapter;
use App\Services\OrderingService;
use Illuminate\Support\Facades\Log;

class OrderingController extends Controller
{
    protected OrderingService $orderingService;
    
    public function __construct(OrderingService $orderingService)
    {
        $this->orderingService = $orderingService;
    }

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
            $affectedExercises = $this->orderingService->countAffectedExercises('subchapter', $request->subchapter_id);
            
            $this->orderingService->moveSubchapter(
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
            $affectedExercises = $this->orderingService->countAffectedExercises('chapter', $request->chapter_id);
            
            $this->orderingService->moveChapter(
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
            $affectedExercises = $this->orderingService->countAffectedExercises('class', $request->class_id);
            
            $this->orderingService->moveClass(
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
            $this->orderingService->reorderSubchaptersInChapter(
                $request->chapter_id,
                $request->subchapter_orders
            );

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
            $this->orderingService->reorderChaptersInClass(
                $request->class_id,
                $request->chapter_orders
            );

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
            $this->orderingService->reorderClasses($request->class_orders);

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
            $affectedCount = $this->orderingService->countAffectedExercises($request->type, $request->id);
            
            return response()->json([
                'affected_exercises' => $affectedCount,
                'warning_level' => $this->orderingService->getWarningLevel($affectedCount)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'affected_exercises' => 0,
                'warning_level' => 'none'
            ]);
        }
    }

}
