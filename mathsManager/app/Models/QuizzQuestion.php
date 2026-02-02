<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuizzQuestion extends Model
{
    use HasFactory;
    
    protected $fillable = ['question', 'latex_question', 'explanation', 'latex_explanation', 'chapter_id', 'subchapter_id'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class, 'chapter_id');
    }

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class, 'subchapter_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizzAnswer::class);
    }

}
