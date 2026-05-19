<?php

namespace App\Http\Traits;

use App\Models\Chapter;
use App\Models\Classe;
use App\Models\Subchapter;

/**
 * Fournit les données de catalogue (classes, chapitres, sous-chapitres)
 * aux controllers qui en ont besoin pour les formulaires d'exercices.
 */
trait ProvidesCatalogueData
{
    protected function catalogueData(): array
    {
        return [
            'classes'     => Classe::where('hidden', false)->orderBy('display_order')->get(['id', 'name']),
            'chapters'    => Chapter::orderBy('class_id')->orderBy('order')->get(['id', 'title', 'class_id']),
            'subchapters' => Subchapter::orderBy('chapter_id')->orderBy('order')->get(['id', 'title', 'chapter_id']),
        ];
    }
}
