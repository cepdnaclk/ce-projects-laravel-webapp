<?php

namespace App\Http\Controllers;

use App\Project;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class ProjectController extends Controller
{
    protected $githubOrgName;

    public function __construct()
    {
        //$this->middleware('auth');
        $this->githubOrgName = env('GITHUB_ORGANIZATION');
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

    public function show($title)
    {
        // Request project data from internal API

        // $proj = Project::getByBatch("e14");

        $data = Project::getGithubData($title);

        if($data != null){
            return view('project.view', compact(['title', 'data']));
        }else{
            return \Response::view('errors.500',[],500);
        }
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
