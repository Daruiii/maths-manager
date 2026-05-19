<?php

namespace App\Http\Controllers\Test;

use App\Http\Controllers\Controller;
use Inertia\Inertia;
use Inertia\Response;

class InertiaTestController extends Controller
{
    /**
     * Display the Inertia React test page
     */
    public function index(): Response
    {
        // Auth data is automatically shared via HandleInertiaRequests middleware
        return Inertia::render('Test/InertiaTest');
    }
}
