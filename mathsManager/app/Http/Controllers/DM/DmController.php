<?php

namespace App\Http\Controllers\DM;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DM\SubmitDmCorrectionRequest;
use App\Http\Requests\DM\UpdateDmStatusRequest;
use App\Models\CorrectionRequest;
use App\Models\Dm;
use App\Notifications\StudentSubmittedCorrection;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DmController extends Controller
{
    public function show(Dm $dm): Response
    {
        abort_unless(auth()->id() === $dm->user_id, 403);

        $dm->load(['problems', 'exercises', 'privateExercises', 'teacher', 'correctionRequest']);

        return Inertia::render('Student/DM/Show', [
            'dm' => $dm,
        ]);
    }

    public function updateStatus(UpdateDmStatusRequest $request, Dm $dm): RedirectResponse
    {
        abort_unless(auth()->id() === $dm->user_id, 403);

        $dm->update(['status' => $request->validated('status')]);

        return back();
    }

    public function submitCorrection(SubmitDmCorrectionRequest $request, Dm $dm): RedirectResponse
    {
        abort_unless(auth()->id() === $dm->user_id, 403);
        abort_if($dm->correctionRequest()->exists(), 409);

        $validated = $request->validated();

        $correctionRequest = CorrectionRequest::create([
            'user_id'  => auth()->id(),
            'dm_id'    => $dm->id,
            'pictures' => $validated['pictures'],
            'message'  => $validated['message'] ?? null,
            'status'   => CorrectionRequestStatus::Pending->value,
        ]);

        $dm->update(['status' => DmStatus::Finished]);

        $dm->teacher?->notify(new StudentSubmittedCorrection($correctionRequest));

        return back()->with('success', 'Votre copie a bien été envoyée.');
    }
}
