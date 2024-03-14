<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chapter extends Model
{
    use HasFactory;

    protected $fillable = ['class_id', 'title', 'description'];

    public function classe()
    {
        return $this->belongsTo(Classe::class);
    }

    public function subchapters()
    {
        return $this->hasMany(Subchapter::class);
    }
}
