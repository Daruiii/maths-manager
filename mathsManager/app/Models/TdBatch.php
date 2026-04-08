<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TdBatch extends Model
{
    protected $fillable = [
        'teacher_id',
        'group_ids',
        'student_ids',
        'td_count',
    ];

    protected $casts = [
        'group_ids'   => 'array',
        'student_ids' => 'array',
    ];

    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function tds(): HasMany
    {
        return $this->hasMany(Td::class, 'batch_id');
    }
}
