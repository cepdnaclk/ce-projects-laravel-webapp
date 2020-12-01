<?php

namespace App;

use GrahamCampbell\GitHub\Facades\GitHub;
use Http\Client\Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class Project extends Model
{
    // protected $guarded = [];
    protected $casts = [
        'languageData' => 'array',
        'contributorData' => 'array',
        'students' => 'array',
        'supervisors' => 'array',
    ];

    // Return the main category of the project
    public function getMainCategory()
    {
        return $this->main_category;
    }

    // Get a list of categories that the project is registered
    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    // Get a list of students
    /*public function students()
    {
        return $this->hasMany(Student::class);
    }*/

    // Get a list of supervisors
    /*public function supervisors()
    {
        return $this->hasMany(Supervisor::class);
    }*/

    // ------------------------------------------------------------------------

    public function getParentCategory()
    {
        return Category::getByCode($this->main_category);
    }

    public static function getByBatch($batch)
    {
        return Project::where('batch', $batch)->get();
    }

    public static function getByName($proj_name)
    {
        return Project::where('name', $proj_name)->first();
    }

    public function syncProject()
    {
        $updated = strtotime($this->updated_at);
        $now = time();
        $diff = floor(($now - $updated) / 60);
        $cacheTime = 1440; // a day // env('PROJECT_CACHE_TIME');

        // if the difference is greater then $cacheTime, it will automatically update the project details from GitHub

        if ($diff >= $cacheTime || $this->contributorData == null || $this->languageData == null) {
            $request = Request::create(route('api.update.singleProjectWithCategory', [$this->organization, $this->repo_name, $this->main_category]), 'GET');
            $response = Route::dispatch($request);

            return ($response->getStatusCode() == 200);
        } else {
            return 0;
        }
    }

    public function getGithubData()
    {
        // This will make a request to internal API server and obtain and return the data
        $request = Request::create(route('api.repository.show', [$this->organization, $this->repo_name]), 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent(), true);

        return $data;
    }

    public function getLanguages()
    {
        $request = Request::create(route('api.repository.languages', [$this->organization, $this->repo_name]), 'GET');
        $response = Route::dispatch($request);
        $data = json_decode($response->getContent(), true);

        return $data;
    }

    public function getContributors()
    {
        $request = Request::create(route('api.repository.contributors', [$this->organization, $this->repo_name]), 'GET');
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

    public static function deleteAll()
    {
        $base = Project::all();
        $base->each(function ($item, $key) {
            $item->categories()->detach();
            $item->delete();
        });
    }

}
