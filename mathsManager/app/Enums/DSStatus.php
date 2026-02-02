<?php

namespace App\Enums;

enum DSStatus: string
{
    case NotStarted = 'not_started';
    case Ongoing = 'ongoing';
    case Finished = 'finished';
    case Sent = 'sent';
    case Corrected = 'corrected';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted => 'Non commencé',
            self::Ongoing => 'En cours',
            self::Finished => 'Terminé',
            self::Sent => 'Envoyé',
            self::Corrected => 'Corrigé',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::NotStarted, self::Ongoing]);
    }
}
