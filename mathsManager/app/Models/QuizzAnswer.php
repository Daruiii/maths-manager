<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzAnswer extends Model
{
    use HasFactory;

    protected $fillable = ['answer', 'latex_answer', 'is_correct', 'quizz_question_id'];

    public function question()
    {
        return $this->hasOne(QuizzQuestion::class, 'id', 'quizz_question_id');
    }

}
