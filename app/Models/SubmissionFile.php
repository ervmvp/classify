<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable(['submission_id', 'file_path', 'file_name', 'mime_type', 'file_size'])]
class SubmissionFile extends Model
{
    use HasFactory;

    public function submission(): BelongsTo
    {
        return $this->belongsTo(Submission::class);
    }
}
