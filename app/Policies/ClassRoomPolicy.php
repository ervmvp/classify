<?php

namespace App\Policies;

use App\Models\ClassRoom;
use App\Models\User;

class ClassRoomPolicy
{
    public function view(User $user, ClassRoom $classroom): bool
    {
        return $classroom->teacher_id === $user->id;
    }

    public function update(User $user, ClassRoom $classroom): bool
    {
        return $classroom->teacher_id === $user->id;
    }

    public function delete(User $user, ClassRoom $classroom): bool
    {
        return $classroom->teacher_id === $user->id;
    }
}