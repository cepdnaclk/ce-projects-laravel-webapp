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
        $languages = $this->getRepoLanguages($organization, $title);
        $contributorArray = $this->getRepoContributors($organization, $title);

        $resp = $this->prepareRepository($repo, '')[$repo['name']];
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
                return $this->prepareRepository($repo,$category_code);
            });

            // merge search results
            $repositories = array_replace($repositories, $newRepositories->toArray());
        }
        return response()->json([
                'count' => count($repositories),
                'repositories' => $repositories]
        );
    }

    // Prepare repository bu removing unwanted data
    private function prepareRepository($repo, $category_code)
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

            $pageLink = "https://" . $organization . ".github.io/" . $repo['name'];
            $imgLink = $pageLink . "/img_cover.jpg";
            $imgLink = ($this->fileExists($imgLink)) ? $imgLink : '';

            $thumbLink = $pageLink . "/img_thumb.jpg";
            $thumbLink = ($this->fileExists($thumbLink)) ? $thumbLink : '';
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

    // Check for the existence of a given URL
    private function fileExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($responseCode == 200);
    }

    // The the list of contributors on a given repository
    public function getRepoContributors($organization, $title)
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
    public function getRepoLanguages($organization, $title)
    {
        // Ex: http://projects.ce.pdn.ac.lk/api/repository/{{organization}}/{{title}}/languages

        try {
            $lang = GitHub::api('repo')->languages($organization, $title);

            return ['count' => count($lang), 'total' => array_sum($lang), 'list' => $lang];

        } catch (\Exception $e) {
            return ['count' => 0, 'total' => 0, 'list' => []];
        }
    }

    // This is a test function, for the production environment
    public function test($organization, $title)
    {

        $url = "https://$organization.github.io/$title/data/index.json";

        $client = new \GuzzleHttp\Client();

        try {
            $response = $client->request('GET', $url);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                dd($data);
            }
        } catch (\Exception $ex) {

        }

    }
}
