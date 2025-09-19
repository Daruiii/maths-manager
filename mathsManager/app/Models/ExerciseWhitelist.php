<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExerciseWhitelist extends Model
{
    use HasFactory;
    
    protected $table = 'exercise_whitelist';
    
    protected $fillable = ['exercise_id', 'user_id'];
    
    public function exercise()
    {
        return $this->belongsTo(Exercise::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
