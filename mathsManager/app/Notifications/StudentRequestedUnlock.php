<?php

namespace App\Notifications;

use App\Models\Td;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentRequestedUnlock extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Td $td) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        $student = $this->td->student;
        $title   = $this->td->custom_title ?? 'Fiche d\'exercices';

        return [
            'type'         => 'unlock_requested',
            'subject_type' => 'td',
            'td_id'        => $this->td->id,
            'batch_id'     => $this->td->batch_id,
            'student_name' => $student->name,
            'title'        => $title,
            'message'      => $student->name . ' demande la correction pour "' . $title . '".',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->toArray($notifiable);

        return (new MailMessage)
            ->subject('Demande de correction — ' . $data['title'])
            ->greeting('Bonjour,')
            ->line($data['student_name'] . ' demande à accéder à la correction de "' . $data['title'] . '".')
            ->action('Gérer les corrections', url('/teacher/bureau'))
            ->line('Vous pouvez débloquer la correction pour cet élève ou pour tout le groupe.');
    }
}
