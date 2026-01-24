<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class QueryFiltersService
{
    /**
     * Applique les filtres dynamiques à une requête Eloquent
     *
     * @param Builder $query
     * @param Request $request
     * @param array $filterFields Map des paramètres request vers colonnes DB
     *                            Ex: ['multiple_chapter_id' => 'multiple_chapter_id', 'type' => 'type']
     * @return Builder
     */
    public function applyFilters(
        Builder $query,
        Request $request,
        array $filterFields
    ): Builder {
        foreach ($filterFields as $requestParam => $dbColumn) {
            if ($request->filled($requestParam)) {
                $query->where($dbColumn, $request->input($requestParam));
            }
        }

        return $query;
    }

    /**
     * Applique un filtre de recherche avec conditions OR sur plusieurs colonnes
     *
     * @param Builder $query
     * @param string|null $searchTerm
     * @param array $searchColumns Colonnes dans lesquelles rechercher
     * @return Builder
     */
    public function applySearch(
        Builder $query,
        ?string $searchTerm,
        array $searchColumns = ['name', 'id']
    ): Builder {
        if (empty($searchTerm)) {
            return $query;
        }

        return $query->where(function ($q) use ($searchTerm, $searchColumns) {
            foreach ($searchColumns as $column) {
                $q->orWhere($column, 'like', '%' . $searchTerm . '%');
            }
        });
    }

    /**
     * Récupère les filtres actifs depuis la requête
     * Utile pour les passer à la vue
     *
     * @param Request $request
     * @param array $filterFields
     * @return array
     */
    public function getActiveFilters(
        Request $request,
        array $filterFields
    ): array {
        $activeFilters = [];

        foreach ($filterFields as $requestParam => $dbColumn) {
            if ($request->filled($requestParam)) {
                $activeFilters[$requestParam] = $request->input($requestParam);
            }
        }

        return $activeFilters;
    }
}
