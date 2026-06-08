<?php

namespace App\Policies;

use App\Models\Assignment;
use App\Models\User;

class AssignmentPolicy
{
    public function view(User $user, Assignment $assignment): bool
    {
        return $assignment->teacher_id === $user->id;
    }

    public function update(User $user, Assignment $assignment): bool
    {
        return $assignment->teacher_id === $user->id;
    }

    public function delete(User $user, Assignment $assignment): bool
    {
        return $assignment->teacher_id === $user->id;
    }
}