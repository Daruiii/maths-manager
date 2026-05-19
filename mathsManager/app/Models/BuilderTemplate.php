<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuilderTemplate extends Model
{
    protected $fillable = ['teacher_id', 'type', 'name', 'student_group_id', 'payload'];

    protected $casts = [
        'payload' => 'array',
    ];

    public function scopeForTeacher(Builder $query, int $teacherId): Builder
    {
        return $query->where('teacher_id', $teacherId);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function studentGroup(): BelongsTo
    {
        return $this->belongsTo(StudentGroup::class, 'student_group_id');
    }
}
