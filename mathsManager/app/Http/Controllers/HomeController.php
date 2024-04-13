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
