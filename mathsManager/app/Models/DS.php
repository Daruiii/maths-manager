<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DS extends Model
{
    use HasFactory;
    protected $fillable = [
        'type_bac', 'exercises_number', 'harder_exercises', 'time', 'timer', 'chrono', 'status'
    ];

    public function chapters()
    {
        return $this->belongsToMany(Chapter::class, 'ds_chapter', 'ds_id', 'chapter_id');
    }

    // public function correctionRequests()
    // {
    //     return $this->hasMany(CorrectionRequest::class);
    // }
}
