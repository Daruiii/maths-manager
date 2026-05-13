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

        return Inertia::render('Student/Ressources/Index', [
            'dss' => $dss,
            'dms' => $dms,
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
