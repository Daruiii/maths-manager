<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dm extends Model
{
    use HasFactory;

    protected $table = 'dm';

    protected $fillable = [
        'user_id',
        'teacher_id',
        'batch_id',
        'status',
        'custom_title',
        'custom_level',
        'custom_instructions',
    ];

    public function isNotStarted(): bool
    {
        return $this->status === 'not_started';
    }

    public function isOngoing(): bool
    {
        return $this->status === 'ongoing';
    }

    public function isFinished(): bool
    {
        return $this->status === 'finished';
    }

    public function isCorrected(): bool
    {
        return $this->status === 'corrected';
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'dm_problem', 'dm_id', 'problem_id');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'dm_exercise', 'dm_id', 'exercise_id');
    }

    public function privateExercises()
    {
        return $this->belongsToMany(PrivateExercise::class, 'dm_private_exercise', 'dm_id', 'private_exercise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function batch()
    {
        return $this->belongsTo(DmBatch::class, 'batch_id');
    }
}
