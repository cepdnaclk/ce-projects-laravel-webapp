<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Project;
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


        $repositories = $filtered->mapWithKeys(function ($repo) {
            // This will filter out unwanted parameters from the repository list
            return $this->filterRepo($repo);
        });

        //print($repositories);
        return response()->json([
            'count' => count($repositories),
            'repositories' => $repositories]);
    }

    public function show($title)
    {
        // Ex: http://ce-projects.nuwanjaliyagoda.com/api/repository/{{title}}

        $repo = GitHub::repo()->show($this->githubOrgName, $title);

        $contributors = GitHub::api('repo')->contributors($this->githubOrgName, $title);
        $languages = GitHub::api('repo')->languages($this->githubOrgName, $title);
        //$communityProfile = GitHub::api('repo')->communityProfile($this->githubOrgName, $title);

        $contributorArray = collect($contributors)->map(function ($contributor) {
            return [
                'username' => $contributor['login'],
                'avatar' => $contributor['avatar_url'],
                /*'name' => $contributor['name'],*/
                'url' => $contributor['html_url'],
                /*'data' => $contributor,*/
            ];
        });


        // Contributors:  https://api.github.com/repos/nuwanj/FYP-simulator-gui/contributors
        //      avatar_url, html_url, login, contributions

        // Languages:  https://api.github.com/repos/nuwanj/FYP-simulator-gui/languages
        //      list of languages used

        // User: https://api.github.com/users/{username}
        //

        // Search: https://api.github.com/search/code?q=arduino+:cepdnaclk
        // Search: https://api.github.com/search/repository?q=arduino+org:cepdnaclk
        //      https://docs.github.com/en/free-pro-team@latest/rest/reference/search
        //      https://docs.github.com/en/free-pro-team@latest/rest/reference/search#constructing-a-search-query


        $resp = $this->filterRepo($repo);

        $resp['contributors'] = [
            'count' => count($contributorArray),
            'list' => $contributorArray
        ];
        $resp['languages'] = [
            'main' => $repo['language'],
            'count' => count($languages),
            'total' => array_sum($languages),
            'list' => $languages
        ];

        return response()->json($resp);
    }

    public function categoryIndex($category_code)
    {

        $c = Category::where('category_code', $category_code)->first();

        if ($c == null) {
            // If the category_code is not in the database
            return response()->json(['count' => 0, 'repositories' => []]);
        }

        //return response()->json([$c->filters]);

        // Read all the available repositories in the organization
        $allRepos = $this->paginator->fetchAll($this->client->user(), 'repositories', [$this->githubOrgName]);
        $repositories = [];

        // Filter with the given list of regex filters
        foreach ($c->filters as $pattern) {
            //echo $pattern."<br>";

            $filtered = collect($allRepos)->filter(function ($value, $key) use ($pattern) {
                return preg_match("/" . $pattern . "/", $value['name']);
            });

            $newRepositories = $filtered->mapWithKeys(function ($repo) {
                // This will filter out unwanted parameters from the repository list
                return $this->filterRepo($repo);
            });

            // merge search results
            $repositories = array_replace($repositories, $newRepositories->toArray());
        }
        return response()->json([
                'count' => count($repositories),
                'repositories' => $repositories]
        );

    }

    private function filterRepo($repo)
    {
        $parts = explode('-', $repo['name']);

        // TODO: Need to format this in better way
        $title = Project::formatTitle(substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1]))));

        $repoName = strtolower(substr($repo['name'], (strlen($parts[0]) + 2 + strlen($parts[1]))));

        return [$repo['name'] => [
            'title' => $title,
            'name' => $parts[0]."-".$repoName,
            'full_name' => $repo['name'],
            'description' => $repo['description'],
            'batch' => $parts[0],
            'category' => $parts[1],

            'repoLink' => $repo['html_url'],
            'pageLink' => $repo['has_pages'] ? ("https://" . $this->githubOrgName . ".github.io/" . $repo['name']) : '',

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


}
