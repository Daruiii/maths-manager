<?php

namespace App\Notifications;

use App\Models\CorrectionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TeacherSentCorrection extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly CorrectionRequest $correctionRequest) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        $type  = $this->correctionRequest->ds_id ? 'ds' : 'dm';
        $title = $type === 'ds'
            ? $this->correctionRequest->ds->custom_title ?? 'DS'
            : $this->correctionRequest->dm->custom_title ?? 'DM';

        return [
            'type'          => 'correction_sent',
            'subject_type'  => $type,
            'correction_id' => $this->correctionRequest->id,
            'ds_id'         => $this->correctionRequest->ds_id,
            'dm_id'         => $this->correctionRequest->dm_id,
            'grade'         => $this->correctionRequest->grade,
            'title'         => $title,
            'message'       => 'Votre correction pour "' . $title . '" est disponible.',
            'link'          => $type === 'ds'
                ? '/ds/' . $this->correctionRequest->ds_id
                : '/dm/' . $this->correctionRequest->dm_id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->toArray($notifiable);

        $mail = (new MailMessage)
            ->subject('Votre correction est disponible')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre professeur a corrigé votre travail pour "' . $data['title'] . '".');

        if ($data['grade'] !== null) {
            $mail->line('Note obtenue : **' . $data['grade'] . '**');
        }

        $path = $this->correctionRequest->dm_id
            ? '/dm/' . $this->correctionRequest->dm_id
            : '/ds/' . $this->correctionRequest->ds_id;

        return $mail
            ->action('Voir ma correction', url($path))
            ->line('Les solutions des exercices sont maintenant accessibles.');
    }
}
