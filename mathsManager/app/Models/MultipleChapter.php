<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MultipleChapter extends Model
{
    use HasFactory;
    protected $fillable = ['title', 'description', 'theme', 'classe_id'];

    public function problems()
    {
        return $this->hasMany(Problem::class);
    }

    public function ds()
    {
        return $this->belongsToMany(DS::class, 'ds_multiple_chapters', 'multiple_chapter_id', 'ds_id');
    }

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }
}
