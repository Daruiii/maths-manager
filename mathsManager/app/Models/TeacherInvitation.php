<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TeacherInvitation extends Model
{
    protected $fillable = [
        'teacher_id',
        'group_id',
        'code',
        'expires_at',
        'max_uses',
        'current_uses',
        'is_active',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Get the teacher that owns this invitation.
     */
    public function teacher(): BelongsTo
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    /**
     * Check if the invitation is still valid.
     */
    public function isValid(): bool
    {
        return $this->is_active 
            && $this->expires_at->isFuture() 
            && $this->current_uses < $this->max_uses;
    }

    /**
     * Increment the usage counter.
     */
    public function incrementUses(): void
    {
        $this->increment('current_uses');
    }
}
