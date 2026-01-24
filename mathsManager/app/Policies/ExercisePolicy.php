<?php

namespace App\Policies;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ExercisePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir la liste des exercices
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Exercise $exercise): bool
    {
        // Tous les utilisateurs authentifiés peuvent voir un exercice
        return true;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        // Seuls les admins peuvent créer des exercices
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Exercise $exercise): bool
    {
        // Seuls les admins peuvent modifier des exercices
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Exercise $exercise): bool
    {
        // Seuls les admins peuvent supprimer des exercices
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Exercise $exercise): bool
    {
        // Seuls les admins peuvent restaurer des exercices
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Exercise $exercise): bool
    {
        // Seuls les admins peuvent supprimer définitivement des exercices
        return $user->role === User::ROLE_ADMIN;
    }

    /**
     * Determine whether the user can view the solution of an exercise.
     */
    public function viewSolution(User $user, Exercise $exercise): bool
    {
        // Admins et teachers peuvent toujours voir les solutions
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
            return true;
        }

        // Les étudiants ne peuvent voir que les solutions des exercices whitelistés
        return $exercise->isWhitelisted($user->id);
    }

    /**
     * Determine whether the user can manage the whitelist for an exercise.
     */
    public function manageWhitelist(User $user, Exercise $exercise): bool
    {
        // Seuls les admins peuvent gérer la whitelist
        return $user->role === User::ROLE_ADMIN;
    }
}
