<?php

namespace App\Policies;

use App\Models\StudentGroup;
use App\Models\User;

class StudentGroupPolicy
{
    /**
     * Un prof peut voir la liste de ses groupes.
     */
    public function viewAny(User $user): bool
    {
        return $user->canActAsTeacher() && $user->status === 'active';
    }

    /**
     * Un prof peut créer des groupes.
     */
    public function create(User $user): bool
    {
        return $user->canActAsTeacher() && $user->status === 'active';
    }

    /**
     * Un prof peut modifier uniquement ses propres groupes.
     */
    public function update(User $user, StudentGroup $group): bool
    {
        return $group->teacher_id === $user->id;
    }

    /**
     * Un prof peut supprimer uniquement ses propres groupes.
     */
    public function delete(User $user, StudentGroup $group): bool
    {
        return $group->teacher_id === $user->id;
    }
}
