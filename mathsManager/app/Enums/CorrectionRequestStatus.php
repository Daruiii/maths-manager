<?php

namespace App\Enums;

enum CorrectionRequestStatus: string
{
    case Pending = 'pending';
    case Corrected = 'corrected';
    case Refused = 'refused';

    public function label(): string
    {
        return match ($this) {
            self::Pending => 'En attente',
            self::Corrected => 'Corrigé',
            self::Refused => 'Refusé',
        };
    }
}
