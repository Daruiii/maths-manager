<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title', 'description', 'theme'];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function subchapters()
    {
        return $this->hasMany(Subchapter::class);
    }

    public function quizzes()
    {
        return $this->hasMany(Quizz::class);
    }

    public function dsExercises()
    {
        return $this->belongsToMany(DsExercise::class, 'chapters_exercises_ds', 'chapter_id', 'exercise_ds_id');
    }
}
