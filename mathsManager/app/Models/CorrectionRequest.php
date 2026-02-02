<?php

namespace App\Models;

use App\Enums\CorrectionRequestStatus;
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

    protected $casts = [
        'pictures' => 'array',
        'correction_pictures' => 'array',
    ];

    public function isPending(): bool
    {
        return $this->status === CorrectionRequestStatus::Pending->value;
    }

    public function isCorrected(): bool
    {
        return $this->status === CorrectionRequestStatus::Corrected->value;
    }

    public function isRefused(): bool
    {
        return $this->status === CorrectionRequestStatus::Refused->value;
    }

    public function getStatusEnum(): CorrectionRequestStatus
    {
        return CorrectionRequestStatus::from($this->status);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function ds()
    {
        return $this->belongsTo(DS::class);
    }
}
