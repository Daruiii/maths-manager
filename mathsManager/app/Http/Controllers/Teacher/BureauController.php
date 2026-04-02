<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\PrivateExercise;
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
}
