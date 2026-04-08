<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

abstract class BaseBuilderController extends Controller
{
    /**
     * Applique un tri validé sur la query, ou retombe sur le tri par défaut.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param array  $allowed         Colonnes acceptées pour le tri
     * @param string $defaultCol      Première colonne du tri par défaut
     * @param string|null $defaultColSecondary Colonne secondaire optionnelle
     * @param string $defaultDir      Direction du tri par défaut
     */
    protected function applySortOrDefault(
        $query,
        Request $request,
        array $allowed,
        string $defaultCol = 'id',
        string $defaultColSecondary = null,
        string $defaultDir = 'asc'
    ): void {
        $sortDir = $request->query('sort_dir') === 'desc' ? 'desc' : 'asc';
        $sortBy  = in_array($request->query('sort_by'), $allowed) ? $request->query('sort_by') : null;

        if ($sortBy) {
            $query->orderBy($sortBy, $sortDir);
        } else {
            $query->orderBy($defaultCol, $defaultDir);
            if ($defaultColSecondary) {
                $query->orderBy($defaultColSecondary, $defaultDir);
            }
        }
    }
}
