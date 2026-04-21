<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\BuilderTemplate;
use App\Models\PrivateExercise;
use App\Models\StudentGroup;
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
                'exercisesCount'   => PrivateExercise::forTeacher($teacher->id)->count(),
                'dsTemplatesCount' => BuilderTemplate::where('teacher_id', $teacher->id)->where('type', 'ds')->count(),
                'tdTemplatesCount' => BuilderTemplate::where('teacher_id', $teacher->id)->where('type', 'td')->count(),
                'dmTemplatesCount' => BuilderTemplate::where('teacher_id', $teacher->id)->where('type', 'dm')->count(),
            ],
        ]);
    }

    /**
     * Listing unifié des templates sauvegardés (DS + TD + DM).
     */
    public function templates(): Response
    {
        $teacher = Auth::user();

        $cols = ['id', 'name', 'type', 'student_group_id', 'created_at', 'payload'];

        $base = BuilderTemplate::where('teacher_id', $teacher->id)
            ->with('studentGroup:id,name')
            ->orderByDesc('created_at');

        $groups = StudentGroup::where('teacher_id', $teacher->id)
            ->orderBy('name')
            ->get(['id', 'name']);

        return Inertia::render('Teacher/Bureau/Templates', [
            'dsTemplates' => (clone $base)->where('type', 'ds')->get($cols),
            'tdTemplates' => (clone $base)->where('type', 'td')->get($cols),
            'dmTemplates' => (clone $base)->where('type', 'dm')->get($cols),
            'groups'      => $groups,
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
