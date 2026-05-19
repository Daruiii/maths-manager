<?php

namespace App\Policies;

use App\Models\Dm;
use App\Models\User;

class DmPolicy
{
    public function viewAny(User $user): bool
    {
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
            return true;
        }

        return $user->role === User::ROLE_STUDENT && $user->teacher_id !== null;
    }

    public function view(User $user, Dm $dm): bool
    {
        if (in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER])) {
            return true;
        }

        return $dm->user_id === $user->id;
    }

    public function create(User $user): bool
    {
        return in_array($user->role, [User::ROLE_ADMIN, User::ROLE_TEACHER]);
    }

    public function update(User $user, Dm $dm): bool
    {
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === User::ROLE_TEACHER) {
            return $dm->teacher_id === $user->id;
        }

        return $dm->user_id === $user->id;
    }

    public function delete(User $user, Dm $dm): bool
    {
        if ($user->role === User::ROLE_ADMIN) {
            return true;
        }

        if ($user->role === User::ROLE_TEACHER) {
            return $dm->teacher_id === $user->id;
        }

        return false;
    }

    public function restore(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function forceDelete(User $user): bool
    {
        return $user->role === User::ROLE_ADMIN;
    }

    public function assign(User $user): bool
    {
        return $user->canActAsTeacher();
    }
}
