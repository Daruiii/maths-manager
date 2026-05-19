<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BuilderTemplate;
use Illuminate\Http\Request;

abstract class BaseBuilderController extends Controller
{
    /**
     * Charge le template initial depuis le query param `template`, en vérifiant
     * que le template appartient au professeur et correspond au bon type.
     * Retourne le payload mergé avec les métadonnées, ou null.
     */
    protected function loadInitialTemplate(Request $request, $teacher, string $type): ?array
    {
        if (! $templateId = $request->integer('template')) {
            return null;
        }

        $tpl = BuilderTemplate::where('id', $templateId)
            ->where('teacher_id', $teacher->id)
            ->where('type', $type)
            ->first();

        if (! $tpl) {
            return null;
        }

        return array_merge($tpl->payload, [
            'id'               => $tpl->id,
            'name'             => $tpl->name,
            'student_group_id' => $tpl->student_group_id,
        ]);
    }

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
