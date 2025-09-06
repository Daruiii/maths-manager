<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesOrdering;

class Exercise extends Model
{
    use HasFactory, ManagesOrdering;

    protected $fillable = ['subchapter_id', 'name', 'statement', 'solution', 'clue', 'latex_statement', 'latex_solution', 'latex_clue', 'difficulty', 'order'];

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class);
    }

    public function exercisesSheets()
    {
        return $this->belongsToMany(ExercisesSheet::class, 'exercises_sheet_exercises');
    }
}
