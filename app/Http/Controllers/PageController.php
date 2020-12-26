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

        $categories = Category::all();
        return view('pages.home', compact('categories'));
    }
    public function about(){
        return view('pages.about');
    }
    public function contact(){
        return view('pages.contact');
    }
}
