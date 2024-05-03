<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecapPart extends Model
{
    protected $fillable = ['title', 'description', 'recap_id'];

    public function recap()
    {
        return $this->belongsTo(Recap::class);
    }

    public function recapPartBlocks()
    {
        return $this->hasMany(RecapPartBlock::class);
    }
    use HasFactory;
}
