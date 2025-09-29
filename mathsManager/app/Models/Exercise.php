<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['subchapter_id', 'name', 'statement', 'solution', 'clue', 'latex_statement', 'latex_solution', 'latex_clue', 'difficulty', 'order'];

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class);
    }

    public function exercisesSheets()
    {
        return $this->belongsToMany(ExercisesSheet::class, 'exercises_sheet_exercises');
    }
    
    public function whitelist()
    {
        return $this->hasMany(ExerciseWhitelist::class);
    }
    
    public function whitelistedUsers()
    {
        return $this->belongsToMany(User::class, 'exercise_whitelist');
    }
    
    public function isWhitelisted($userId)
    {
        return $this->whitelist()->where('user_id', $userId)->exists();
    }
}
