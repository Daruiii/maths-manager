<?php

namespace App\Notifications;

use App\Models\CorrectionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class StudentSubmittedCorrection extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly CorrectionRequest $correctionRequest) {}

    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    public function toArray(object $notifiable): array
    {
        $student = $this->correctionRequest->user;
        $type    = $this->correctionRequest->ds_id ? 'ds' : 'dm';
        $title   = $type === 'ds'
            ? $this->correctionRequest->ds->custom_title ?? 'DS'
            : $this->correctionRequest->dm->custom_title ?? 'DM';

        return [
            'type'            => 'correction_submitted',
            'subject_type'    => $type,
            'correction_id'   => $this->correctionRequest->id,
            'student_name'    => $student->name,
            'title'           => $title,
            'message'         => $student->name . ' a rendu sa copie pour "' . $title . '".',
            'link'            => '/teacher/corrections/' . $this->correctionRequest->id,
        ];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $data = $this->toArray($notifiable);

        return (new MailMessage)
            ->subject('Demande de correction de ' . $data['student_name'])
            ->greeting('Bonjour,')
            ->line($data['student_name'] . ' a envoyé sa copie pour "' . $data['title'] . '" et attend votre correction.')
            ->action('Voir la demande', url('/teacher/bureau'))
            ->line('Merci de corriger dès que possible.');
    }
}
