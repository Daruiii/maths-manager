<?php

namespace App\Policies;

use App\Models\TeacherTag;
use App\Models\User;

class TeacherTagPolicy
{
    public function create(User $user): bool
    {
        return $user->canActAsTeacher();
    }

    public function update(User $user, TeacherTag $tag): bool
    {
        return $tag->teacher_id === $user->id;
    }

    public function delete(User $user, TeacherTag $tag): bool
    {
        return $tag->teacher_id === $user->id;
    }
}
