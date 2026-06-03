<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['class_id', 'teacher_id', 'title', 'description', 'due_date', 'total_points'])]
class Assignment extends Model
{
    use HasFactory;

    protected $casts = [
        'due_date' => 'datetime',
    ];

    public function classroom(): BelongsTo
    {
        return $this->belongsTo(ClassRoom::class, 'class_id');
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function submissions(): HasMany
    {
        return $this->hasMany(Submission::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(AssignmentFile::class);
    }

    public function isOverdue(): bool
    {
        return now()->isAfter($this->due_date);
    }
}
