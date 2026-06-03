<?php

namespace App\Policies;

use App\Models\Submission;
use App\Models\User;

class SubmissionPolicy
{
    public function grade(User $user, Submission $submission): bool
    {
        return $user->id === $submission->assignment->teacher_id || $user->isAdmin();
    }
}
