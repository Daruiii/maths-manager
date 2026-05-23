<?php

namespace App\Notifications;

use App\Models\CorrectionRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class TeacherUpdatedCorrection extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly CorrectionRequest $correctionRequest) {}

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        $type = $this->correctionRequest->ds_id ? 'ds' : 'dm';
        $title = $type === 'ds'
            ? $this->correctionRequest->ds->custom_title ?? 'DS'
            : $this->correctionRequest->dm->custom_title ?? 'DM';

        $link = $type === 'ds'
            ? route('ds.show', $this->correctionRequest->ds_id)
            : route('dm.show', $this->correctionRequest->dm_id);

        return [
            'type'          => 'correction_updated',
            'subject_type'  => $type,
            'correction_id' => $this->correctionRequest->id,
            'ds_id'         => $this->correctionRequest->ds_id,
            'dm_id'         => $this->correctionRequest->dm_id,
            'grade'         => $this->correctionRequest->grade,
            'title'         => $title,
            'message'       => 'Votre correction pour "' . $title . '" a été mise à jour.',
            'link'          => $link,
        ];
    }
}
