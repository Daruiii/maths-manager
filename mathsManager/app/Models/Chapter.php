<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title', 'description', 'theme', 'order'];

    public function classe()
    {
        return $this->belongsTo(Classe::class, 'class_id');
    }

    public function subchapters()
    {
        return $this->hasMany(Subchapter::class);
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'chapter_problem', 'chapter_id', 'problem_id');
    }

    public function recaps()
    {
        return $this->hasMany(Recap::class);
    }

    public function quizzQuestions()
    {
        return $this->hasMany(QuizzQuestion::class);
    }

    public function tds()
    {
        return $this->hasMany(Td::class);
    }
}
