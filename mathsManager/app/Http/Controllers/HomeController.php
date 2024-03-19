<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Chapter;
use App\Models\Classe;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function admin()
    {
        return view('admin');
    }
}
