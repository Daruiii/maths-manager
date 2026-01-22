<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

/**
 * Helper pour standardiser les réponses d'erreur dans les contrôleurs.
 * 
 * Patterns unifiés :
 * - Validation errors : throw ValidationException (déjà géré par FormRequests)
 * - Business logic errors : DomainException avec message utilisateur
 * - System errors : Log + message générique utilisateur
 */
class ErrorResponseHelper
{
    /**
     * Gère une erreur de validation manuelle.
     * Note: Préférer FormRequests quand possible.
     */
    public static function validationError(string $field, string $message): never
    {
        throw ValidationException::withMessages([$field => $message]);
    }

    /**
     * Gère une erreur de logique métier (business rules).
     * Exemple: limite quotidienne dépassée, ressource non disponible.
     * 
     * @param string $message Message utilisateur (safe, pas de détails techniques)
     * @param string|null $redirectRoute Route de redirection (null = back())
     */
    public static function businessError(string $message, ?string $redirectRoute = null)
    {
        return redirect($redirectRoute ?? back())
            ->withErrors(['error' => $message]);
    }

    /**
     * Gère une erreur système (exception inattendue).
     * Log les détails techniques, retourne message générique à l'utilisateur.
     * 
     * @param \Exception $exception Exception capturée
     * @param string $context Contexte de l'erreur (ex: "Upload avatar", "Send email")
     */
    public static function systemError(\Exception $exception, string $context = '')
    {
        Log::error(($context ? "$context: " : '') . $exception->getMessage(), [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
            'file' => $exception->getFile(),
            'line' => $exception->getLine(),
            'trace' => $exception->getTraceAsString(),
        ]);

        return back()->withErrors([
            'error' => 'Une erreur est survenue. Veuillez réessayer ou contacter un administrateur.'
        ]);
    }

    /**
     * Gère spécifiquement les erreurs d'envoi d'email.
     * Log l'erreur mais ne bloque pas le flux utilisateur.
     * 
     * @param \Exception $exception Exception mail
     * @param string $context Contexte de l'email (ex: "DS assignment", "Correction notification")
     */
    public static function mailError(\Exception $exception, string $context = 'Email'): void
    {
        Log::error("$context - Mail error: " . $exception->getMessage(), [
            'exception' => get_class($exception),
            'message' => $exception->getMessage(),
        ]);
        // Ne pas exposer l'erreur à l'utilisateur - l'email est secondaire
    }
}
