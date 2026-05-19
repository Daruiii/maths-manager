<?php

namespace App\Services;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Factorise le pattern commun aux deux builders (DS + TD) :
 * paginer une collection d'exercices et y attacher les chemins d'images
 * stockées sur le filesystem.
 */
class BuilderSearchService
{
    public function __construct(private FileUploadService $fileUploadService) {}

    /**
     * Attache les image_paths filesystem à chaque item d'un paginateur.
     *
     * @param LengthAwarePaginator $results  Résultat paginé
     * @param string               $context  Contexte de stockage (ex: 'exercises')
     * @param callable             $identifier  Fonction (item) => string identifiant le dossier (ex: 'exercise-42/statement')
     */
    public function withImages(
        LengthAwarePaginator $results,
        string $context,
        callable $identifier
    ): LengthAwarePaginator {
        $results->getCollection()->transform(function ($item) use ($context, $identifier) {
            $files = $this->fileUploadService->getFiles($context, $identifier($item), true, 'img-*');

            $imagePaths = [];
            foreach ($files as $path) {
                $imagePaths[pathinfo($path, PATHINFO_FILENAME)] = $path;
            }

            $item->image_paths = $imagePaths ?: null;
            return $item;
        });

        return $results;
    }
}
