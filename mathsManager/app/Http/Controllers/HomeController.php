<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\CorrectionRequest;
use App\Models\DS;
use App\Models\Quizze;
use App\Models\Content;
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

            // Admin view
            if ($user->role == 'admin') {
                $status = $request->get('status', 'pending');
                $correctionRequests = CorrectionRequest::where('status', $status)
                    ->with('user')
                    ->orderBy('created_at', 'desc')
                    ->paginate(3)->withQueryString();

                $ds = DS::join('users', 'users.id', '=', 'DS.user_id')
                    ->whereIn('DS.status', ['not_started', 'ongoing', 'finished'])
                    ->select('DS.*', 'users.first_name', 'users.last_name')
                    ->orderBy('users.last_name', 'asc')
                    ->orderBy('users.first_name', 'asc')
                    ->get();

                return inertia('Home/Home', [
                    'correctionRequests' => $correctionRequests,
                    'ds' => $ds,
                ]);
            }

            // Student/Teacher common logic (Quizzes)
            $quizzes = Quizze::where('student_id', $user->id)
                ->with('details')
                ->latest()
                ->take(10)
                ->get();

            $goodAnswers = $quizzes->sum('score');
            $totalQuestions = $quizzes->sum(function ($quiz) {
                return $quiz->details->count();
            });

            $badAnswers = $totalQuestions - $goodAnswers;
            if ($totalQuestions == 0) {
                $goodAnswers = 100;
                $badAnswers = 0;
            }

            $scores = $quizzes->count() > 0 ? round($goodAnswers / $quizzes->count(), 1) : "N/A";

            // DS Stats
            $dsCounts = DS::where('user_id', $user->id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $averageGrade = CorrectionRequest::where('user_id', $user->id)
                ->where('status', 'corrected')
                ->avg('grade');

            return inertia('Home/Home', [
                'averageGrade' => $averageGrade ? round($averageGrade, 1) : "N/A",
                'totalDS' => $dsCounts->sum(),
                'notStartedDS' => $dsCounts->get('not_started', 0),
                'inProgressDS' => $dsCounts->get('ongoing', 0),
                'sentDS' => $dsCounts->get('sent', 0),
                'correctedDS' => $dsCounts->get('corrected', 0),
                'goodAnswers' => (int)$goodAnswers,
                'badAnswers' => (int)$badAnswers,
                'scores' => $scores,
            ]);
        } catch (\Exception $e) {
            if (auth()->check()) {
                abort(500, 'An error occurred while loading your dashboard. Please contact support.');
            }
            
            return redirect()->route('login');
        }
    }

    public function isntValid(): View
    {
        return view('errors/isntValid');
    }

    public function admin(): View
    {
        return view('admin');
    }
}
