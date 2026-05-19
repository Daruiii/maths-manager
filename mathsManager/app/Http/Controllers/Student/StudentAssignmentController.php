<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Dm;
use App\Models\DS;
use App\Models\Td;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StudentAssignmentController extends Controller
{
    public function ressources(): Response
    {
        $userId = Auth::id();

        $dss = DS::where('user_id', $userId)
            ->with(['teacher:id,first_name,last_name', 'correctionRequest:id,ds_id,grade,status'])
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        $dms = Dm::where('user_id', $userId)
            ->with(['teacher:id,first_name,last_name', 'correctionRequest:id,dm_id,grade,status'])
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        $tds = Td::where('user_id', $userId)
            ->with('teacher:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        return Inertia::render('Student/Ressources/Index', [
            'dss' => $dss->map(fn ($ds) => [
                'id'           => $ds->id,
                'status'       => $ds->status,
                'custom_title' => $ds->custom_title,
                'custom_level' => $ds->custom_level,
                'teacher'      => $ds->teacher,
                'created_at'   => $ds->created_at,
                'grade'        => $ds->correctionRequest?->grade,
            ])->values(),
            'dms' => $dms->map(fn ($dm) => [
                'id'           => $dm->id,
                'status'       => $dm->status,
                'custom_title' => $dm->custom_title,
                'custom_level' => $dm->custom_level,
                'teacher'      => $dm->teacher,
                'created_at'   => $dm->created_at,
                'grade'        => $dm->correctionRequest?->grade,
            ])->values(),
            'tds' => $tds,
        ]);
    }

    public function index(): Response
    {
        $userId = Auth::id();

        $dss = DS::where('user_id', $userId)
            ->with('teacher:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        $dms = Dm::where('user_id', $userId)
            ->with('teacher:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        $tds = Td::where('user_id', $userId)
            ->with('teacher:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        return Inertia::render('Student/Assignments/Index', [
            'dss' => $dss,
            'dms' => $dms,
            'tds' => $tds,
        ]);
    }
}
