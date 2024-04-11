<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DS extends Model
{
    use HasFactory;
    protected $table = 'DS';
    protected $fillable = [
        'type_bac', 'exercises_number', 'harder_exercises', 'time', 'timer', 'chrono', 'status'
    ];

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'ds_chapter', 'ds_id', 'chapter_id');
    }

    public function exercisesDS()
    {
        return $this->belongsToMany(DsExercise::class, 'ds_exercises_ds', 'ds_id', 'ds_exercise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // public function correctionRequests()
    // {
    //     return $this->hasMany(CorrectionRequest::class);
    // }
}
