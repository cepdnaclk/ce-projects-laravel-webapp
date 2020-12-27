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
        return [Category::getByCode($this->main_category)];//;$this->belongsToMany(Category::class);
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

    public static function getByRepoTitle($repo)
    {
        return Project::where('repo_name', $repo)->first();
    }

    public function syncProject()
    {
        $updated = strtotime($this->updated_at);
        $now = time();
        $diff = floor(($now - $updated) / 60);
        $cacheTime = 1440; // a day // env('PROJECT_CACHE_TIME');

        // if the difference is greater then $cacheTime, it will automatically update the project details from GitHub

        // TODO: remove language and contributor test and implement it using Vue

        if ($diff >= $cacheTime || $this->contributorData == null || $this->languageData == null) {
            $request = Request::create(route('api.update.singleProjectWithCategory', [$this->organization, $this->repo_name, $this->main_category]), 'GET');
            $response = Route::dispatch($request);

            return ($response->getStatusCode() == 200);
        } else {
            return 0;
        }
    }

    // TODO: Need to check the usage and remove if no use in future
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
        return self::getRepoLanguages($this->organization, $this->repo_name);
    }

    public function getContributors()
    {
        return self::getRepoContributors($this->organization, $this->repo_name);
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
        /*$base = Project::all();
        $base->each(function ($item, $key) {
            $item->categories()->detach();
            $item->delete();
        });*/
    }

    // The the list of contributors on a given repository
    public static function getRepoContributors($organization, $title)
    {
        // Ex: http://projects.ce.pdn.ac.lk/api/repository/{{organization}}/{{title}}/contributors

        try {
            $contributors = GitHub::api('repo')->contributors($organization, $title);
            return collect($contributors)->map(function ($contributor) {
                return [
                    'username' => $contributor['login'],
                    'avatar' => $contributor['avatar_url'],
                    'url' => $contributor['html_url'],

                    /*'name' => $contributor['name'],*/
                    /*'data' => $contributor,*/
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    // The the list of languages used on a given repository
    public static function getRepoLanguages($organization, $title)
    {
        // Ex: http://projects.ce.pdn.ac.lk/api/repository/{{organization}}/{{title}}/languages

        try {
            $lang = GitHub::api('repo')->languages($organization, $title);

            return ['count' => count($lang), 'total' => array_sum($lang), 'list' => $lang];

        } catch (\Exception $e) {
            return ['count' => 0, 'total' => 0, 'list' => []];
        }
    }

    public static function prepareRepository($org, $repo, $category_code)
    {

        $parts = explode('-', $repo['name']);
        $organization = $repo['owner']['login'];

        // TODO: Need to format this name by a better way

        if (strtolower(substr($repo['name'], 0, 1)) == "e") {
            // COURSE project

            $title = Project::formatTitle(substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1]))));
            $repoName = $parts[0] . "-" . (substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1]))));
            $batch = $parts[0];
            $mainCategory = $category_code;

        } else {
            // DEPARTMENT project

            $title = str_replace('-', " ", $repo['name']);
            $repoName = $repo['name'];
            $batch = null;
            $mainCategory = $category_code;
        }

        if ($repo['has_pages']) {
            // Obtain some data from the repository, if GitHub pages already available

            $pageLink = "https://" . $org . ".github.io/" . $repo['name'];
            $imgLink = $pageLink . "/img_cover.jpg";
            $imgLink = (self::fileExists($imgLink)) ? $imgLink : '';

            $thumbLink = $pageLink . "/img_thumb.jpg";
            $thumbLink = (self::fileExists($thumbLink)) ? $thumbLink : '';
        }

        return [$repo['name'] => [
            'title' => $title,
            'name' => $repoName,
            'full_name' => $repo['name'],
            'description' => $repo['description'],
            'batch' => $batch,
            'category' => $mainCategory,
            'organization' => $organization,

            'repoLink' => $repo['html_url'],
            'pageLink' => ($repo['has_pages']) ? $pageLink : '',

            'coverImgLink' => ($repo['has_pages']) ? $imgLink : '',
            'thumbImgLink' => ($repo['has_pages']) ? $thumbLink : '',

            'has_pages' => $repo['has_pages'],
            'has_wiki' => $repo['has_wiki'],

            'private' => $repo['private'],
            'language' => $repo['language'],
            'forks' => $repo['forks'],
            'watchers' => $repo['watchers'],
            'stars' => $repo['stargazers_count'],

            'repo_created' => date_format(date_create($repo['created_at']), "Y-m-d"),
            'repo_updated' => date_format(date_create($repo['updated_at']), "Y-m-d h:i:s"),
            'default_branch' => $repo['default_branch'],
        ]];
    }

    private static function fileExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($responseCode == 200);
    }

}
