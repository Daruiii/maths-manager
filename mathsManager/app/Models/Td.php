<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Td extends Model
{
    use HasFactory;

    protected $table = 'td';

    protected $fillable = [
        'user_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function exercises()
    {
        return $this->belongsToMany(Exercise::class, 'td_exercise', 'td_id', 'exercise_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
