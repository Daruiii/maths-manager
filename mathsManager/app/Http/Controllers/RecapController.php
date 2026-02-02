<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Recap;
use App\Models\RecapPart;
use App\Models\RecapPartBlock;
use App\Models\Chapter;
use App\Services\LatexToHtmlConverter;
use App\Http\Requests\Recap\StoreRecapRequest;
use App\Http\Requests\Recap\StoreRecapPartRequest;
use App\Http\Requests\Recap\UpdateRecapPartRequest;
use App\Http\Requests\Recap\StoreRecapPartBlockRequest;
use App\Http\Requests\Recap\UpdateRecapPartBlockRequest;

class RecapController extends Controller
{

    // Méthode pour show un récap
    public function show($id)
    {
        $recap = Recap::find($id);
        $recapParts = $recap->recapParts;
        $recapPartBlocks = [];
        foreach ($recapParts as $recapPart) {
            $recapPartBlocks[$recapPart->id] = $recapPart->recapPartBlocks;
        }
        return view('recap.show', compact('recap'));
    }

    // Méthode pour créer un récap
    public function create($id)
    {
        $chapter_id = $id;
        return view('recap.create',  compact('chapter_id'));
    }

    // Méthode pour stocker un récap
    public function store(StoreRecapRequest $request)
    {
        // Création du récap
        $recap = new Recap();
        $recap->title = $request->title ?? 'Récapitulatif';
        $recap->chapter_id = $request->chapter_id;
        $recap->save();

        return redirect()->route('recap.show', $recap->id);
    }

    // Méthode pour destroy un récap
    public function destroy($id)
    {
        $recap = Recap::find($id);
        $recap->delete();
        return redirect()->route('classe.show', $recap->chapter->classe->level);
    }

    // Méthode pour créer une partie de récap
    public function createPart($id)
    {
        $recap_id = $id;
        return view('recap.createPart', compact('recap_id'));
    }

    // Méthode pour stocker une partie de récap
    public function storePart(StoreRecapPartRequest $request)
    {
        $maxOrder = RecapPart::withoutGlobalScope('order')
            ->where('recap_id', $request->recap_id)
            ->max('order') ?? -1;

        $recapPart = RecapPart::create([
            'title' => $request->title,
            'description' => $request->description,
            'recap_id' => $request->recap_id,
            'order' => $maxOrder + 1
        ]);

        return redirect()->route('recap.show', $recapPart->recap_id);
    }

    // Méthode pour éditer une partie de récap
    public function editPart($id)
    {
        $recapPart = RecapPart::find($id);
        return view('recap.editPart', compact('recapPart'));
    }

    // Méthode pour mettre à jour une partie de récap
    public function updatePart(UpdateRecapPartRequest $request, $id)
    {
        // Mise à jour de la partie de récap
        $recapPart = RecapPart::find($id);
        $recapPart->title = $request->title;
        $recapPart->description = $request->description;
        $recapPart->save();

        return redirect()->route('recap.show', $recapPart->recap_id);
    }

    // Méthode pour destroy une partie de récap
    public function destroyPart($id)
    {
        $recapPart = RecapPart::find($id);
        $recapPart->delete();
        return redirect()->route('recap.show', $recapPart->recap_id);
    }

    // Méthode pour créer un bloc de partie de récap
    public function createPartBlock($id)
    {
        $recapPart_id = $id;
        $chapter_id = RecapPart::find($recapPart_id)->recap->chapter_id;
        $subchapters = Chapter::find($chapter_id)->subchapters;
        return view('recap.createPartBlock', compact('recapPart_id', 'subchapters'));
    }

    // Méthode pour stocker un bloc de partie de récap
    public function storePartBlock(StoreRecapPartBlockRequest $request)
    {
        // Création du bloc de partie de récap
        $recapPartBlock = new RecapPartBlock();
        $recapPartBlock->title = $request->title;
        $recapPartBlock->theme = $request->theme ?? 'grey';
        $recapPartBlock->latex_content = $request->content;
        $recapPartBlock->latex_example = $request->example;
        $recapPartBlock->latex_demonstration = $request->demonstration;
        $recapPartBlock->latex_remarque = $request->remarque;
        $recapPartBlock->content = $request->content ? LatexToHtmlConverter::convertForRecap($request->content) : null;
        $recapPartBlock->example = $request->example ? LatexToHtmlConverter::convertForRecap($request->example) : null;
        $recapPartBlock->demonstration = $request->demonstration ? LatexToHtmlConverter::convertForRecap($request->demonstration) : null;
        $recapPartBlock->remarque = $request->remarque ? LatexToHtmlConverter::convertForRecap($request->remarque) : null;
        $recapPartBlock->recap_part_id = $request->recap_part_id;
        $recapPartBlock->subchapter_id = $request->subchapter_id;

        // Set order as the last position
        $maxOrder = RecapPartBlock::where('recap_part_id', $request->recap_part_id)->max('order') ?? -1;
        $recapPartBlock->order = $maxOrder + 1;

        $recapPartBlock->save();

        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }

    // Méthode pour éditer un bloc de partie de récap
    public function editPartBlock($id)
    {
        $recapPartBlock = RecapPartBlock::find($id);
        $subchapters = Chapter::find($recapPartBlock->recapPart->recap->chapter_id)->subchapters;

        return view('recap.editPartBlock', compact('recapPartBlock', 'subchapters'));
    }

    // Méthode pour mettre à jour un bloc de partie de récap
    public function updatePartBlock(UpdateRecapPartBlockRequest $request, $id)
    {
        // Mise à jour du bloc de partie de récap
        $recapPartBlock = RecapPartBlock::find($id);
        $recapPartBlock->title = $request->title;
        $recapPartBlock->theme = $request->theme ?? 'grey';
        $recapPartBlock->latex_example = $request->example;
        $recapPartBlock->latex_demonstration = $request->demonstration;
        $recapPartBlock->latex_remarque = $request->remarque;
        $recapPartBlock->example = $request->example ? LatexToHtmlConverter::convertForRecap($request->example) : null;
        $recapPartBlock->demonstration = $request->demonstration ? LatexToHtmlConverter::convertForRecap($request->demonstration) : null;
        $recapPartBlock->remarque = $request->remarque ? LatexToHtmlConverter::convertForRecap($request->remarque) : null;
        $recapPartBlock->latex_content = $request->content;
        $recapPartBlock->content = $request->content ? LatexToHtmlConverter::convertForRecap($request->content) : null;
        $recapPartBlock->subchapter_id = $request->subchapter_id;
        $recapPartBlock->save();

        return redirect()->route('recap.show', [
        'id' => $recapPartBlock->recapPart->recap_id,
        'block' => $recapPartBlock->id
    ]);
    }

    // Méthode pour destroy un bloc de partie de récap
    public function destroyPartBlock($id)
    {
        $recapPartBlock = RecapPartBlock::find($id);
        $recapPartBlock->delete();
        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }

    // Méthode pour déplacer une partie de récap vers le haut
    public function movePartUp($id)
    {
        $recapPart = RecapPart::withoutGlobalScope('order')->find($id);

        if (!$recapPart) {
            return response()->json(['success' => false, 'message' => 'Partie non trouvée'], 404);
        }

        $recap_id = $recapPart->recap_id;

        // Trouver la partie précédente
        $previousPart = RecapPart::withoutGlobalScope('order')
            ->where('recap_id', $recap_id)
            ->where('order', '<', $recapPart->order)
            ->orderBy('order', 'desc')
            ->first();

        if ($previousPart) {
            // Échanger les ordres
            $tempOrder = $recapPart->order;
            $recapPart->order = $previousPart->order;
            $previousPart->order = $tempOrder;

            $recapPart->save();
            $previousPart->save();

            return response()->json(['success' => true, 'message' => 'Partie déplacée vers le haut']);
        }

        return response()->json(['success' => false, 'message' => 'Déjà en première position']);
    }

    // Méthode pour déplacer une partie de récap vers le bas
    public function movePartDown($id)
    {
        $recapPart = RecapPart::withoutGlobalScope('order')->find($id);

        if (!$recapPart) {
            return response()->json(['success' => false, 'message' => 'Partie non trouvée'], 404);
        }

        $recap_id = $recapPart->recap_id;

        // Trouver la partie suivante
        $nextPart = RecapPart::withoutGlobalScope('order')
            ->where('recap_id', $recap_id)
            ->where('order', '>', $recapPart->order)
            ->orderBy('order', 'asc')
            ->first();

        if ($nextPart) {
            // Échanger les ordres
            $tempOrder = $recapPart->order;
            $recapPart->order = $nextPart->order;
            $nextPart->order = $tempOrder;

            $recapPart->save();
            $nextPart->save();

            return response()->json(['success' => true, 'message' => 'Partie déplacée vers le bas']);
        }

        return response()->json(['success' => false, 'message' => 'Déjà en dernière position']);
    }

    // Méthode pour réorganiser les blocs d'une partie (AJAX)
    public function reorderBlocks(Request $request)
    {
        $validated = $request->validate([
            'blocks' => 'required|array',
            'blocks.*' => 'required|integer|exists:recap_part_blocks,id',
        ]);

        foreach ($validated['blocks'] as $index => $blockId) {
            RecapPartBlock::where('id', $blockId)->update(['order' => $index]);
        }

        return response()->json(['success' => true]);
    }

    // Méthode pour déplacer un bloc vers une autre partie
    public function moveBlockToPart(Request $request, $id)
    {
        $block = RecapPartBlock::findOrFail($id);
        $newPartId = $request->input('recap_part_id');

        if ($newPartId) {
            $maxOrder = RecapPartBlock::where('recap_part_id', $newPartId)->max('order') ?? -1;

            $block->update([
                'recap_part_id' => $newPartId,
                'order' => $maxOrder + 1
            ]);
        }

        return redirect()->route('recap.show', $block->recapPart->recap_id);
    }
}
