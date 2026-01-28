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
    public function index(Request $request): View|RedirectResponse
    {
        // si on est pas co
        if (!auth()->check()) {
            $introContent = Content::where('section', 'home_guest_intro')->first();
            $whoamiContent = Content::where('section', 'home_guest_whoami')->first();
            // dd($introContent, $whoamiContent);
            return view('home', compact('introContent', 'whoamiContent'));
        }

        try {
            $user = auth()->user();

            if ($user->role == 'admin') {
                $search = $request->get('search');
                $status = $request->get('status', 'pending'); // Par défaut, le statut est 'pending'

                $correctionRequests = CorrectionRequest::where('status', $status)
                    ->when($search, function ($query, $search) {
                        $query->whereHas('user', function ($query) use ($search) {
                            $query->where('name', 'LIKE', "%{$search}%");
                        });
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(3)->withQueryString();

                // get all ds not_started and ongoing
                $ds = DS::join('users', 'users.id', '=', 'DS.user_id')
                    ->where('status', 'not_started')
                    ->orWhere('status', 'ongoing')
                    ->orwhere('status', 'finished')
                    ->select('DS.*', 'users.name')
                    ->orderBy('users.name', 'asc')
                    ->orderBy('status', 'asc')
                    ->get();

                session(['correctionRequests' => $correctionRequests]);
                session(['ds' => $ds]);
                return view('home', compact('correctionRequests', 'ds'));
            }

            // Get the last 10 quizzes with eager loading
            $quizzes = Quizze::where('student_id', $user->id)
                ->with('details')
                ->latest()
                ->take(10)
                ->get();

            // Calculate the number of correct and incorrect answers
            // goodAnswers = la somme de tous les scores 
            $goodAnswers = $quizzes->sum('score');
            $totalQUestions =  $quizzes->sum(function ($quiz) {
                return $quiz->details->count();
            });

            $badAnswers = $totalQUestions - $goodAnswers;
            if ($totalQUestions == 0) {
                $goodAnswers = 100;
                $badAnswers = 0;
            }

            // dd($goodAnswers, $badAnswers, $totalQUestions);

            // Get moyenne des 10 derniers scores 
            if ($quizzes->count() > 0) {
                $scores = round($goodAnswers / $quizzes->count(), 1);
            } else {
                $scores = "N/A";
            }

            // Single query with groupBy instead of 5 separate count() queries
            $dsCounts = DS::where('user_id', $user->id)
                ->selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status');

            $totalDS = $dsCounts->sum();
            $notStartedDS = $dsCounts->get('not_started', 0);
            $inProgressDS = $dsCounts->get('ongoing', 0);
            $sentDS = $dsCounts->get('sent', 0);
            $correctedDS = $dsCounts->get('corrected', 0);

            $averageGrade = CorrectionRequest::where('user_id', $user->id)
                ->where('status', 'corrected')
                ->avg('grade');

            if ($averageGrade == null) {
                $averageGrade = "N/A";
            } else {
                $averageGrade = round($averageGrade, 1);
            }

            return view('home', compact(
                'averageGrade',
                'totalDS',
                'notStartedDS',
                'inProgressDS',
                'sentDS',
                'correctedDS',
                'goodAnswers',
                'badAnswers',
                'scores'
            ));
        } catch (\Exception $e) {
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
