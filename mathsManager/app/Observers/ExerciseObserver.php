<?php

namespace App\Observers;

use App\Models\Exercise;
use App\Services\FileUploadService;

class ExerciseObserver
{
    public function __construct(private FileUploadService $fileUploadService) {}

    public function deleted(Exercise $exercise): void
    {
        $this->fileUploadService->deleteDirectory(
            'exercises',
            'exercise-' . $exercise->id
        );
    }
}
