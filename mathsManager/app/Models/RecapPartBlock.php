<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecapPartBlock extends Model
{
    use HasFactory;

    protected $fillable = ['recap_part_id', 'title', 'theme', 'content', 'latex-content', 'example', 'latex_example', 'demonstration', 'latex_demonstration', 'remarque', 'latex_remarque', 'subchapter_id', 'order'];

    public function recapPart()
    {
        return $this->belongsTo(RecapPart::class);
    }

    public function subchapter()
    {
        return $this->belongsTo(Subchapter::class);
    }
}
