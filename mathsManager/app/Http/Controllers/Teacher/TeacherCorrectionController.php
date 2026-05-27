<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Enums\DSStatus;
use App\Enums\TdStatus;
use App\Http\Controllers\Controller;
use App\Http\Requests\Teacher\SendTeacherCorrectionRequest;
use App\Models\CorrectionRequest;
use App\Models\Td;
use App\Models\TemporaryUploadSession;
use App\Notifications\TeacherSentCorrection;
use App\Notifications\TeacherUpdatedCorrection;
use App\Services\TemporaryUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class TeacherCorrectionController extends Controller
{
    public function __construct(private readonly TemporaryUploadService $uploadService) {}

    public function index(Request $request): Response
    {
        $teacher = Auth::user();
        $status  = $request->query('status', 'pending');

        $query = CorrectionRequest::query()
            ->where(function ($q) use ($teacher) {
                $q->whereHas('ds', fn ($q) => $q->where('teacher_id', $teacher->id))
                  ->orWhereHas('dm', fn ($q) => $q->where('teacher_id', $teacher->id));
            })
            ->with([
                'user:id,first_name,last_name',
                'ds:id,custom_title,teacher_id',
                'dm:id,custom_title,teacher_id',
            ])
            ->orderByDesc('created_at');

        if (in_array($status, ['pending', 'corrected'])) {
            $query->where('status', $status);
        }

        return Inertia::render('Teacher/Corrections/Index', [
            'correctionRequests' => $query->paginate(20)->withQueryString(),
            'tdUnlockRequests'   => Td::where('teacher_id', $teacher->id)
                ->where('status', TdStatus::CorrectionRequested->value)
                ->with('student:id,first_name,last_name')
                ->orderByDesc('updated_at')
                ->get(['id', 'status', 'custom_title', 'custom_level', 'user_id', 'batch_id', 'updated_at']),
            'filters'            => ['status' => $status],
        ]);
    }

    public function show(CorrectionRequest $correctionRequest): Response
    {
        $this->authorizeTeacher($correctionRequest);

        $correctionRequest->load([
            'user:id,first_name,last_name,avatar,role',
            'ds.problems',
            'ds.exercises',
            'ds.privateExercises',
            'dm.problems',
            'dm.exercises',
            'dm.privateExercises',
        ]);

        return Inertia::render('Teacher/Corrections/Show', [
            'correctionRequest' => $this->mapCorrectionRequest($correctionRequest),
        ]);
    }

    private function mapCorrectionRequest(CorrectionRequest $correctionRequest): array
    {
        return [
            'id'                 => $correctionRequest->id,
            'status'             => $correctionRequest->status,
            'pictures'           => $correctionRequest->pictures ?? [],
            'correction_pictures'=> $correctionRequest->correction_pictures,
            'correction_message' => $correctionRequest->correction_message,
            'grade'              => $correctionRequest->grade,
            'message'            => $correctionRequest->message,
            'created_at'         => $correctionRequest->created_at,
            'updated_at'         => $correctionRequest->updated_at,
            'user'               => $correctionRequest->user
                ? [
                    'id'         => $correctionRequest->user->id,
                    'first_name' => $correctionRequest->user->first_name,
                    'last_name'  => $correctionRequest->user->last_name,
                    'avatar'     => $correctionRequest->user->avatar,
                    'role'       => $correctionRequest->user->role,
                ]
                : null,
            'ds'                 => $correctionRequest->ds
                ? $this->mapCorrectionSubject($correctionRequest->ds)
                : null,
            'dm'                 => $correctionRequest->dm
                ? $this->mapCorrectionSubject($correctionRequest->dm)
                : null,
        ];
    }

    private function mapCorrectionSubject(mixed $subject): array
    {
        return [
            'id'                  => $subject->id,
            'custom_title'        => $subject->custom_title,
            'custom_level'        => $subject->custom_level,
            'custom_instructions' => $subject->custom_instructions,
            'problems'            => $subject->problems->map(fn ($item) => $this->mapAssignmentItem($item)),
            'exercises'           => $subject->exercises->map(fn ($item) => $this->mapAssignmentItem($item)),
            'private_exercises'   => $subject->privateExercises->map(fn ($item) => $this->mapAssignmentItem($item)),
        ];
    }

    private function mapAssignmentItem(mixed $item): array
    {
        return [
            'id'              => $item->id,
            'name'            => $item->name ?? null,
            'title'           => $item->title ?? null,
            'statement'       => $item->statement ?? null,
            'latex_statement' => $item->latex_statement ?? null,
            'image_paths'     => $item->image_paths ?? null,
            'difficulty'      => $item->difficulty ?? null,
            'latex_solution'  => $item->latex_solution ?? null,
        ];
    }

    public function updateCorrection(Request $request, CorrectionRequest $correctionRequest): RedirectResponse
    {
        $this->authorizeTeacher($correctionRequest);
        abort_unless($correctionRequest->isCorrected(), 422);

        $data = $request->validate([
            'upload_session_token' => ['nullable', 'string'],
            'existing_pictures'    => ['nullable', 'array'],
            'existing_pictures.*'  => ['string'],
            'correction_message'   => ['nullable', 'string', 'max:2000'],
            'grade'                => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $currentPictures = $correctionRequest->correction_pictures ?? [];
        $requestedPictures = $data['existing_pictures'] ?? null;
        $base = is_array($requestedPictures)
            ? array_values(array_intersect($requestedPictures, $currentPictures))
            : $currentPictures;

        $updatePayload = [
            'corrector_id'        => Auth::id(),
            'correction_message' => $data['correction_message'] ?? null,
            'grade'              => isset($data['grade']) ? (float) $data['grade'] : null,
            'correction_pictures' => $base,
        ];

        if ($token = $data['upload_session_token'] ?? null) {
            $session = TemporaryUploadSession::where('token', $token)
                ->where('user_id', Auth::id())
                ->where('purpose', 'teacher_correction')
                ->firstOrFail();

            abort_if($session->isExpired(), 422, 'La session d\'upload a expiré.');
            abort_if($session->isConsumed(), 422, 'Cette session a déjà été utilisée.');

            $destinationKey = $correctionRequest->ds_id
                ? "teacher-ds-{$correctionRequest->ds_id}"
                : "teacher-dm-{$correctionRequest->dm_id}";

            $newPaths = $this->uploadService->consume($session, $destinationKey);
            $updatePayload['correction_pictures'] = array_merge($base, $newPaths);
        }

        $correctionRequest->update($updatePayload);
        $correctionRequest->refresh();
        $correctionRequest->user->notify(new TeacherUpdatedCorrection($correctionRequest));

        return back()->with('success', 'Correction mise à jour.');
    }

    public function sendCorrection(SendTeacherCorrectionRequest $request, CorrectionRequest $correctionRequest): RedirectResponse
    {
        $this->authorizeTeacher($correctionRequest);
        abort_if($correctionRequest->isCorrected(), 409);

        $finalPaths = null;

        if ($token = $request->validated('upload_session_token')) {
            $session = TemporaryUploadSession::where('token', $token)
                ->where('user_id', Auth::id())
                ->where('purpose', 'teacher_correction')
                ->firstOrFail();

            abort_if($session->isExpired(), 422, 'La session d\'upload a expiré.');
            abort_if($session->isConsumed(), 422, 'Cette session a déjà été utilisée.');

            $destinationKey = $correctionRequest->ds_id
                ? "teacher-ds-{$correctionRequest->ds_id}"
                : "teacher-dm-{$correctionRequest->dm_id}";

            $finalPaths = $this->uploadService->consume($session, $destinationKey);
        }

        $correctionRequest->update([
            'status'              => CorrectionRequestStatus::Corrected,
            'corrector_id'        => Auth::id(),
            'correction_pictures' => $finalPaths,
            'correction_message'  => $request->validated('correction_message'),
            'grade'               => $request->validated('grade'),
        ]);

        if ($correctionRequest->ds_id) {
            $correctionRequest->ds->update(['status' => DSStatus::Corrected]);
        } else {
            $correctionRequest->dm->update(['status' => DmStatus::Corrected]);
        }

        $correctionRequest->user->notify(new TeacherSentCorrection($correctionRequest));

        $isLastCorrection = $this->isBatchComplete($correctionRequest);

        if ($isLastCorrection) {
            session()->flash('confetti', true);
            return back()->with('success', 'Toutes les copies corrigées ! Ce devoir est maintenant archivé.');
        }

        return back()->with('success', 'Correction envoyée à ' . $correctionRequest->user->name . '.');
    }

    private function isBatchComplete(CorrectionRequest $correctionRequest): bool
    {
        if ($correctionRequest->ds_id) {
            $batch = $correctionRequest->ds->batch;
            if (! $batch) return false;
            $total     = $batch->ds()->count();
            $corrected = $batch->ds()->where('status', DSStatus::Corrected->value)->count();
            return $total > 0 && $corrected >= $total;
        }

        $batch = $correctionRequest->dm->batch;
        if (! $batch) return false;
        $total     = $batch->dms()->count();
        $corrected = $batch->dms()->where('status', DmStatus::Corrected->value)->count();
        return $total > 0 && $corrected >= $total;
    }

    private function authorizeTeacher(CorrectionRequest $correctionRequest): void
    {
        $subject = $correctionRequest->ds ?? $correctionRequest->dm;
        abort_unless($subject?->teacher_id === Auth::id(), 403);
    }
}
