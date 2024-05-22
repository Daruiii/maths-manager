<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quizze extends Model
{
    use HasFactory;
    protected $fillable = ['student_id', 'score', 'started_at', 'finished_at', 'chapter_id']; 

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function details()
    {
        return $this->hasMany(QuizzDetail::class, 'quizz_id');
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }
}
