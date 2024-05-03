<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recap extends Model
{
    protected $fillable = ['chapter_id', 'title'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function recapParts()
    {
        return $this->hasMany(RecapPart::class);
    }
    use HasFactory;
}
