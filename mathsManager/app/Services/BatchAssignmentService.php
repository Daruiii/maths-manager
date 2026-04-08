<?php

namespace App\Services;

use App\Helpers\ErrorResponseHelper;
use App\Http\Requests\DS\AssignDsBuilderRequest;
use App\Http\Requests\Td\AssignTdRequest;
use App\Mail\AssignDSMail;
use App\Mail\AssignTDMail;
use App\Models\DS;
use App\Models\DsBatch;
use App\Models\Exercise;
use App\Models\PrivateExercise;
use App\Models\Problem;
use App\Models\Td;
use App\Models\TdBatch;
use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Mail;

class BatchAssignmentService
{
    private const DEFAULT_EXERCISE_MINUTES = 10;

    // ──────────────────────────────────────────────────────────────────────────
    // DS
    // ──────────────────────────────────────────────────────────────────────────

    public function assignDs(AssignDsBuilderRequest $request, User $teacher): int
    {
        $problems  = Problem::findMany($request->input('problem_ids', []));
        $exercises = Exercise::findMany($request->input('exercise_ids', []));
        $privateExercises = $this->scopedPrivateExercises(
            $request->input('private_exercise_ids', []),
            $teacher->id
        );

        $privateTime = $privateExercises->sum(fn ($e) => $e->time ?? self::DEFAULT_EXERCISE_MINUTES);
        $totalTime   = $problems->sum('time')
            + ($exercises->count() * self::DEFAULT_EXERCISE_MINUTES)
            + $privateTime;

        $multipleChapterIds = $problems->pluck('multiple_chapter_id')->unique()->values()->all();

        [$studentIds, $groupIds] = $this->extractRecipients($request);

        $batch = DsBatch::create([
            'teacher_id'  => $teacher->id,
            'group_ids'   => $groupIds->isNotEmpty() ? $groupIds->all() : null,
            'student_ids' => $studentIds->all(),
            'ds_count'    => $studentIds->count(),
        ]);

        foreach ($studentIds as $studentId) {
            $ds = new DS();
            $ds->user_id              = $studentId;
            $ds->teacher_id           = $teacher->id;
            $ds->batch_id             = $batch->id;
            $ds->type_bac             = false;
            $ds->exercises_number     = $problems->count() + $exercises->count() + $privateExercises->count();
            $ds->harder_exercises     = false;
            $ds->time                 = $totalTime;
            $ds->timer                = $totalTime * 60;
            $ds->chrono               = 0;
            $ds->status               = 'not_started';
            $ds->custom_title         = $request->input('custom_title');
            $ds->custom_level         = $request->input('custom_level');
            $ds->custom_instructions  = $request->input('custom_instructions');
            $ds->save();

            $ds->multipleChapters()->attach($multipleChapterIds);
            if ($problems->isNotEmpty())         $ds->problems()->attach($problems->pluck('id'));
            if ($exercises->isNotEmpty())        $ds->exercises()->attach($exercises->pluck('id'));
            if ($privateExercises->isNotEmpty()) $ds->privateExercises()->attach($privateExercises->pluck('id'));

            $this->sendMailSafe(new AssignDSMail($ds), $studentId, 'DS builder assign');
        }

        return $studentIds->count();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // TD
    // ──────────────────────────────────────────────────────────────────────────

    public function assignTd(AssignTdRequest $request, User $teacher): int
    {
        $exercises = Exercise::findMany($request->input('exercise_ids', []));
        $privateExercises = $this->scopedPrivateExercises(
            $request->input('private_exercise_ids', []),
            $teacher->id,
            basicOnly: true
        );

        [$studentIds, $groupIds] = $this->extractRecipients($request);

        $batch = TdBatch::create([
            'teacher_id'  => $teacher->id,
            'group_ids'   => $groupIds->isNotEmpty() ? $groupIds->all() : null,
            'student_ids' => $studentIds->all(),
            'td_count'    => $studentIds->count(),
        ]);

        foreach ($studentIds as $studentId) {
            $td = Td::create([
                'teacher_id'          => $teacher->id,
                'user_id'             => $studentId,
                'batch_id'            => $batch->id,
                'custom_title'        => $request->input('custom_title'),
                'custom_level'        => $request->input('custom_level'),
                'custom_instructions' => $request->input('custom_instructions'),
                'correction_unlocked' => false,
            ]);

            if ($exercises->isNotEmpty())        $td->exercises()->attach($exercises->pluck('id'));
            if ($privateExercises->isNotEmpty()) $td->privateExercises()->attach($privateExercises->pluck('id'));

            $this->sendMailSafe(new AssignTDMail($td), $studentId, 'TD builder assign');
        }

        return $studentIds->count();
    }

    // ──────────────────────────────────────────────────────────────────────────
    // Helpers privés
    // ──────────────────────────────────────────────────────────────────────────

    private function scopedPrivateExercises(array $ids, int $teacherId, bool $basicOnly = false)
    {
        $query = PrivateExercise::whereIn('id', $ids)->where('teacher_id', $teacherId);

        if ($basicOnly) {
            $query->where('type', 'basic');
        }

        return $query->get();
    }

    private function extractRecipients($request): array
    {
        $studentIds = collect($request->input('student_ids'))->unique()->values();
        $groupIds   = collect($request->input('group_ids', []))->filter()->values();

        return [$studentIds, $groupIds];
    }

    private function sendMailSafe(Mailable $mail, int $studentId, string $context): void
    {
        $student = User::find($studentId);
        if (!$student) return;

        try {
            Mail::to($student->email)->send($mail);
        } catch (\Exception $e) {
            ErrorResponseHelper::mailError($e, $context);
        }
    }
}
