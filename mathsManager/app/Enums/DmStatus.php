<?php

namespace App\Enums;

enum DmStatus: string
{
    case NotStarted = 'not_started';
    case Ongoing    = 'ongoing';
    case Finished   = 'finished';
    case Corrected  = 'corrected';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Non commencé',
            self::Ongoing    => 'En cours',
            self::Finished   => 'Terminé',
            self::Corrected  => 'Corrigé',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::NotStarted, self::Ongoing]);
    }
}
