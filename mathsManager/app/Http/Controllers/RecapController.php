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
        // Création de la partie de récap
        $recapPart = new RecapPart();
        $recapPart->title = $request->title;
        $recapPart->description = $request->description;
        $recapPart->recap_id = $request->recap_id;
        $recapPart->save();

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
        $recapPartBlock->content = $request->content ? LatexToHtmlConverter::convertForRecap($request->content) : null;
        $recapPartBlock->example = $request->example ? LatexToHtmlConverter::convertForRecap($request->example) : null;
        $recapPartBlock->recap_part_id = $request->recap_part_id;
        $recapPartBlock->subchapter_id = $request->subchapter_id;
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
        $recapPartBlock->example = $request->example ? LatexToHtmlConverter::convertForRecap($request->example) : null;
        $recapPartBlock->latex_content = $request->content;
        $recapPartBlock->content = $request->content ? LatexToHtmlConverter::convertForRecap($request->content) : null;
        $recapPartBlock->subchapter_id = $request->subchapter_id;
        $recapPartBlock->save();

        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }

    // Méthode pour destroy un bloc de partie de récap
    public function destroyPartBlock($id)
    {
        $recapPartBlock = RecapPartBlock::find($id);
        $recapPartBlock->delete();
        return redirect()->route('recap.show', $recapPartBlock->recapPart->recap_id);
    }
}
