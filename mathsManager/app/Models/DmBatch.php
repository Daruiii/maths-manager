<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DmBatch extends Model
{
    protected $fillable = [
        'teacher_id',
        'group_ids',
        'student_ids',
        'dm_count',
        'due_date',
        'is_archived',
    ];

    protected $casts = [
        'group_ids'   => 'array',
        'student_ids' => 'array',
        'due_date'    => 'date',
        'is_archived' => 'boolean',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function dms(): HasMany
    {
        return $this->hasMany(Dm::class, 'batch_id');
    }
}
