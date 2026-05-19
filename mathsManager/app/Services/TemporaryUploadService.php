<?php

namespace App\Services;

use App\Models\TemporaryUpload;
use App\Models\TemporaryUploadSession;
use App\Models\User;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TemporaryUploadService
{
    private const EXPIRY_MINUTES = 60;

    private const ALLOWED_MIMES = ['image/jpeg', 'image/png', 'image/jpg', 'image/webp', 'image/gif'];
    private const MAX_SIZE_KB    = 5120; // 5 MB

    public function createSession(User $user, string $purpose): TemporaryUploadSession
    {
        return TemporaryUploadSession::create([
            'user_id'    => $user->id,
            'token'      => Str::random(48),
            'purpose'    => $purpose,
            'expires_at' => now()->addMinutes(self::EXPIRY_MINUTES),
        ]);
    }

    public function addFile(TemporaryUploadSession $session, UploadedFile $file): TemporaryUpload
    {
        $this->validateFile($file);

        $nextPosition = $session->uploads()->max('position') + 1;
        $filename     = Str::random(16) . '.' . $file->getClientOriginalExtension();
        $path         = $file->storeAs("temporary-uploads/{$session->token}", $filename, 'private');

        return TemporaryUpload::create([
            'session_id'    => $session->id,
            'path'          => $path,
            'original_name' => $file->getClientOriginalName(),
            'mime'          => $file->getMimeType(),
            'size'          => $file->getSize(),
            'position'      => $nextPosition,
        ]);
    }

    /**
     * Moves all files from temp storage to a permanent destination and marks the session consumed.
     * Returns the array of final paths.
     */
    public function consume(TemporaryUploadSession $session, string $destinationIdentifier): array
    {
        throw_if($session->isExpired(),  \RuntimeException::class, 'Cannot consume an expired upload session.');
        throw_if($session->isConsumed(), \RuntimeException::class, 'Cannot consume an already-consumed upload session.');

        $finalPaths = [];

        foreach ($session->uploads()->orderBy('position')->get() as $upload) {
            $filename    = basename($upload->path);
            $destination = "corrections/{$destinationIdentifier}/{$filename}";

            Storage::disk('private')->move($upload->path, $destination);

            $finalPaths[] = $destination;
        }

        $session->update(['consumed_at' => now()]);

        // Clean the now-empty temp directory
        Storage::disk('private')->deleteDirectory("temporary-uploads/{$session->token}");

        return $finalPaths;
    }

    public function removeFile(TemporaryUpload $upload): void
    {
        Storage::disk('private')->delete($upload->path);
        $upload->delete();
    }

    /** Purges expired, unconsumed sessions and their files. */
    public function purgeExpired(): int
    {
        $expired = TemporaryUploadSession::where('expires_at', '<', now())
            ->whereNull('consumed_at')
            ->get();

        foreach ($expired as $session) {
            Storage::disk('private')->deleteDirectory("temporary-uploads/{$session->token}");
            $session->delete();
        }

        return $expired->count();
    }

    private function validateFile(UploadedFile $file): void
    {
        if (!in_array($file->getMimeType(), self::ALLOWED_MIMES)) {
            throw new \InvalidArgumentException('Type de fichier non autorisé.');
        }

        if ($file->getSize() > self::MAX_SIZE_KB * 1024) {
            throw new \InvalidArgumentException('Fichier trop volumineux (max 5 Mo).');
        }
    }
}
