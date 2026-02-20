<?php

namespace App\Policies;

use App\Models\DS;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class DSPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Admins et teachers peuvent voir tous les DS
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
            return true;
        }

        // Les étudiants peuvent voir la liste uniquement s'ils ont un prof assigné
        return $user->role === User::ROLE_STUDENT && $user->teacher_id !== null;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, DS $dS): bool
    {
        // Admins et teachers peuvent voir tous les DS
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
            return true;
        }

        // Les étudiants ne peuvent voir que leurs propres DS
        return $dS->user_id === $user->id;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les admins et teachers peuvent créer/assigner des DS
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, DS $dS): bool
    {
        // Admins peuvent modifier tous les DS
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }

        // Le propriétaire peut modifier son propre DS
        return $dS->user_id === $user->id;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, DS $dS): bool
    {
        // Admins peuvent supprimer tous les DS
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }

        // Le propriétaire peut supprimer son propre DS
        return $dS->user_id === $user->id;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, DS $dS): bool
    {
        // Seuls les admins peuvent restaurer des DS
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, DS $dS): bool
    {
        // Seuls les admins peuvent supprimer définitivement des DS
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can start, pause, or finish a DS.
     */
    public function manage(User $user, DS $dS): bool
    {
        // Seul le propriétaire peut gérer (start/pause/finish) son DS
        return $dS->user_id === $user->id;
    }

    /**
     * Determine whether the user can assign a DS to another user.
     */
    public function assign(User $user): bool
    {
        // Seuls les admins peuvent assigner des DS à d'autres utilisateurs
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can generate unlimited DS.
     */
    public function generateUnlimited(User $user): bool
    {
        // Admins et teachers peuvent générer sans limite
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }
}
