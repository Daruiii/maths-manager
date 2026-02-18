<?php

namespace App\Policies;

use App\Models\User;

class StudentPolicy
{
    /**
     * Determine if the student can view exercise corrections.
     * Only students with an assigned teacher can view corrections.
     */
    public function viewCorrection(User $user): bool
    {
        return $user->teacher_id !== null;
    }

    /**
     * Determine if the student can create a correction request.
     * Only students with an assigned teacher can request corrections.
     */
    public function createCorrectionRequest(User $user): bool
    {
        return $user->teacher_id !== null;
    }

    /**
     * Determine if the student can start a DS (Devoir Surveillé).
     * Only students with an assigned teacher can take DS.
     */
    public function startDS(User $user): bool
    {
        return $user->teacher_id !== null;
    }

    /**
     * Determine if the student can request whitelist access for an exercise.
     * Only students with an assigned teacher can request whitelist.
     */
    public function requestWhitelist(User $user): bool
    {
        return $user->teacher_id !== null;
    }
}
