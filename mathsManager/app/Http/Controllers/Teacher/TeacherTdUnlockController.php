<?php

namespace App\Http\Controllers\Teacher;

use App\Enums\TdStatus;
use App\Http\Controllers\Controller;
use App\Models\Td;
use App\Models\TdBatch;
use App\Notifications\TeacherUnlockedTd;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class TeacherTdUnlockController extends Controller
{
    public function unlock(Td $td): RedirectResponse
    {
        abort_unless($td->teacher_id === Auth::id(), 403);

        $td->update([
            'correction_unlocked' => true,
            'status'              => TdStatus::CorrectionUnlocked,
        ]);

        $td->student->notify(new TeacherUnlockedTd($td));

        if ($td->batch_id && $this->isBatchAllUnlocked($td->batch_id)) {
            session()->flash('confetti', true);
            return back()->with('success', 'Toutes les corrections débloquées ! Ce TD est maintenant archivé.');
        }

        return back()->with('success', 'Correction débloquée pour ' . $td->student->name . '.');
    }

    public function unlockBatch(TdBatch $batch): RedirectResponse
    {
        abort_unless($batch->teacher_id === Auth::id(), 403);

        $toUnlock = $batch->tds()
            ->where('status', '!=', TdStatus::CorrectionUnlocked)
            ->with('student')
            ->get();

        $toUnlock->each(function (Td $td) {
            $td->update([
                'correction_unlocked' => true,
                'status'              => TdStatus::CorrectionUnlocked,
            ]);

            $td->student->notify(new TeacherUnlockedTd($td));
        });

        if ($this->isBatchAllUnlocked($batch->id)) {
            session()->flash('confetti', true);
            return back()->with('success', 'Toutes les corrections débloquées ! Ce TD est maintenant archivé.');
        }

        return back()->with('success', 'Correction débloquée pour ' . $toUnlock->count() . ' élève(s).');
    }

    private function isBatchAllUnlocked(int $batchId): bool
    {
        $batch = TdBatch::find($batchId);
        if (! $batch) return false;

        $total    = $batch->tds()->count();
        $unlocked = $batch->tds()->where('status', TdStatus::CorrectionUnlocked)->count();

        return $total > 0 && $unlocked >= $total;
    }
}
