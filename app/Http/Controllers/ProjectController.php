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

        $category_code = $project->getParentCategory()->category_code;

        // TODO: Need to override default values, if config file provided by the repository owners
        if ($project->syncProject()) {
            $project = $project->fresh();
        }

        //$langData = $project->getLanguages();
        //$contributorData = $project->getContributors();
        return view('project.view', compact(['title', 'project', 'category_code']));


    }


    public function update(Project $project)
    {
        //dd($project);

        $request = Request::create(route('api.update.singleProject', [$project->organization, $project->repo_name]), 'GET');
        $response = Route::dispatch($request);
        return redirect()->route('project.show', $project->name);
    }

}
