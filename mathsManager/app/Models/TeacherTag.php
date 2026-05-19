<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeacherTag extends Model
{
    protected $fillable = ['teacher_id', 'name', 'color'];

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function privateExercises()
    {
        return $this->belongsToMany(
            PrivateExercise::class,
            'private_exercise_teacher_tag',
            'teacher_tag_id',
            'private_exercise_id'
        );
    }
}
