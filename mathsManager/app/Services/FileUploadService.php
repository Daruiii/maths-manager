<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class FileUploadService
{
    /**
     * Types de fichiers autorisés par contexte
     */
    private const ALLOWED_TYPES = [
        'image' => ['jpeg', 'png', 'jpg', 'gif', 'svg', 'webp'],
        'pdf' => ['pdf'],
        'document' => ['pdf', 'doc', 'docx'],
    ];

    /**
     * Tailles maximales par type (en Ko)
     */
    private const MAX_SIZES = [
        'image' => 2048,  // 2 MB
        'pdf' => 5120,    // 5 MB
        'document' => 10240, // 10 MB
    ];

    /**
     * MIME types autorisés pour vérification sécurisée
     */
    private const ALLOWED_MIMES = [
        'image' => ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/svg+xml', 'image/webp'],
        'pdf' => ['application/pdf'],
        'document' => ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
    ];

    /**
     * Upload un fichier dans le système de storage Laravel
     *
     * @param UploadedFile $file Le fichier uploadé
     * @param string $context Le contexte (exercises, corrections, problems, etc.)
     * @param string $identifier Un identifiant unique (ex: exercise_123)
     * @param string $type Type de fichier (image, pdf, document)
     * @param bool $isPublic Si true, fichier accessible publiquement. Si false, protégé par auth
     * @param string|null $customName Nom personnalisé (optionnel)
     * @return string Le chemin relatif du fichier uploadé
     * @throws \Exception
     */
    public function upload(
        UploadedFile $file,
        string $context,
        string $identifier,
        string $type = 'image',
        bool $isPublic = true,
        ?string $customName = null
    ): string {
        // Validation du type
        $this->validateFileType($file, $type);

        // Validation de la taille
        $this->validateFileSize($file, $type);

        // Validation du MIME type réel
        $this->validateMimeType($file, $type);

        // Génération du nom de fichier sécurisé
        $fileName = $this->generateSecureFileName($file, $customName);

        // Construction du chemin
        $disk = $isPublic ? 'public' : 'private';
        $directory = $this->buildDirectory($context, $identifier);

        // Upload du fichier
        try {
            $path = $file->storeAs($directory, $fileName, $disk);

            Log::info("File uploaded successfully", [
                'context' => $context,
                'identifier' => $identifier,
                'filename' => $fileName,
                'disk' => $disk,
                'path' => $path,
            ]);

            return $path;
        } catch (\Exception $e) {
            Log::error("File upload failed", [
                'context' => $context,
                'identifier' => $identifier,
                'error' => $e->getMessage(),
            ]);
            throw new \Exception("Échec de l'upload du fichier : " . $e->getMessage());
        }
    }

    /**
     * Upload plusieurs fichiers
     *
     * @param array $files Tableau de UploadedFile
     * @param string $context
     * @param string $identifier
     * @param string $type
     * @param bool $isPublic
     * @param string|null $prefix Préfixe pour les noms (ex: "statement_", "solution_")
     * @return array Tableau des chemins uploadés
     */
    public function uploadMultiple(
        array $files,
        string $context,
        string $identifier,
        string $type = 'image',
        bool $isPublic = true,
        ?string $prefix = null
    ): array {
        $uploadedPaths = [];

        foreach ($files as $index => $file) {
            $customName = $prefix ? $prefix . ($index + 1) : null;
            $uploadedPaths[] = $this->upload($file, $context, $identifier, $type, $isPublic, $customName);
        }

        return $uploadedPaths;
    }

    /**
     * Supprimer un fichier
     *
     * @param string $path Chemin relatif du fichier
     * @param bool $isPublic Si le fichier est public ou privé
     * @return bool
     */
    public function delete(string $path, bool $isPublic = true): bool
    {
        $disk = $isPublic ? 'public' : 'private';

        try {
            if (Storage::disk($disk)->exists($path)) {
                Storage::disk($disk)->delete($path);

                Log::info("File deleted successfully", [
                    'path' => $path,
                    'disk' => $disk,
                ]);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("File deletion failed", [
                'path' => $path,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Supprimer plusieurs fichiers
     *
     * @param array $paths Tableau de chemins
     * @param bool $isPublic
     * @return int Nombre de fichiers supprimés
     */
    public function deleteMultiple(array $paths, bool $isPublic = true): int
    {
        $deletedCount = 0;

        foreach ($paths as $path) {
            if ($this->delete($path, $isPublic)) {
                $deletedCount++;
            }
        }

        return $deletedCount;
    }

    /**
     * Supprimer tout un dossier
     *
     * @param string $context
     * @param string $identifier
     * @param bool $isPublic
     * @return bool
     */
    public function deleteDirectory(string $context, string $identifier, bool $isPublic = true): bool
    {
        $disk = $isPublic ? 'public' : 'private';
        $directory = $this->buildDirectory($context, $identifier);

        try {
            if (Storage::disk($disk)->exists($directory)) {
                Storage::disk($disk)->deleteDirectory($directory);

                Log::info("Directory deleted successfully", [
                    'context' => $context,
                    'identifier' => $identifier,
                    'disk' => $disk,
                ]);

                return true;
            }

            return false;
        } catch (\Exception $e) {
            Log::error("Directory deletion failed", [
                'context' => $context,
                'identifier' => $identifier,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }

    /**
     * Obtenir l'URL publique d'un fichier
     *
     * @param string $path Chemin relatif
     * @return string
     */
    public function getPublicUrl(string $path): string
    {
        return Storage::disk('public')->url($path);
    }

    /**
     * Obtenir tous les fichiers d'un contexte/identifier
     *
     * @param string $context
     * @param string $identifier
     * @param bool $isPublic
     * @param string|null $pattern Pattern de filtre (ex: "statement_*")
     * @return array
     */
    public function getFiles(string $context, string $identifier, bool $isPublic = true, ?string $pattern = null): array
    {
        $disk = $isPublic ? 'public' : 'private';
        $directory = $this->buildDirectory($context, $identifier);

        if (!Storage::disk($disk)->exists($directory)) {
            return [];
        }

        $files = Storage::disk($disk)->files($directory);

        if ($pattern) {
            $files = array_filter($files, function($file) use ($pattern) {
                return Str::is($pattern, basename($file));
            });
        }

        return array_values($files);
    }

    /**
     * Valider le type de fichier
     */
    private function validateFileType(UploadedFile $file, string $type): void
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if (!isset(self::ALLOWED_TYPES[$type])) {
            throw new \Exception("Type de fichier non reconnu: {$type}");
        }

        if (!in_array($extension, self::ALLOWED_TYPES[$type])) {
            throw new \Exception("Extension de fichier non autorisée: {$extension}");
        }
    }

    /**
     * Valider la taille du fichier
     */
    private function validateFileSize(UploadedFile $file, string $type): void
    {
        $maxSize = self::MAX_SIZES[$type] ?? 2048;
        $fileSize = $file->getSize() / 1024; // Convertir en Ko

        if ($fileSize > $maxSize) {
            throw new \Exception("Fichier trop volumineux. Maximum: {$maxSize}Ko");
        }
    }

    /**
     * Valider le MIME type réel du fichier
     */
    private function validateMimeType(UploadedFile $file, string $type): void
    {
        $mimeType = $file->getMimeType();

        if (!isset(self::ALLOWED_MIMES[$type])) {
            throw new \Exception("Type MIME non reconnu: {$type}");
        }

        if (!in_array($mimeType, self::ALLOWED_MIMES[$type])) {
            throw new \Exception("Type MIME non autorisé: {$mimeType}");
        }
    }

    /**
     * Générer un nom de fichier sécurisé
     */
    private function generateSecureFileName(UploadedFile $file, ?string $customName = null): string
    {
        $extension = strtolower($file->getClientOriginalExtension());

        if ($customName) {
            // Sanitize le nom personnalisé
            $sanitized = Str::slug(pathinfo($customName, PATHINFO_FILENAME));
            return $sanitized . '.' . $extension;
        }

        // Génère un nom unique basé sur le timestamp et un hash
        return time() . '_' . Str::random(10) . '.' . $extension;
    }

    /**
     * Construire le chemin du répertoire
     */
    private function buildDirectory(string $context, string $identifier): string
    {
        // Sanitize context et identifier
        $context = Str::slug($context);
        $identifier = Str::slug($identifier);

        return "{$context}/{$identifier}";
    }
}
