<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesOrdering;

class Chapter extends Model
{
    use HasFactory, ManagesOrdering;

    protected $fillable = ['class_id', 'title', 'description', 'theme', 'order'];

    public function classe()
    {
        return $this->hasOne(Classe::class, 'id', 'class_id');
    }

    public function subchapters()
    {
        return $this->hasMany(Subchapter::class);
    }

    public function dsExercises()
    {
        return $this->belongsToMany(DsExercise::class, 'chapters_exercises_ds', 'chapter_id', 'exercise_ds_id');
    }

    public function recaps()
    {
        return $this->hasMany(Recap::class);
    }

    public function quizzQuestions()
    {
        return $this->hasMany(QuizzQuestion::class);
    }

    public function exercisesSheets()
    {
        return $this->hasMany(ExercisesSheet::class);
    }
}
