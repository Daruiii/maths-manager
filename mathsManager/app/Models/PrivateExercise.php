<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrivateExercise extends Model
{
    protected $fillable = [
        'teacher_id',
        'type',
        'name',
        'statement',
        'latex_statement',
        'solution',
        'latex_solution',
        'clue',
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

    public function ds()
    {
        return $this->belongsToMany(DS::class, 'ds_private_exercises', 'private_exercise_id', 'ds_id');
    }

    /**
     * Scope: exercices privés du prof connecté uniquement.
     */
    public function scopeForTeacher($query, int $teacherId)
    {
        return $query->where('teacher_id', $teacherId);
    }
}
