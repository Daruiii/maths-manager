<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'ds_id',
        'pictures',
        'correction_pictures',
        'correction_message',
        'grade',
        'status',
        'message',
        'correction_pdf',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ds()
    {
        return $this->belongsTo(DS::class);
    }
}
