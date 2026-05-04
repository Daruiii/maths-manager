<?php

namespace App\Notifications;

use App\Models\Td;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherUnlockedTd extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Td $td) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        $title = $this->td->custom_title ?? 'Fiche d\'exercices';

        return [
            'type'         => 'td_unlocked',
            'subject_type' => 'td',
            'td_id'        => $this->td->id,
            'title'        => $title,
            'message'      => 'La correction de "' . $title . '" est maintenant accessible.',
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $title = $this->td->custom_title ?? 'Fiche d\'exercices';

        return (new MailMessage)
            ->subject('Correction disponible — ' . $title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre professeur a débloqué la correction de "' . $title . '".')
            ->action('Voir ma fiche', url('/td/' . $this->td->id))
            ->line('Les solutions des exercices sont maintenant accessibles.');
    }
}
