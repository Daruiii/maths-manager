<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Enums\DSStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\SendTeacherCorrectionRequest;
use App\Models\CorrectionRequest;
use App\Notifications\TeacherSentCorrection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TeacherCorrectionController extends Controller
{
    public function show(CorrectionRequest $correctionRequest): Response
    {
        $this->authorizeTeacher($correctionRequest);

        $correctionRequest->load(['user', 'ds', 'dm']);

        return Inertia::render('Teacher/Corrections/Show', [
            'correctionRequest' => $correctionRequest,
        ]);
    }

    public function sendCorrection(SendTeacherCorrectionRequest $request, CorrectionRequest $correctionRequest): RedirectResponse
    {
        $this->authorizeTeacher($correctionRequest);
        abort_if($correctionRequest->isCorrected(), 409);

        $validated = $request->validated();

        $correctionRequest->update([
            'status'              => CorrectionRequestStatus::Corrected,
            'corrector_id'        => Auth::id(),
            'correction_pictures' => $validated['correction_pictures'],
            'correction_message'  => $validated['correction_message'] ?? null,
            'grade'               => $validated['grade'] ?? null,
        ]);

        if ($correctionRequest->ds_id) {
            $correctionRequest->ds->update(['status' => DSStatus::Corrected]);
        } else {
            $correctionRequest->dm->update(['status' => DmStatus::Corrected]);
        }

        $correctionRequest->user->notify(new TeacherSentCorrection($correctionRequest));

        return back()->with('success', 'Correction envoyée à ' . $correctionRequest->user->name . '.');
    }

    private function authorizeTeacher(CorrectionRequest $correctionRequest): void
    {
        $subject = $correctionRequest->ds ?? $correctionRequest->dm;
        abort_unless($subject?->teacher_id === Auth::id(), 403);
    }
}
