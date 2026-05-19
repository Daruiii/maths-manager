<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PrivateFileController extends Controller
{
    /**
     * Servir un fichier privé après vérification des droits
     *
     * @param Request $request
     * @param string $context (corrections, etc.)
     * @param string $identifier
     * @param string $filename
     * @return StreamedResponse
     */
    public function serve(Request $request, string $context, string $identifier, string $filename)
    {
        // Vérifier que l'utilisateur est authentifié
        if (!Auth::check()) {
            abort(401, 'Unauthorized');
        }

        $user = Auth::user();

        // Protection contre path traversal
        $filename = basename($filename);
        if (str_contains($identifier, '..') || str_contains($context, '..')) {
            abort(400, 'Invalid path');
        }

        $filePath = "{$context}/{$identifier}/{$filename}";

        // Vérification des droits selon le contexte
        if (!$this->canAccessFile($user, $context, $identifier)) {
            Log::warning('Unauthorized file access attempt', [
                'user_id' => $user->id,
                'file_path' => $filePath,
            ]);
            abort(403, 'Forbidden');
        }

        // Vérifier que le fichier existe
        if (!Storage::disk('private')->exists($filePath)) {
            abort(404, 'File not found');
        }

        // Log de l'accès
        Log::info('Private file accessed', [
            'user_id' => $user->id,
            'file_path' => $filePath,
        ]);

        // Servir le fichier
        return Storage::disk('private')->response($filePath);
    }

    /**
     * Vérifier si l'utilisateur peut accéder au fichier
     *
     * @param \App\Models\User $user
     * @param string $context
     * @param string $identifier
     * @return bool
     */
    private function canAccessFile($user, string $context, string $identifier): bool
    {
        // Admin et teachers ont accès à tout
        if (in_array($user->role, ['admin', 'teacher'])) {
            return true;
        }

        // Pour les corrections, vérifier que le fichier appartient bien à l'élève
        if ($context === 'corrections') {
            // Copie élève ou correction prof d'un DM
            if (str_starts_with($identifier, 'student-dm-') || str_starts_with($identifier, 'teacher-dm-')) {
                $dmId = (int) substr($identifier, strrpos($identifier, '-') + 1);
                $dm = \App\Models\Dm::find($dmId);
                return $dm && $dm->user_id === $user->id;
            }

            // Correction prof d'un DS (teacher-ds-{id}) ou ancien chemin ds-{id}
            $dsId = (int) preg_replace('/[^0-9]/', '', $identifier);
            $ds = \App\Models\DS::find($dsId);
            return $ds && $ds->user_id === $user->id;
        }

        // Par défaut, refuser l'accès
        return false;
    }
}
