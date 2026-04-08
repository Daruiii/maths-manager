<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Td extends Model
{
    use HasFactory;

    protected $table = 'td';

    protected $fillable = [
        'teacher_id',
        'user_id',
        'batch_id',
        'custom_title',
        'custom_level',
        'custom_instructions',
        'correction_unlocked',
    ];

    protected $casts = [
        'correction_unlocked' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(TdBatch::class, 'batch_id');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'td_exercise', 'td_id', 'exercise_id');
    }

    public function privateExercises(): BelongsToMany
    {
        return $this->belongsToMany(PrivateExercise::class, 'td_private_exercises', 'td_id', 'private_exercise_id');
    }
}
