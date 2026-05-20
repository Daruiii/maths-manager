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

        $correctionRequest->load(['user', 'ds', 'dm']);

        return Inertia::render('Teacher/Corrections/Show', [
            'correctionRequest' => $correctionRequest,
        ]);
    }

    public function updateCorrection(Request $request, CorrectionRequest $correctionRequest): RedirectResponse
    {
        $this->authorizeTeacher($correctionRequest);
        abort_unless($correctionRequest->isCorrected(), 422);

        $data = $request->validate([
            'upload_session_token' => ['nullable', 'string'],
            'correction_message'   => ['nullable', 'string', 'max:2000'],
            'grade'                => ['nullable', 'numeric', 'min:0', 'max:20'],
        ]);

        $updatePayload = [
            'correction_message' => $data['correction_message'] ?? null,
            'grade'              => isset($data['grade']) ? (float) $data['grade'] : null,
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
            $existing = $correctionRequest->correction_pictures ?? [];
            $updatePayload['correction_pictures'] = array_merge($existing, $newPaths);
        }

        $correctionRequest->update($updatePayload);

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

        return back()->with('success', 'Correction envoyée à ' . $correctionRequest->user->name . '.');
    }

    private function authorizeTeacher(CorrectionRequest $correctionRequest): void
    {
        $subject = $correctionRequest->ds ?? $correctionRequest->dm;
        abort_unless($subject?->teacher_id === Auth::id(), 403);
    }
}
