<?php

namespace App\Policies;

use App\Models\PrivateExercise;
use App\Models\User;

class PrivateExercisePolicy
{
    public function viewAny(User $user): bool
    {
        return $user->canActAsTeacher();
    }

    public function view(User $user, PrivateExercise $exercise): bool
    {
        return $exercise->teacher_id === $user->id;
    }

    public function create(User $user): bool
    {
        return $user->canActAsTeacher();
    }

    public function update(User $user, PrivateExercise $exercise): bool
    {
        return $exercise->teacher_id === $user->id;
    }

    public function delete(User $user, PrivateExercise $exercise): bool
    {
        return $exercise->teacher_id === $user->id;
    }
}
