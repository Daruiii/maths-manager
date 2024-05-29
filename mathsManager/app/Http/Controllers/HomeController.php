<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\CorrectionRequest;
use App\Models\DS;
use App\Models\Quizze;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        // si on est pas co
        if (!auth()->check()) {
            return view('home');
        }
  
        Auth::user()->refresh();
        $user = auth()->user();

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

        return view('home', compact('averageGrade', 'totalDS', 'notStartedDS', 'inProgressDS', 'sentDS', 'correctedDS', 
        'goodAnswers', 'badAnswers', 'scores'));
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
