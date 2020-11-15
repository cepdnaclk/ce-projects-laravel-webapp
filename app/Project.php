<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Project extends Model
{
    // protected $guarded = [];

    public function getCategories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }


    public static function getByBatch($batch){

        return Project::where('batch', $batch)->get();
    }

    public static function getBrowserLink($title)
    {
        // TODO: need to check for duplicates
        return preg_replace('/\W+/', '-', ($title));
    }

    public static function getGithubData($title)
    {
        // This will make a request to internal API server and obtain and return the data
        $request = Request::create(route('api.repository.show', $title), 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent(), true);

        return $data;
    }


    public static function formatTitle($link_title)
    {
        $smallwordsarray = array(
            'of', 'a', 'the', 'and', 'an', 'or', 'nor', 'but', 'is', 'if', 'then', 'else', 'when',
            'at', 'from', 'by', 'on', 'off', 'for', 'in', 'out', 'over', 'to', 'into', 'with'
        );
        $words = explode('-', $link_title);

        foreach ($words as $key => $word) {
            if ($key == 0 or !in_array($word, $smallwordsarray))
                $words[$key] = ucwords($word);
        }

        $newtitle = ucfirst(implode(' ', $words));

        return $newtitle;
    }

}
