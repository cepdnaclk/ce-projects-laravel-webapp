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
        $project = Project::where('name', $title)->first();
        if ($project == null) return \Response::view('errors.404', [], 404);

        // TODO: Need to override default values, if config file provided by the repository owners

        $langData = $project->getLanguages();
        $contributorData = $project->getContributors();
        return view('project.view', compact(['title', 'project', 'langData', 'contributorData']));


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
