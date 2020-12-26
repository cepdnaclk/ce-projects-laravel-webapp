<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

class Category extends Model
{
    // protected $guarded = [];

    protected $casts = [
        'filters' => 'array'
    ];

    public function projects()
    {
        return Project::where('main_category', $this->category_code); //('main_category', $this->category_code); //$this->belongsToMany(Project::class, 'category_project');
    }

    public static function getByCode($category_code)
    {
        return Category::where('category_code', $category_code)->first();
    }

    public function getBatches()
    {
        $batches = DB::select("SELECT batch, COUNT(*) as count
            FROM projects as p, categories as c
            WHERE (p.main_category = '".$this->category_code."') AND (p.main_category = c.category_code)
            GROUP BY `batch` ORDER BY `batch` DESC");
        return $batches;
    }

    public static function getGithubData()
    {
        // This will make a request to internal API server and obtain and return the data
        $request = Request::create(route('api . repository . index'), 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent(), true);

        return $data;
    }
}
