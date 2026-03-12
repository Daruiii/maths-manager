<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['subchapter_id', 'name', 'statement', 'solution', 'clue', 'latex_statement', 'latex_solution', 'latex_clue', 'difficulty', 'order', 'is_hidden'];

    protected $casts = [
        'is_hidden' => 'boolean',
    ];

    /**
     * Scope pour exclure les exercices masqués
     * Utilisé pour students et teachers
     */
    public function scopeVisible($query)
    {
        return $query->where('is_hidden', false);
    }

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class);
    }

    public function tds()
    {
        return $this->belongsToMany(Td::class, 'td_exercise', 'exercise_id', 'td_id');
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
    
    public function whitelistRequests()
    {
        return $this->hasMany(WhitelistRequest::class);
    }
    
    public function pendingWhitelistRequests()
    {
        return $this->whitelistRequests()->pending();
    }
    
    public function hasWhitelistRequest($userId)
    {
        return $this->whitelistRequests()
            ->where('user_id', $userId)
            ->where('status', 'pending') // ✅ Only check pending requests, not rejected ones
            ->exists();
    }
    
    public function hasRejectedWhitelistRequest($userId)
    {
        return $this->whitelistRequests()
            ->where('user_id', $userId)
            ->where('status', 'rejected')
            ->exists();
    }
}
