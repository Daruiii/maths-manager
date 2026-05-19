<?php

namespace App\Observers;

use App\Models\PrivateExercise;
use App\Services\FileUploadService;

// TODO: même chose pour les exercises et problems il me semble, pour supprimer les images associées

class PrivateExerciseObserver
{
    public function __construct(private FileUploadService $fileUploadService) {}

    public function deleted(PrivateExercise $exercise): void
    {
        $this->fileUploadService->deleteDirectory(
            'private-exercises',
            'private-exercise-' . $exercise->id
        );
    }
}
