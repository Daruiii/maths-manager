<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subchapter extends Model
{
    use HasFactory;

    protected $fillable = ['chapter_id', 'title', 'description', 'order'];

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function exercises()
    {
        return $this->hasMany(Exercise::class);
    }
}
