<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DSChapter extends Model
{
    use HasFactory;
    protected $fillable = ['ds_id', 'chapter_id'];

    public function ds()
    {
        return $this->belongsTo(DS::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }
}
