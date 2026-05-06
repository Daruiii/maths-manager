<?php

namespace App\Http\Controllers\DM;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\DM\SubmitDmCorrectionRequest;
use App\Http\Requests\DM\UpdateDmStatusRequest;
use App\Models\CorrectionRequest;
use App\Models\Dm;
use App\Models\TemporaryUploadSession;
use App\Notifications\StudentSubmittedCorrection;
use App\Services\TemporaryUploadService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class DmController extends Controller
{
    public function __construct(private readonly TemporaryUploadService $uploadService) {}

    public function show(Dm $dm): Response
    {
        abort_unless(auth()->id() === $dm->user_id, 403);

        $dm->load(['problems', 'exercises', 'privateExercises', 'teacher:id,first_name,last_name', 'correctionRequest']);

        $unlocked = $dm->status === DmStatus::Corrected;

        return Inertia::render('Student/DM/Show', [
            'dm' => [
                'id'                  => $dm->id,
                'status'              => $dm->status,
                'custom_title'        => $dm->custom_title,
                'custom_level'        => $dm->custom_level,
                'custom_instructions' => $dm->custom_instructions,
                'teacher'             => $dm->teacher
                    ? ['id' => $dm->teacher->id, 'first_name' => $dm->teacher->first_name, 'last_name' => $dm->teacher->last_name]
                    : null,
                'correction_request'  => $dm->correctionRequest,
                'problems'            => $dm->problems->map(fn ($p) => $this->mapItem($p, $unlocked)),
                'exercises'           => $dm->exercises->map(fn ($e) => $this->mapItem($e, $unlocked)),
                'private_exercises'   => $dm->privateExercises->map(fn ($e) => $this->mapItem($e, $unlocked)),
            ],
        ]);
    }

    private function mapItem(mixed $item, bool $unlocked): array
    {
        return [
            'id'              => $item->id,
            'name'            => $item->name ?? null,
            'title'           => $item->title ?? null,
            'statement'       => $item->statement ?? null,
            'latex_statement' => $item->latex_statement ?? null,
            'image_paths'     => $item->image_paths ?? null,
            'difficulty'      => $item->difficulty ?? null,
            'latex_solution'  => $unlocked ? ($item->latex_solution ?? null) : null,
        ];
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

        $session = TemporaryUploadSession::where('token', $request->validated('upload_session_token'))
            ->where('user_id', auth()->id())
            ->where('purpose', 'correction_submission')
            ->firstOrFail();

        abort_if($session->isExpired(), 422, 'La session d\'upload a expiré.');
        abort_if($session->isConsumed(), 422, 'Cette session a déjà été utilisée.');
        abort_if($session->uploads()->count() === 0, 422, 'Veuillez ajouter au moins une photo.');

        $finalPaths = $this->uploadService->consume($session, "student-dm-{$dm->id}");

        $correctionRequest = CorrectionRequest::create([
            'user_id'  => auth()->id(),
            'dm_id'    => $dm->id,
            'pictures' => $finalPaths,
            'message'  => $request->validated('message'),
            'status'   => CorrectionRequestStatus::Pending->value,
        ]);

        $dm->update(['status' => DmStatus::Finished]);

        $dm->teacher?->notify(new StudentSubmittedCorrection($correctionRequest));

        return back()->with('success', 'Votre copie a bien été envoyée.');
    }
}
