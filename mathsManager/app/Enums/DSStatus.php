<?php

namespace App\Enums;

enum DSStatus: string
{
    case NotStarted = 'not_started';
    case Ongoing = 'ongoing';
    case Paused = 'paused';
    case Finished = 'finished';
    case FinishedLate = 'finished_late';
    case Sent = 'sent';
    case Corrected = 'corrected';

    public function label(): string
    {
        return match ($this) {
            self::NotStarted   => 'Non commencé',
            self::Ongoing      => 'En cours',
            self::Paused       => 'En pause',
            self::Finished     => 'Terminé',
            self::FinishedLate => 'Terminé (en retard)',
            self::Sent         => 'Envoyé',
            self::Corrected    => 'Corrigé',
        };
    }

    public function isActive(): bool
    {
        return in_array($this, [self::NotStarted, self::Ongoing, self::Paused]);
    }
}
