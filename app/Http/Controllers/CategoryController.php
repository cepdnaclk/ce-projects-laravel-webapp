<?php

namespace App\Http\Controllers;

use App\Category;
use App\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function __construct()
    {
        //$this->middleware('auth');
    }


    public function show($category_code)
    {
        $category = Category::where('category_code', $category_code)->first();
        if ($category == null) return \Response::view('errors.404', [], 404);

        $batches = $category->getBatches();                 // Get batches under given category
        $projects = $category->projects()->paginate(12);    // List of projects
        $project_count = $category->projects()->count();    // All projects, not the project count in current page
        $subtitle = '';

        return view('category.view', compact(['category', 'projects', 'batches', 'subtitle', 'project_count']));
    }

    public function showByBatch($category_code, $batch)
    {
        $category = Category::where('category_code', $category_code)->first();
        $projects = $category->projects()->where('batch', $batch)->get()->unique();
        $batches = null;
        $project_count = $projects->count();
        $subtitle = $batch;

        return view('category.view', compact(['category', 'projects', 'batches', 'subtitle', 'project_count']));
    }
}
