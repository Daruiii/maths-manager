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

        return back()->with('success', 'Correction débloquée pour ' . $td->student->name . '.');
    }

    public function unlockBatch(TdBatch $batch): RedirectResponse
    {
        abort_unless($batch->teacher_id === Auth::id(), 403);

        $batch->tds()->with('student')->get()->each(function (Td $td) {
            $td->update([
                'correction_unlocked' => true,
                'status'              => TdStatus::CorrectionUnlocked,
            ]);

            $td->student->notify(new TeacherUnlockedTd($td));
        });

        return back()->with('success', 'Correction débloquée pour tous les élèves du groupe.');
    }
}
