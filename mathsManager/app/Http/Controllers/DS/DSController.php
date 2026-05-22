<?php

namespace App\Http\Controllers\DS;

use App\Enums\CorrectionRequestStatus;
use App\Enums\DSStatus;
use App\Http\Requests\DS\SubmitDsCorrectionRequest;
use App\Models\CorrectionRequest;
use App\Models\TemporaryUploadSession;
use App\Notifications\StudentSubmittedCorrection;
use App\Services\TemporaryUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\DS;
use Inertia\Inertia;
use Inertia\Response as InertiaResponse;

class DSController extends Controller
{
    protected \App\Services\TimerFormattingService $timerService;
    protected \App\Services\QueryFiltersService $queryFiltersService;

    public function __construct(
        \App\Services\TimerFormattingService $timerService,
        \App\Services\QueryFiltersService $queryFiltersService
    ) {
        $this->timerService = $timerService;
        $this->queryFiltersService = $queryFiltersService;
    }

    // Méthode pour afficher tous les DS
    public function index(Request $request)
    {
        $sort_by_student = request()->query('sort_by_student');
        $sort_by_status = request()->query('sort_by_status');

        $dsList = DS::query()->with(['problems.multipleChapter', 'user', 'correctionRequest']);

        // Appliquer la recherche via le service
        $dsList = $this->queryFiltersService->applySearch(
            $dsList,
            $request->query('search'),
            ['type_bac', 'exercises_number', 'status']
        );

        // Recherche dans la relation user (si nécessaire, garder whereHas pour les relations)
        if ($request->query('search')) {
            $dsList->orWhereHas('user', function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->query('search') . '%');
            });
        }

        // Check if the request has sort_by_student
        if ($request->filled('sort_by_student')) {
            $dsList = $dsList->orderBy('user_id');
        }

        // Check if the request has sort_by_status
        if ($request->filled('sort_by_status')) {
            $dsList = $dsList->orderByRaw("FIELD(status, 'sent', 'ongoing', 'not_started', 'finished', 'corrected')");
        }

        // Default sort by created_at
        $dsList = $dsList->orderBy('created_at', 'desc');

        $dsList = $dsList->paginate(10)->withQueryString();

        return view('ds.index', compact('dsList', 'sort_by_student', 'sort_by_status'));
    }

    // Méthode pour afficher les DS de l'utilisateur connecté
    public function indexUser($id)
    {
        if (Auth::id() != $id) {
            return redirect()->route('ds.myDS', Auth::id());
        }
        // Eager loading pour éviter N+1 queries (fix #14.2)
        $dsList = DS::where('user_id', $id)
            ->with([
                'problems.multipleChapter',  // Charge exercices + leurs chapitres
                'correctionRequest'              // Charge les demandes de correction
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10)
            ->withQueryString();

        return view('ds.myDS', compact('dsList'));
    }

    // Méthode pour afficher un DS
    public function show(int $id): InertiaResponse
    {
        $ds = DS::findOrFail($id);
        $isTeacher = Auth::id() === $ds->teacher_id;
        abort_unless(Auth::id() === $ds->user_id || $isTeacher, 403);

        $notStarted = ! $isTeacher && $ds->status === DSStatus::NotStarted->value;
        $unlocked   = $isTeacher || $ds->status === DSStatus::Corrected->value;

        $ds->load(['teacher:id,first_name,last_name', 'correctionRequest']);
        if (! $notStarted) {
            $ds->load(['problems', 'exercises', 'privateExercises']);
        }

        // Server-side remaining time: prevents client from cheating by reloading
        $timerSeconds = $ds->timer;
        if (! $isTeacher && $ds->status === DSStatus::Ongoing->value && $ds->started_at) {
            $elapsed = (int) $ds->started_at->diffInSeconds(now());
            $timerSeconds = max(0, $ds->timer - $elapsed);
        }

        return Inertia::render('Student/DS/Show', [
            'ds' => [
                'id'                  => $ds->id,
                'status'              => $ds->status,
                'custom_title'        => $ds->custom_title,
                'custom_level'        => $ds->custom_level,
                'custom_instructions' => $ds->custom_instructions,
                'time_minutes'        => $ds->time,
                'timer_seconds'       => $timerSeconds,
                'type_bac'            => (bool) $ds->type_bac,
                'harder_exercises'    => (bool) $ds->harder_exercises,
                'is_teacher_preview'  => $isTeacher,
                'teacher'             => $ds->teacher
                    ? ['id' => $ds->teacher->id, 'first_name' => $ds->teacher->first_name, 'last_name' => $ds->teacher->last_name]
                    : null,
                'correction_request'  => $ds->correctionRequest,
                'problems'            => $notStarted ? [] : $ds->problems->map(fn ($p) => $this->mapDsItem($p, $unlocked)),
                'exercises'           => $notStarted ? [] : $ds->exercises->map(fn ($e) => $this->mapDsItem($e, $unlocked)),
                'private_exercises'   => $notStarted ? [] : $ds->privateExercises->map(fn ($e) => $this->mapDsItem($e, $unlocked)),
            ],
        ]);
    }

    private function mapDsItem(mixed $item, bool $unlocked): array
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

    public function updateStatus(Request $request, DS $ds): RedirectResponse
    {
        abort_unless(Auth::id() === $ds->user_id, 403);

        $status = $request->validate([
            'status' => ['required', 'in:ongoing,paused,finished,finished_late'],
        ])['status'];

        $validFrom = [
            'ongoing'       => [DSStatus::NotStarted->value, DSStatus::Paused->value],
            'paused'        => [DSStatus::Ongoing->value],
            'finished'      => [DSStatus::Ongoing->value],
            'finished_late' => [DSStatus::Ongoing->value],
        ];

        abort_unless(in_array($ds->status, $validFrom[$status] ?? []), 422);

        $updates = ['status' => $status];

        if ($status === 'ongoing') {
            // Record when this session started; timer column holds the remaining snapshot
            $updates['started_at'] = now();
        } elseif (in_array($status, ['paused', 'finished', 'finished_late'])) {
            // Compute true remaining before clearing started_at
            if ($ds->started_at) {
                $elapsed = (int) $ds->started_at->diffInSeconds(now());
                $updates['timer'] = max(0, $ds->timer - $elapsed);
            }
            $updates['started_at'] = null;
        }

        $ds->update($updates);

        return back();
    }

    public function submitCorrection(SubmitDsCorrectionRequest $request, DS $ds, TemporaryUploadService $uploadService): RedirectResponse
    {
        abort_unless(Auth::id() === $ds->user_id, 403);
        abort_if($ds->correctionRequest()->exists(), 409);
        abort_unless(in_array($ds->status, [DSStatus::Finished->value, DSStatus::FinishedLate->value]), 422);

        $session = TemporaryUploadSession::where('token', $request->validated('upload_session_token'))
            ->where('user_id', Auth::id())
            ->where('purpose', 'correction_submission')
            ->firstOrFail();

        abort_if($session->isExpired(), 422, 'La session d\'upload a expiré.');
        abort_if($session->isConsumed(), 422, 'Cette session a déjà été utilisée.');

        $finalPaths = $uploadService->consume($session, "student-ds-{$ds->id}");

        $correctionRequest = CorrectionRequest::create([
            'user_id'  => Auth::id(),
            'ds_id'    => $ds->id,
            'pictures' => $finalPaths,
            'message'  => $request->validated('message'),
            'status'   => CorrectionRequestStatus::Pending->value,
        ]);

        $ds->update(['status' => DSStatus::Sent->value]);

        $ds->teacher?->notify(new StudentSubmittedCorrection($correctionRequest));

        return back()->with('success', 'Votre copie a bien été envoyée.');
    }
}
