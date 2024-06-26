<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleChapter extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'theme'];

    public function dsExercises()
    {
        return $this->hasMany(DsExercise::class);
    }

    public function ds()
    {
        return $this->belongsToMany(DS::class, 'ds_multiple_chapters', 'multiple_chapter_id', 'ds_id');
    }
}
