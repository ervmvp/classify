<?php

namespace App\Policies;

use App\Models\ClassRoom;
use App\Models\User;

class ClassRoomPolicy
{
    public function update(User $user, ClassRoom $classroom): bool
    {
        return $user->id === $classroom->teacher_id || $user->isAdmin();
    }

    public function delete(User $user, ClassRoom $classroom): bool
    {
        return $user->id === $classroom->teacher_id || $user->isAdmin();
    }
}
