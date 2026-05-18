<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Enums\CorrectionRequestStatus;
use App\Enums\DmStatus;
use App\Enums\DSStatus;
use App\Enums\TdStatus;
use App\Models\CorrectionRequest;
use App\Models\Dm;
use App\Models\DS;
use App\Models\Content;
use App\Models\Td;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // Guest view
        if (!auth()->check()) {
            $introContent = Content::where('section', 'home_guest_intro')->first();
            $whoamiContent = Content::where('section', 'home_guest_whoami')->first();
            
            return inertia('Home/Home', [
                'introContent' => $introContent,
                'whoamiContent' => $whoamiContent,
            ]);
        }

        try {
            $user = auth()->user();

            // Teacher / Admin view (admin acts as teacher + sees admin alerts)
            if ($user->canActAsTeacher()) {
                $pendingCorrections = CorrectionRequest::query()
                    ->where('status', CorrectionRequestStatus::Pending->value)
                    ->where(function ($q) use ($user) {
                        $q->whereHas('ds', fn ($q) => $q->where('teacher_id', $user->id))
                          ->orWhereHas('dm', fn ($q) => $q->where('teacher_id', $user->id));
                    })
                    ->with(['user:id,first_name,last_name', 'ds:id,custom_title', 'dm:id,custom_title'])
                    ->latest()
                    ->get();

                $unlockRequests = Td::where('teacher_id', $user->id)
                    ->where('status', TdStatus::CorrectionRequested->value)
                    ->with('student:id,first_name,last_name')
                    ->latest('updated_at')
                    ->get(['id', 'custom_title', 'user_id', 'updated_at']);

                return inertia('Home/Home', [
                    'pendingCorrections' => [
                        'count' => $pendingCorrections->count(),
                        'items' => $pendingCorrections->take(5)->map(fn ($cr) => [
                            'id'            => $cr->id,
                            'student_name'  => $cr->user?->name ?? 'Élève',
                            'subject_title' => $cr->ds?->custom_title ?? $cr->dm?->custom_title ?? 'Devoir',
                            'subject_type'  => $cr->ds_id ? 'ds' : 'dm',
                            'created_at'    => $cr->created_at->toIso8601String(),
                        ])->values(),
                    ],
                    'unlockRequests' => [
                        'count' => $unlockRequests->count(),
                        'items' => $unlockRequests->take(5)->map(fn ($td) => [
                            'id'           => $td->id,
                            'student_name' => $td->student?->name ?? 'Élève',
                            'title'        => $td->custom_title ?? 'TD',
                            'updated_at'   => $td->updated_at->toIso8601String(),
                        ])->values(),
                    ],
                    'pendingTeachersCount' => $user->isAdmin()
                        ? User::where('role', 'teacher')->where('status', 'pending_approval')->count()
                        : 0,
                ]);
            }

            // Student view
            $activeDs = DS::where('user_id', $user->id)
                ->whereIn('status', [
                    DSStatus::NotStarted->value,
                    DSStatus::Ongoing->value,
                    DSStatus::Paused->value,
                    DSStatus::Sent->value,
                ])
                ->latest()
                ->get(['id', 'custom_title', 'status']);

            $activeDm = Dm::where('user_id', $user->id)
                ->whereIn('status', [
                    DmStatus::NotStarted->value,
                    DmStatus::Ongoing->value,
                ])
                ->latest()
                ->get(['id', 'custom_title', 'status']);

            $activeTd = Td::where('user_id', $user->id)
                ->whereIn('status', [
                    TdStatus::NotStarted->value,
                    TdStatus::Ongoing->value,
                    TdStatus::CorrectionRequested->value,
                ])
                ->latest()
                ->get(['id', 'custom_title', 'status']);

            $averageGrade = CorrectionRequest::where('user_id', $user->id)
                ->where('status', CorrectionRequestStatus::Corrected->value)
                ->avg('grade');

            return inertia('Home/Home', [
                'activeAssignments' => [
                    'ds' => $activeDs->map(fn ($ds) => [
                        'id'     => $ds->id,
                        'title'  => $ds->custom_title ?? 'DS',
                        'status' => $ds->status,
                    ])->values(),
                    'dm' => $activeDm->map(fn ($dm) => [
                        'id'     => $dm->id,
                        'title'  => $dm->custom_title ?? 'DM',
                        'status' => $dm->status->value,
                    ])->values(),
                    'td' => $activeTd->map(fn ($td) => [
                        'id'     => $td->id,
                        'title'  => $td->custom_title ?? 'TD',
                        'status' => $td->status->value,
                    ])->values(),
                ],
                'averageGrade' => $averageGrade ? round((float)$averageGrade, 1) : null,
            ]);
        } catch (\Exception $e) {
            if (auth()->check()) {
                abort(500, 'An error occurred while loading your dashboard. Please contact support.');
            }
            
            return redirect()->route('login');
        }
    }

    public function admin(): View
    {
        return view('admin');
    }
}
