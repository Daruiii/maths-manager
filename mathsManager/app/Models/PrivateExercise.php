<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateExercise extends Model
{
    protected $fillable = [
        'teacher_id',
        'classe_id',
        'chapter_id',
        'subchapter_id',
        'type',
        'name',
        'notes',
        'latex_statement',
        'latex_solution',
        'latex_clue',
        'difficulty',
        'time',
    ];

    protected $casts = [
        'difficulty' => 'integer',
        'time'       => 'integer',
    ];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'classe_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class, 'subchapter_id');
    }

    public function tags()
    {
        return $this->belongsToMany(
            TeacherTag::class,
            'private_exercise_teacher_tag',
            'private_exercise_id',
            'teacher_tag_id'
        );
    }

    public function ds()
    {
        return $this->belongsToMany(DS::class, 'ds_private_exercises', 'private_exercise_id', 'ds_id');
    }

    public function scopeForTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}
