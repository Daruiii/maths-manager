<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    use HasFactory;

    protected $fillable = ['subchapter_id', 'name', 'statement', 'solution', 'clue'];

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class);
    }
}
