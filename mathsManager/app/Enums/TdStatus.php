<?php

namespace App\Enums;

enum TdStatus: string
{
    case NotStarted = 'not_started';
    case Ongoing = 'ongoing';
    case CorrectionRequested = 'correction_requested';
    case CorrectionUnlocked = 'correction_unlocked';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted         => 'Non commencé',
            self::Ongoing            => 'En cours',
            self::CorrectionRequested => 'Correction demandée',
            self::CorrectionUnlocked  => 'Correction débloquée',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::NotStarted, self::Ongoing]);
    }
}
