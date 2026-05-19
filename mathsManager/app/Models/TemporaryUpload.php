<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TemporaryUpload extends Model
{
    protected $fillable = ['session_id', 'path', 'original_name', 'mime', 'size', 'position'];

    public function session(): BelongsTo
    {
        return $this->belongsTo(TemporaryUploadSession::class, 'session_id');
    }
}
