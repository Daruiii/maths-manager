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
        return $this->hasOne(Chapter::class, 'id', 'chapter_id');
    }

    public function subchapter()
    {
        return $this->hasOne(Subchapter::class, 'id', 'subchapter_id');
    }

    public function answers()
    {
        return $this->hasMany(QuizzAnswer::class);
    }

}
