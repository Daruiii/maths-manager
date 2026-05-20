<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Content;
use App\Services\HomeDashboardService;

class HomeController extends Controller
{
    public function index(Request $request, HomeDashboardService $dashboard)
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
                return inertia('Home/Home', $dashboard->teacherPayload($user));
            }

            // Student view
            return inertia('Home/Home', $dashboard->studentPayload($user));
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
