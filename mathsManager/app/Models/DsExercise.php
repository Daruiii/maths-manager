<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DsExercise extends Model
{
    use HasFactory;
    protected $fillable = [
        'header', 'multiple_chapter_id', 'harder_exercise', 'time', 'name', 'statement', 'latex_statement'
    ];

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'chapters_exercises_ds', 'exercise_ds_id', 'chapter_id');
    }

    public function ds()
    {
        return $this->belongsToMany(DS::class, 'ds_exercises_ds', 'ds_exercise_id', 'ds_id');
    }

    public function multipleChapter()
    {
        return $this->belongsTo(MultipleChapter::class)->onDelete('cascade');
    }
}
