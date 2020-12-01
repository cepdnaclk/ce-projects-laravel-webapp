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


Auth::routes();

Route::get('/', 'PageController@home')->name('home');
Route::get('about', 'PageController@about')->name('about');
Route::get('contact', 'PageController@contact')->name('contact');

Route::get('dashboard', 'PageController@index')->name('dashboard.home');

Route::group(['middleware' => 'verified'], function () {
    // Only for testing purposes
    Route::get('dashboard/updateCategories', 'MaintainController@updateCategories')->name('dashboard.updateCategories');
    Route::get('dashboard/updateProjects', 'MaintainController@updateProjects')->name('dashboard.updateProjects');
    Route::get('dashboard/test', 'MaintainController@test')->name('dashboard.test');
    Route::get('dashboard/github', 'MaintainController@github')->name('dashboard.github');

});

// Documentation Routes
Route::get('docs/', 'DocsController@index')->name('docs.index');
Route::get('docs/{title}', 'DocsController@page')->name('docs.page');

// Category Routes
Route::get('categories/', 'CategoryController@showCategories')->name('list.category.index');
Route::get('category/{category_code}/', 'CategoryController@show')->name('category.show');
Route::get('category/{category_title}/{batch_id}', 'CategoryController@showByBatch')->name('category.batch');

// Project Routes
Route::get('projects/', 'ProjectController@index')->name('project.index');
Route::get('project/{title}', 'ProjectController@show')->name('project.show');
Route::get('project/refresh/{project}', 'ProjectController@update')->name('project.update');
