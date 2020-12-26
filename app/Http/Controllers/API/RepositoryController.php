<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Project;
use GrahamCampbell\GitHub\Facades\GitHub;

use GrahamCampbell\GitHub\GitHubManager;

class RepositoryController extends Controller
{
    protected $githubOrgName;

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

    public function __construct(GitHubManager $manager)
    {
        $this->client = $manager->connection();
        $this->paginator = new \Github\ResultPager($this->client);
        $this->githubOrgName = env('GITHUB_ORGANIZATION');
    }

    // Show all repositories in an organization
    public function index($organization)
    {
        // Ex: http://projects.ce.pdn.ac.lk/api/repositories/{organization}

        $allRepos = $this->paginator->fetchAll($this->client->user(), 'repositories', [$organization]);

        if ($allRepos == null) return [];

        return response()->json([
            'count' => count($allRepos),
            'repositories' => $allRepos]);
    }

    // Show a specific repository
    public function show($organization, $title)
    {
        // Ex: http://projects.ce.pdn.ac.lk/api/repository/{{organization}}/{{title}}/

        $repo = GitHub::repo()->show($organization, $title);

        $organization = $repo['owner']['login'];
        $languages = Project::getRepoLanguages($organization, $title);
        $contributorArray = Project::getRepoContributors($organization, $title);

        $resp = Project::prepareRepository($this->githubOrgName, $repo, '')[$repo['name']];
        $resp['contributors'] = ['count' => count($contributorArray), 'list' => $contributorArray];
        $resp['languages'] = $languages;

        return response()->json($resp);
    }

    // Filter projects on a given category
    public function categoryFilter($category_code)
    {
        // Ex: Ex: http://projects.ce.pdn.ac.lk/repositories/filter/{category_code}

        $c = Category::where('category_code', $category_code)->first();
        $repositories = [];

        // If the category_code is not in the database
        if ($c == null) return response()->json(['count' => 0, 'repositories' => []]);

        foreach ($c->filters as $pattern) {
            // Filter with the given list of regex filters

            $allRepos = $this->paginator->fetchAll($this->client->user(), 'repositories', [$pattern['organization']]);

            $filtered = collect($allRepos)->filter(function ($value, $key) use ($pattern) {
                return preg_match("/" . $pattern['filter'] . "/", $value['name']);
            });

            $category_code = $c->category_code;

            $newRepositories = $filtered->mapWithKeys(function ($repo) use ($category_code) {
                // This will filter out unwanted parameters from the repository list
                return Project::prepareRepository($this->githubOrgName, $repo, $category_code);
            });

            // merge search results
            $repositories = array_replace($repositories, $newRepositories->toArray());
        }
        return response()->json([
                'count' => count($repositories),
                'repositories' => $repositories]
        );
    }

    public function getRepoContributors($organization, $title)
    {
        return Project::getRepoContributors($organization, $title);
    }

    // The the list of languages used on a given repository
    public function getRepoLanguages($organization, $title)
    {
        return Project::getRepoLanguages($organization, $title);
    }

    // This is a test function, for the production environment
    public function test()
    {
        /*
                $repo = GitHub::repo()->show("cepdnaclk", "projects");

                $organization = $repo['owner']['login'];

                $data = Project::prepareRepository("cepdnaclk", $repo, "fyp");
                dd($data);
        */
    }
}
