<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['assignment_id', 'student_id', 'content', 'grade', 'status', 'submitted_at'])]
class Submission extends Model
{
    use HasFactory;

    protected $casts = [
        'submitted_at' => 'datetime',
        'graded_at' => 'datetime',
    ];

    public function assignment(): BelongsTo
    {
        return $this->belongsTo(Assignment::class);
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(SubmissionComment::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(SubmissionFile::class);
    }

    public function isLate(): bool
    {
        if (!$this->submitted_at) {
            return false;
        }
        return $this->submitted_at->isAfter($this->assignment->due_date);
    }
}
