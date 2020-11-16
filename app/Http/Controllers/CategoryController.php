<?php

namespace App\Http\Controllers;

use App\Category;
use App\Project;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function show($category_code)
    {
        $category = Category::where('category_code', $category_code)->first();

        if($category==null)  return \Response::view('errors.404',[],404);

        $batches = Project::all()->groupBy('batch')->reverse();

        $project_count =  $category->projects()->count();
        $projects = $category->projects()->paginate(12);

        return view('category.view', compact(['category','projects', 'batches', 'project_count']));
    }

    public function showByBatch($category_code, $batch)
    {
        $category = Category::where('category_code', $category_code)->first();
        $projects = $category->projects()->where('batch', $batch)->get()->unique();
        $batches = null;
        $project_count =  $category->projects()->count();
        $subtitle = $batch;

        return view('category.view', compact(['category','projects', 'batches', 'subtitle', 'project_count']));
    }
}
