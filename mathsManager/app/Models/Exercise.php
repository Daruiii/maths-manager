<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['subchapter_id', 'name', 'statement', 'solution', 'clue', 'latex_statement', 'latex_solution', 'latex_clue', 'difficulty'];

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class);
    }
}
