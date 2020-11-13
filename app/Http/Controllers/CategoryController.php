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

    public function showCategories()
    {
        $data = Category::getGithubData();

        if($data != null){
            return view('category.categories', compact(['data']));
        }else{
            return \Response::view('errors.404',[],404);
        }
        //return view('category.categories');
    }

    public function showBatches()
    {
        return view('category.batches');
    }

    public function showBatchCategories($batch_id)
    {
        return view('category.batch_category', compact('batch_id'));
    }

    public function showCategoryBatches($category_title)
    {
        return view('category.category_batch', compact('category_title'));
    }

    public function create()
    {

    }

    public function store(Request $request)
    {
        //
    }

    public function show(Category $category)
    {
        return view('category.view', compact('category'));
    }

    public function edit(Category $category)
    {
        //
    }

    public function update(Request $request, Category $category)
    {
        //
    }

    public function destroy(Category $category)
    {
        //
    }
}
