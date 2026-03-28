<?php

namespace App\Models;

use App\Enums\DSStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DS extends Model
{
    use HasFactory;
    protected $table = 'ds';
    protected $fillable = [
        'type_bac', 'exercises_number', 'harder_exercises', 'time', 'timer', 'chrono', 'status', 'teacher_id'
    ];

    public function isNotStarted(): bool
    {
        return $this->status === DSStatus::NotStarted->value;
    }

    public function isOngoing(): bool
    {
        return $this->status === DSStatus::Ongoing->value;
    }

    public function isFinished(): bool
    {
        return $this->status === DSStatus::Finished->value;
    }

    public function isCorrected(): bool
    {
        return $this->status === DSStatus::Corrected->value;
    }

    public function isActive(): bool
    {
        return in_array($this->status, [DSStatus::NotStarted->value, DSStatus::Ongoing->value]);
    }

    public function getStatusEnum(): DSStatus
    {
        return DSStatus::from($this->status);
    }

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'ds_chapter', 'ds_id', 'chapter_id');
    }

    public function multipleChapters()
    {
        return $this->belongsToMany(MultipleChapter::class, 'ds_multiple_chapters', 'ds_id', 'multiple_chapter_id');
    }

    public function problems()
    {
        return $this->belongsToMany(Problem::class, 'ds_problem', 'ds_id', 'problem_id');
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'ds_exercises', 'ds_id', 'exercise_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function correctionRequest()
    {
        return $this->hasOne(CorrectionRequest::class, 'ds_id');
    }
}
