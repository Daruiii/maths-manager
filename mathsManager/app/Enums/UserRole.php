<?php

namespace App\Enums;

enum UserRole: string
{
    case Admin = 'admin';
    case Teacher = 'teacher';
    case Student = 'student';

    public function label(): string
    {
        return match ($this) {
            self::Admin => 'Administrateur',
            self::Teacher => 'Professeur',
            self::Student => 'Étudiant',
        };
    }

    public function isPrivileged(): bool
    {
        return in_array($this, [self::Admin, self::Teacher]);
    }
}
