<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable(['teacher_id', 'name', 'description', 'subject', 'class_code', 'qr_code_path', 'section', 'room', 'announcement'])]
class ClassRoom extends Model
{
    use HasFactory;

    protected $table = 'classes';

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function students(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'class_student', 'class_id', 'student_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(Assignment::class, 'class_id');
    }

    public function generateClassCode(): string
    {
        return strtoupper(substr(md5(uniqid()), 0, 6));
    }
    public function classes()
    {
         return $this->belongsToMany(ClassRoom::class, 'class_student')
                ->withoutTimestamps();
    }
}
