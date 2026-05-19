<?php

namespace App\Models;

use App\Enums\CorrectionRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrectionRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'corrector_id',
        'ds_id',
        'dm_id',
        'pictures',
        'correction_pictures',
        'correction_message',
        'grade',
        'status',
        'message',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function ds(): BelongsTo
    {
        return $this->belongsTo(DS::class);
    }

    public function dm(): BelongsTo
    {
        return $this->belongsTo(Dm::class);
    }
}
