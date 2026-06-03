<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function update(User $user, Assignment $assignment): bool
    {
        return $user->id === $assignment->teacher_id || $user->isAdmin();
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $user->id === $assignment->teacher_id || $user->isAdmin();
    }
}
