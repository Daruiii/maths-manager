<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class TeacherAssignedWork extends Notification
{
    use Queueable;

    public function __construct(
        private readonly string $subjectType,
        private readonly int $subjectId,
        private readonly string $title,
    ) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $label = match ($this->subjectType) {
            'ds' => 'DS',
            'dm' => 'DM',
            'td' => 'TD',
            default => 'Travail',
        };

        return [
            'type'         => 'work_assigned',
            'subject_type' => $this->subjectType,
            'subject_id'   => $this->subjectId,
            'title'        => $this->title,
            'message'      => 'Un nouveau ' . $label . ' vous a été assigné : "' . $this->title . '".',
            'link'         => '/' . $this->subjectType . '/' . $this->subjectId,
        ];
    }
}
