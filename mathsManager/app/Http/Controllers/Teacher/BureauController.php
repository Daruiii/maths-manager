<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\PrivateExercise;
use App\Services\BureauActivityService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class BureauController extends Controller
{
    /**
     * Dashboard du prof — vue d'ensemble de ses ressources.
     */
    public function index(): Response
    {
        $teacher = Auth::user();

        return Inertia::render('Teacher/Bureau/Index', [
            'stats' => [
                'exercisesCount' => PrivateExercise::forTeacher($teacher->id)->count(),
            ],
        ]);
    }

    /**
     * Historique global du professeur (assignations, élèves, corrections...).
     */
    public function history(Request $request, BureauActivityService $activityService): Response
    {
        $teacher = Auth::user();

        $filters = $request->validate([
            'search' => ['nullable', 'string', 'max:255'],
            'scope' => ['nullable', 'in:all,assignments,students,corrections'],
            'type' => [
                'nullable',
                'in:all,ds_assigned,td_assigned,dm_assigned,student_joined,invitation_configured,correction_requested,correction_processed',
            ],
            'sort' => ['nullable', 'in:asc,desc'],
            'page' => ['nullable', 'integer', 'min:1'],
            'per_page' => ['nullable', 'integer', 'min:5', 'max:100'],
        ]);

        $paginator = $activityService->forTeacher($teacher, [
            'search' => $filters['search'] ?? '',
            'scope' => $filters['scope'] ?? 'all',
            'type' => $filters['type'] ?? 'all',
            'sort' => $filters['sort'] ?? 'desc',
            'page' => $filters['page'] ?? 1,
            'per_page' => $filters['per_page'] ?? 20,
        ]);

        return Inertia::render('Teacher/Bureau/History', [
            'activities' => $paginator,
            'filters' => [
                'search' => $filters['search'] ?? '',
                'scope' => $filters['scope'] ?? 'all',
                'type' => $filters['type'] ?? 'all',
                'sort' => $filters['sort'] ?? 'desc',
                'per_page' => (int) ($filters['per_page'] ?? 20),
            ],
        ]);
    }
}
