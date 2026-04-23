<?php

namespace App\Models;

use App\Enums\DmStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    protected $casts = [
        'status' => DmStatus::class,
    ];

    public function isNotStarted(): bool
    {
        return $this->status === DmStatus::NotStarted;
    }

    public function isOngoing(): bool
    {
        return $this->status === DmStatus::Ongoing;
    }

    public function isFinished(): bool
    {
        return $this->status === DmStatus::Finished;
    }

    public function isCorrected(): bool
    {
        return $this->status === DmStatus::Corrected;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(DmBatch::class, 'batch_id');
    }

    public function problems(): BelongsToMany
    {
        return $this->belongsToMany(Problem::class, 'dm_problem', 'dm_id', 'problem_id');
    }

    public function exercises(): BelongsToMany
    {
        return $this->belongsToMany(Exercise::class, 'dm_exercise', 'dm_id', 'exercise_id');
    }

    public function privateExercises(): BelongsToMany
    {
        return $this->belongsToMany(PrivateExercise::class, 'dm_private_exercise', 'dm_id', 'private_exercise_id');
    }

    public function correctionRequest(): HasOne
    {
        return $this->hasOne(CorrectionRequest::class, 'dm_id');
    }
}
