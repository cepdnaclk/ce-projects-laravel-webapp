<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('repository/{organization}/{title}/languages', 'API\RepositoryController@getRepoLanguages')->name('api.repository.languages');
Route::get('repository/{organization}/{title}/contributors', 'API\RepositoryController@getRepoContributors')->name('api.repository.contributors');
Route::get('repository/{organization}/{title}', 'API\RepositoryController@show')->name('api.repository.show');

//Route::get('repository/{title}', 'API\RepositoryController@show')->name('api.repository.show');
Route::get('repositories/', 'API\RepositoryController@index')->name('api.repository.index');
Route::get('repositories/{category_code}', 'API\RepositoryController@categoryIndex')->name('api.repository.filter');



