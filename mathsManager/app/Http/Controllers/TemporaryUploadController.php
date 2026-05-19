<?php

namespace App\Http\Controllers;

use App\Models\TemporaryUpload;
use App\Models\TemporaryUploadSession;
use App\Services\TemporaryUploadService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class TemporaryUploadController extends Controller
{
    public function __construct(private readonly TemporaryUploadService $uploadService) {}

    public function createSession(Request $request): JsonResponse
    {
        $request->validate([
            'purpose' => 'required|in:correction_submission,teacher_correction',
        ]);

        $session = $this->uploadService->createSession(auth()->user(), $request->input('purpose'));

        return response()->json([
            'session_id' => $session->id,
            'token'      => $session->token,
            'expires_at' => $session->expires_at->toIso8601String(),
        ], 201);
    }

    public function mobilePage(string $token): Response
    {
        $session = $this->resolveActiveSession($token);

        return Inertia::render('Uploads/Mobile', [
            'token'     => $token,
            'purpose'   => $session->purpose,
            'expiresAt' => $session->expires_at->toIso8601String(),
        ]);
    }

    public function addFile(Request $request, string $token): JsonResponse
    {
        $session = $this->resolveActiveSession($token);

        abort_if($session->uploads()->count() >= 20, 422, 'Nombre maximum de fichiers atteint (20).');

        $request->validate([
            'file' => 'required|file|image|max:5120',
        ]);

        $upload = $this->uploadService->addFile($session, $request->file('file'));

        return response()->json([
            'id'            => $upload->id,
            'original_name' => $upload->original_name,
            'size'          => $upload->size,
            'position'      => $upload->position,
        ], 201);
    }

    public function listFiles(string $token): JsonResponse
    {
        $session = $this->resolveActiveSession($token);

        return response()->json([
            'files' => $session->uploads->map(fn(TemporaryUpload $u) => [
                'id'            => $u->id,
                'original_name' => $u->original_name,
                'size'          => $u->size,
                'position'      => $u->position,
            ]),
        ]);
    }

    public function deleteFile(string $token, TemporaryUpload $upload): JsonResponse
    {
        $session = $this->resolveActiveSession($token);
        abort_unless($session->user_id === auth()->id(), 403);
        abort_unless($upload->session_id === $session->id, 403);

        $this->uploadService->removeFile($upload);

        return response()->json(null, 204);
    }

    private function resolveActiveSession(string $token): TemporaryUploadSession
    {
        $session = TemporaryUploadSession::where('token', $token)->firstOrFail();
        abort_if($session->isExpired(), 410, 'Cette session d\'upload a expiré.');
        abort_if($session->isConsumed(), 410, 'Cette session a déjà été utilisée.');
        return $session;
    }
}
