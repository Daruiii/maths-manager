<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Classe;
use App\Models\CorrectionRequest;
use App\Models\DS;

class HomeController extends Controller
{
    public function index()
    {
        // si on est pas co
        if (!auth()->check()) {
            return view('home');
        }
  
        $user = auth()->user();

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
        }

        return view('home', compact('averageGrade', 'totalDS', 'notStartedDS', 'inProgressDS', 'sentDS', 'correctedDS'));
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
