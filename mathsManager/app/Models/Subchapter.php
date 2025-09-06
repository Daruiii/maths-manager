<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\ManagesOrdering;

class Subchapter extends Model
{
    use HasFactory, ManagesOrdering;

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
