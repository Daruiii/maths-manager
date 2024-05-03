<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecapPartBlock extends Model
{
    protected $fillable = ['recap_part_id', 'title', 'theme', 'content', 'latex-content'];

    public function recapPart()
    {
        return $this->belongsTo(RecapPart::class);
    }
    use HasFactory;
}
