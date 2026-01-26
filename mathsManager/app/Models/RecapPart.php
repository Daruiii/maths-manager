<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RecapPart extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'recap_id', 'order'];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('order', function ($builder) {
            $builder->orderBy('order', 'asc');
        });
    }

    public function recap()
    {
        return $this->belongsTo(Recap::class);
    }

    public function recapPartBlocks()
    {
        return $this->hasMany(RecapPartBlock::class)->orderBy('order', 'asc');
    }
}
