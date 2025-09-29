<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Classe extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'level', 'hidden', 'display_order'];

    public function chapters()
    {
        return $this->hasMany(Chapter::class, 'class_id');
    }
}
