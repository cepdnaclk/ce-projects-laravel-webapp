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
        // Ex: http://ce-projects.nuwanjaliyagoda.com/api/repositories

        $allRepos = $this->paginator->fetchAll($this->client->user(), 'repositories', [$this->githubOrgName]);

        //  Filter: e{batch}-{tag}-
        $pattern = "/^e\d{2}-\w*-/";// "/e\d{2}/";

        // Can use this link to check the functionality of RegEx expressions: https://regexr.com/

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
        // Ex: http://ce-projects.nuwanjaliyagoda.com/api/repository/{{title}}

        $repo = GitHub::repo()->show($this->githubOrgName, $title);

        $parts = explode('-', $repo['name']);
        $repoName = substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1])));


        // Contributors:  https://api.github.com/repos/nuwanj/FYP-simulator-gui/contributors
        //      avatar_url, html_url, login, contributions

        // Contributors:  https://api.github.com/repos/nuwanj/FYP-simulator-gui/languages
        //      list of languages used


        // Search: https://api.github.com/search/code?q=arduino+org:cepdnaclk
        // Search: https://api.github.com/search/repository?q=arduino+org:cepdnaclk
        //      https://docs.github.com/en/free-pro-team@latest/rest/reference/search
        //      https://docs.github.com/en/free-pro-team@latest/rest/reference/search#constructing-a-search-query


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
