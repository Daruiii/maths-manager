<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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

            // Get the last 10 quizzes
            $quizzes = Quizze::where('student_id', $user->id)->latest()->take(10)->get();

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

            $totalDS = DS::where('user_id', $user->id)->count();
            $notStartedDS = DS::where('user_id', $user->id)->where('status', 'not_started')->count();
            $inProgressDS = DS::where('user_id', $user->id)->where('status', 'ongoing')->count();
            $sentDS = DS::where('user_id', $user->id)->where('status', 'sent')->count();
            $correctedDS = DS::where('user_id', $user->id)->where('status', 'corrected')->count();

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

    // method for redirect to error isntValid
    public function isntValid()
    {
        return view('errors/isntValid');
    }

    public function admin()
    {
        return view('admin');
    }
}
