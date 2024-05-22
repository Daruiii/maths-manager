<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzDetail extends Model
{
    use HasFactory;
    protected $fillable = ['quizz_id', 'question_id', 'chosen_answer_id'];

    public function quizz()
    {
        return $this->belongsTo(Quizze::class, 'quizz_id');
    }

    public function question()
    {
        return $this->belongsTo(QuizzQuestion::class, 'question_id');
    }

    public function chosenAnswer()
    {
        return $this->belongsTo(QuizzAnswer::class, 'chosen_answer_id');
    }
}
