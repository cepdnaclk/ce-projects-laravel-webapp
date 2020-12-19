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


        //$category_code = $project->getParentCategory()->category_code;

        // Sync the database with the latest updates, using a cache architecture
        if ($project->syncProject()) $project = $project->fresh();

        return view('project.view', compact(['title', 'project']));
    }

    public function update(Project $project)
    {

        $request = Request::create(route('api.update.singleProjectWithCategory', [$project->organization, $project->repo_name, $project->main_category]), 'GET');
        $response = Route::dispatch($request);
        return redirect()->route('project.show', $project->name);
    }

}
