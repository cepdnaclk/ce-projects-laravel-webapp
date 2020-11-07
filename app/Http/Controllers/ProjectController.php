<?php

namespace App\Http\Controllers;

use App\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{

    public function __construct()
    {
        //$this->middleware('auth');
    }

    public function index()
    {
        // $projects = Project::paginate(12);
        return view('project.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Project $project)
    {
        return view('project.view', compact('project'));
    }


    public function showBC_Project($batch_id, $category_title)
    {
        return view('project.view', compact(['batch_id', 'category_title']));
    }

    public function showCB_Project($category_title, $batch_id)
    {
        return view('project.view', compact(['batch_id', 'category_title']));
    }

    public function edit(Project $project)
    {
        //
    }

    public function update(Request $request, Project $project)
    {
        //
    }

    public function destroy(Project $project)
    {
        //
    }
}
