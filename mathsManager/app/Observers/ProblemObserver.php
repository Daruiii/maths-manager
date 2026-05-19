<?php

namespace App\Observers;

use App\Models\Problem;
use App\Services\FileUploadService;

class ProblemObserver
{
    public function __construct(private FileUploadService $fileUploadService) {}

    public function deleted(Problem $problem): void
    {
        $this->fileUploadService->deleteDirectory(
            'problems',
            'problem-' . $problem->id
        );
    }
}
