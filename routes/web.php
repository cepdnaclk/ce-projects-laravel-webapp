<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('dashboard/home', 'HomeController@index')->name('dashboard.home');

Route::group(['middleware' => 'verified'], function () {

    Route::get('dashboard/updateCategories', 'MaintainController@updateCategories')->name('dashboard.updateCategories');
    Route::get('dashboard/updateProjects', 'MaintainController@updateProjects')->name('dashboard.updateProjects');
    Route::get('dashboard/test', 'MaintainController@test')->name('dashboard.test');

});

// Documentation Routes

Route::get('docs/', 'DocsController@index')->name('docs.index');
Route::get('docs/{title}', 'DocsController@page')->name('docs.page');


// Category Routes
Route::get('categories/', 'CategoryController@showCategories')->name('list.category.index');
Route::get('batches/', 'CategoryController@showBatches')->name('list.batch.index');

Route::get('batch/{batch_id}/', 'CategoryController@showBatchCategories')->name('category.showBatchCategories');
Route::get('category/{category_title}/', 'CategoryController@showCategoryBatches')->name('category.showCategoryBatches');


// Project Routes
Route::get('projects/', 'ProjectController@index')->name('project.index');
Route::get('project/{project}/', 'ProjectController@show')->name('project.show');

// Batch/Category specific project (need a routing mechanism in future)
Route::get('batch/{batch_id}/{category_title}', 'ProjectController@showBC_Project')->name('category.showBC.Project');
Route::get('category/{category_title}/{batch_id}', 'ProjectController@showCB_Project')->name('category.showCB.Project');

