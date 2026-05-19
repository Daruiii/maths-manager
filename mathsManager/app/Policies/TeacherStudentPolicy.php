<?php

namespace App\Policies;

use App\Models\User;

class TeacherStudentPolicy
{
    /**
     * Un prof peut retirer un élève uniquement si c'est le sien.
     */
    public function remove(User $user, User $student): bool
    {
        return $student->teacher_id === $user->id;
    }

    /**
     * Un prof peut changer le groupe d'un élève uniquement si c'est le sien.
     */
    public function updateGroup(User $user, User $student): bool
    {
        return $student->teacher_id === $user->id;
    }
}
