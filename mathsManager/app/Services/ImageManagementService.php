<?php

namespace App\Services;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Service centralisé pour la gestion intelligente des images
 *
 * Responsabilités :
 * - Numérotation intelligente (max + 1) avec détection de trous
 * - Gestion des suppressions marquées
 * - Validation des références LaTeX
 * - Synchronisation nomenclature Frontend ↔ Backend
 *
 * @see exclude/docs/features/14.3-uploads-securises-amelioration-images.md
 */
class ImageManagementService
{
    public function __construct(
        private FileUploadService $fileUploadService
    ) {}

    /**
     * Gère l'upload complet des images avec numérotation intelligente
     *
     * @param Request $request
     * @param string $inputName Nom du champ form (ex: 'images', 'images_statement')
     * @param string $deleteInputName Nom du champ pour suppressions (ex: 'delete_images')
     * @param string $context Contexte storage (ex: 'problems', 'exercises')
     * @param string $identifier Identifiant unique (ex: 'problem-123', 'exercise-456/statement')
     * @param string $prefix Préfixe des noms (ex: 'img-')
     * @param bool $isPublic Public ou privé
     * @return array ['filename' => 'path'] des images finales
     */
    public function handleImageUpload(
        Request $request,
        string $inputName,
        string $deleteInputName,
        string $context,
        string $identifier,
        string $prefix = 'img-',
        bool $isPublic = true
    ): array {
        $imagePaths = [];

        // Étape 1 : Supprimer les images marquées pour suppression
        $this->deleteMarkedImages($request, $deleteInputName, $context, $identifier, $isPublic);

        // Étape 2 : Récupérer les images existantes (après suppression)
        $existingImages = $this->fileUploadService->getFiles(
            $context,
            $identifier,
            $isPublic,
            $prefix . '*'
        );

        // Ajouter les images existantes au résultat
        foreach ($existingImages as $path) {
            $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
            $imagePaths[$filename] = $path;
        }

        // Étape 3 : Uploader les nouvelles images avec numérotation intelligente
        if ($request->hasFile($inputName)) {
            $maxImageNumber = $this->calculateMaxImageNumber($existingImages, $prefix);
            $newFiles = $request->file($inputName);

            foreach ($newFiles as $index => $file) {
                $nextIndex = $maxImageNumber + $index + 1;
                $customName = $prefix . $nextIndex;

                $path = $this->fileUploadService->upload(
                    file: $file,
                    context: $context,
                    identifier: $identifier,
                    type: 'image',
                    isPublic: $isPublic,
                    customName: $customName
                );

                $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));
                $imagePaths[$filename] = $path;
            }

            Log::info("ImageManagementService: Uploaded {$inputName}", [
                'context' => $context,
                'identifier' => $identifier,
                'max_before' => $maxImageNumber,
                'new_count' => count($newFiles),
                'total_after' => count($imagePaths)
            ]);
        }

        return $imagePaths;
    }

    /**
     * Supprime les images marquées pour suppression
     *
     * @param Request $request
     * @param string $deleteInputName Nom du champ (ex: 'delete_images[]')
     * @param string $context
     * @param string $identifier
     * @param bool $isPublic
     * @return int Nombre d'images supprimées
     */
    public function deleteMarkedImages(
        Request $request,
        string $deleteInputName,
        string $context,
        string $identifier,
        bool $isPublic = true
    ): int {
        $deletedCount = 0;

        if ($request->has($deleteInputName)) {
            foreach ($request->input($deleteInputName) as $imageName) {
                // Pattern glob pour trouver le fichier avec n'importe quelle extension
                $files = $this->fileUploadService->getFiles(
                    $context,
                    $identifier,
                    $isPublic,
                    $imageName . '.*'
                );

                $deletedCount += $this->fileUploadService->deleteMultiple($files, $isPublic);
            }

            if ($deletedCount > 0) {
                Log::info("ImageManagementService: Deleted marked images", [
                    'context' => $context,
                    'identifier' => $identifier,
                    'count' => $deletedCount
                ]);
            }
        }

        return $deletedCount;
    }

    /**
     * Calcule le numéro maximum parmi les images existantes
     *
     * Utilise regex pour extraire le numéro depuis le nom de fichier.
     * Ex: 'img-1.jpg', 'img-3.png' → retourne 3
     *
     * @param array $imagePaths Chemins des images existantes
     * @param string $prefix Préfixe à chercher (ex: 'img-')
     * @return int Numéro maximum trouvé (0 si aucune image)
     */
    public function calculateMaxImageNumber(array $imagePaths, string $prefix = 'img-'): int
    {
        $maxImageNumber = 0;

        foreach ($imagePaths as $path) {
            $filename = basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION));

            // Regex pour extraire le numéro : img-(\d+)
            $pattern = '/' . preg_quote($prefix, '/') . '(\d+)/';

            if (preg_match($pattern, $filename, $matches)) {
                $maxImageNumber = max($maxImageNumber, (int)$matches[1]);
            }
        }

        return $maxImageNumber;
    }

    /**
     * Valide que toutes les références \graph{} dans le LaTeX correspondent à des images existantes
     *
     * Résout l'inconvénient #3 de la doc : "Couplage LaTeX ↔ Noms fichiers"
     *
     * @param string $latex Contenu LaTeX à valider
     * @param array $imagePaths ['filename' => 'path'] des images disponibles
     * @return array ['valid' => bool, 'missing' => string[], 'unused' => string[]]
     */
    public function validateLatexReferences(string $latex, array $imagePaths): array
    {
        // Extraire toutes les références \graph{nom_image}
        preg_match_all('/\\\\graph\{([^}]+)\}/', $latex, $matches);
        $referencedImages = $matches[1] ?? [];

        // Images disponibles (keys du tableau)
        $availableImages = array_keys($imagePaths);

        // Images manquantes (référencées mais pas uploadées)
        $missing = array_diff($referencedImages, $availableImages);

        // Images inutilisées (uploadées mais pas référencées)
        $unused = array_diff($availableImages, $referencedImages);

        $valid = empty($missing);

        if (!$valid) {
            Log::warning("ImageManagementService: LaTeX validation failed", [
                'missing' => $missing,
                'unused' => $unused
            ]);
        }

        return [
            'valid' => $valid,
            'missing' => array_values($missing),
            'unused' => array_values($unused)
        ];
    }

    /**
     * Prépare les images existantes pour le composant image-manager
     *
     * Format attendu par le composant :
     * [
     *   ['name' => 'img-1', 'path' => 'exercises/exercise-123/img-1.jpg'],
     *   ['name' => 'img-2', 'path' => 'exercises/exercise-123/img-2.jpg']
     * ]
     *
     * @param string $context
     * @param string $identifier
     * @param bool $isPublic
     * @param string $pattern Pattern glob (ex: 'img-*')
     * @return array
     */
    public function getFormattedImagesForComponent(
        string $context,
        string $identifier,
        bool $isPublic = true,
        string $pattern = 'img-*'
    ): array {
        $existingImages = $this->fileUploadService->getFiles($context, $identifier, $isPublic, $pattern);

        return array_values(array_map(function($path) {
            return [
                'name' => basename($path, '.' . pathinfo($path, PATHINFO_EXTENSION)),
                'path' => $path
            ];
        }, $existingImages));
    }

    /**
     * Obtient le prochain nom d'image disponible
     *
     * Résout l'inconvénient #4 : "Synchronisation Frontend ↔ Backend"
     * Le backend devient la source de vérité unique.
     *
     * @param string $context
     * @param string $identifier
     * @param string $prefix
     * @param bool $isPublic
     * @return string Prochain nom (ex: 'img-5')
     */
    public function getNextImageName(
        string $context,
        string $identifier,
        string $prefix = 'img-',
        bool $isPublic = true
    ): string {
        $existingImages = $this->fileUploadService->getFiles($context, $identifier, $isPublic, $prefix . '*');
        $maxNumber = $this->calculateMaxImageNumber($existingImages, $prefix);

        return $prefix . ($maxNumber + 1);
    }

    /**
     * Vérifie si une image existe dans le contexte donné
     *
     * @param string $imageName Nom sans extension (ex: 'img-1')
     * @param string $context
     * @param string $identifier
     * @param bool $isPublic
     * @return bool
     */
    public function imageExists(
        string $imageName,
        string $context,
        string $identifier,
        bool $isPublic = true
    ): bool {
        $files = $this->fileUploadService->getFiles(
            $context,
            $identifier,
            $isPublic,
            $imageName . '.*'
        );

        return count($files) > 0;
    }

    /**
     * Compte le nombre d'images dans un contexte
     *
     * @param string $context
     * @param string $identifier
     * @param string $prefix
     * @param bool $isPublic
     * @return int
     */
    public function countImages(
        string $context,
        string $identifier,
        string $prefix = 'img-',
        bool $isPublic = true
    ): int {
        $images = $this->fileUploadService->getFiles($context, $identifier, $isPublic, $prefix . '*');
        return count($images);
    }

    /**
     * Valide et gère les erreurs LaTeX avec stratégie intelligente
     *
     * Stratégie Option C :
     * - Images MANQUANTES → Exception (bloquant)
     * - Images INUTILISÉES → Session flash warning (non bloquant)
     *
     * @param string $latex Contenu LaTeX à valider
     * @param array $imagePaths ['filename' => 'path'] des images disponibles
     * @param string $fieldName Nom du champ pour l'erreur (ex: 'statement', 'solution')
     * @throws \Illuminate\Validation\ValidationException Si images manquantes
     * @return void
     */
    public function validateLatexReferencesOrFail(
        string $latex,
        array $imagePaths,
        string $fieldName = 'statement'
    ): void {
        if (empty($latex)) {
            return; // Pas de LaTeX = pas de validation
        }

        $validation = $this->validateLatexReferences($latex, $imagePaths);

        // Images manquantes = BLOQUANT (prioritaire, on ne montre pas le warning)
        if (!$validation['valid']) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                $fieldName => '❌ Le LaTeX référence des images qui n\'existent pas : ' . implode(', ', $validation['missing']) . '. Uploadez ces images ou retirez les \\graph{} correspondants.'
            ]);
        }

        // Images inutilisées = WARNING (seulement si pas d'erreur bloquante)
        if (!empty($validation['unused'])) {
            session()->flash('warning', '⚠️ Images uploadées mais non utilisées dans le LaTeX : ' . implode(', ', $validation['unused']));
        }
    }
}
