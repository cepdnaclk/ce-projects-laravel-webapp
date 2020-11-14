<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Category extends Model
{
    // protected $guarded = [];

    function getProjects($batch_id){
        return $this->hasMany(Project::class);
    }


    public static function getGithubData()
    {
        // This will make a request to internal API server and obtain and return the data
        $request = Request::create(route('api.repository.index'), 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent(), true);

        return $data;
    }
}
