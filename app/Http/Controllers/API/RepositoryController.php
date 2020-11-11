<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use GrahamCampbell\GitHub\Facades\GitHub;
use Illuminate\Http\Request;

use GrahamCampbell\GitHub\GitHubManager;

class RepositoryController extends Controller
{
    protected $githubOrgName;

    public function __construct(GitHubManager $manager)
    {
        $this->client = $manager->connection();
        $this->paginator = new \Github\ResultPager($this->client);
        $this->githubOrgName = env('GITHUB_ORGANIZATION');
    }

    public function index()
    {
        //$d =  GitHub::api('organizations')->repositories($this->githubOrgName);
        $allRepos = $this->paginator->fetchAll($this->client->user(), 'repositories', [$this->githubOrgName]);

        //  Filter: e{batch}-{tag}-
        $pattern = "/^e\d{2}-\w*-/";// "/e\d{2}/";

        // Filter the repositories  by regex filter
        $filtered = collect($allRepos)->filter(function ($value, $key) use ($pattern) {
            return preg_match($pattern, $value['name']);
        });

        $repositories = $filtered->map(function ($repo) {
            // This will filter out unwanted parameters from the repository list

            $parts = explode('-', $repo['name']);
            $repoName = substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1])));

            return [
                'name' => $repoName,
                'fullName' => $repo['name'],
                'description' => $repo['description'],
                'batch' => $parts[0],

                'repoLink' => $repo['html_url'],
                'pageLink' => $repo['has_pages'] ? ("https://" . $this->githubOrgName . ".github.io/" . $repo['name']) : '',

                'has_pages' => $repo['has_pages'],
                'has_wiki' => $repo['has_wiki'],

                'private' => $repo['private'],
                'language' => $repo['language'],
                'forks' => $repo['forks'],
                'watchers' => $repo['watchers'],
                'created_at' => $repo['created_at'],
                'updated_at' => $repo['updated_at'],
                'default_branch' => $repo['default_branch'],

                /*'owner' => $repo['owner'],*/
            ];
        });

        //print($repositories);
        return response()->json([
            'count' => count($repositories),
            'repositories' => array_values($repositories->toArray())]);
    }

    public function show($title)
    {
        $repo = GitHub::repo()->show($this->githubOrgName, $title);
        //dd($repo);

        $parts = explode('-', $repo['name']);
        $repoName = substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1])));

        $resp = [
            'name' => $repoName,
            'fullName' => $repo['name'],
            'description' => $repo['description'],
            'batch' => $parts[0],

            'repoLink' => $repo['html_url'],
            'pageLink' => $repo['has_pages'] ? ("https://" . $this->githubOrgName . ".github.io/" . $repo['name']) : '',

            'has_pages' => $repo['has_pages'],
            'has_wiki' => $repo['has_wiki'],

            'private' => $repo['private'],
            'language' => $repo['language'],
            'forks' => $repo['forks'],
            'watchers' => $repo['watchers'],
            'default_branch' => $repo['default_branch'],

            'created_at' => $repo['created_at'],
            'updated_at' => $repo['updated_at'],
        ];

        return response()->json($resp);
    }

}
