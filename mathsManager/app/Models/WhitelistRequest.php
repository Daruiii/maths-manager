<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhitelistRequest extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'exercise_id',
        'status',
        'message',
        'admin_response',
        'processed_by',
        'processed_at',
    ];
    
    protected $casts = [
        'processed_at' => 'datetime',
    ];
    
    // Statuts possibles
    const STATUS_PENDING = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    
    public function processedBy()
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
    
    // Scopes utiles
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    public function scopeProcessed($query)
    {
        return $query->whereIn('status', [self::STATUS_APPROVED, self::STATUS_REJECTED]);
    }
    
    // Méthodes helper
    public function isPending()
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    public function isApproved()
    {
        return $this->status === self::STATUS_APPROVED;
    }
    
    public function isRejected()
    {
        return $this->status === self::STATUS_REJECTED;
    }
}
