<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Facades\Redirect;

class DocsController extends Controller
{

    protected $pages = [
        'project-design'=>[
            'title' => 'Project Design',
            'link' => 'https://docs.google.com/document/d/e/2PACX-1vQOIQtoEDanIcvGF0tPCOs12sAWdebOzoFrPjY80a8wmufrk8YVHEzI7DYkeET6khXEtK5M-mmRrOW3/pub?embedded=true'
        ],
        'github_projects'=>[
            'title' => 'Current Project Site',
            'link'=>'https://cepdnaclk.github.io/projects/'
        ]
    ];

    public function index(){
        return view('docs.index');
    }

    public function page($title){

        if(isset($this->pages[$title])){

            $data = $this->pages[$title];
            return view('docs.page', compact('data'));

            //$url = $this->pages['github_projects']['link']."$title";
            //return Redirect::away($url);

        }else{
            //not found
            return abort(404);

        }

    }
}
