<?php

namespace App\Http\Controllers;

use App\Category;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function __construct()
    {

    }

    public function index()
    {
        $this->middleware('auth');
        return view('dashboard.home');
    }


    public function home(){

        $courseProj  = Category::where('type', 'COURSE')->get();
        $departProj  = Category::where('type', 'DEPARTMENT')->get();

        return view('pages.home', compact(['courseProj', 'departProj']));
    }
    public function about(){
        return view('pages.about');
    }
    public function contact(){
        return view('pages.contact');
    }
}
