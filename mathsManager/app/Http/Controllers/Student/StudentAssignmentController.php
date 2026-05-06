<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Dm;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class StudentAssignmentController extends Controller
{
    public function index(): Response
    {
        $dms = Dm::where('user_id', Auth::id())
            ->with('teacher:id,first_name,last_name')
            ->orderByDesc('created_at')
            ->get(['id', 'status', 'custom_title', 'custom_level', 'teacher_id', 'created_at']);

        return Inertia::render('Student/Assignments/Index', [
            'dms' => $dms,
        ]);
    }
}
