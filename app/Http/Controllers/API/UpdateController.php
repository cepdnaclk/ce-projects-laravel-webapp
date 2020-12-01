<?php

namespace App\Http\Controllers\API;

use App\Category;
use App\Http\Controllers\Controller;
use App\Project;
use GrahamCampbell\GitHub\GitHubManager;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

class UpdateController extends Controller
{
    protected $baseRepository = "https://nuwanj.github.io/ce-projects-data-repository";

    protected $client;
    protected $paginator;

    public function __construct(GitHubManager $manager)
    {
        set_time_limit(0);      // No timeout for the script execution

        $this->client = $manager->connection();
        $this->paginator = new \Github\ResultPager($this->client);
    }

    public function updateCategories()
    {
        $url = $this->baseRepository . "/data/categories/list.json";
        $client = new \GuzzleHttp\Client();
        $resp = [];

        try {
            $response = $client->request('GET', $url);

            if ($response->getStatusCode() == 200) {
                $data = json_decode($response->getBody(), true);
                Category::truncate();

                foreach ($data as $key => $category) {
                    // fetch each category, one by one and add to the categories table

                    $categoryURL = $this->baseRepository . "/data/categories/" . $key . "/index.json";
                    $response = $client->request('GET', $categoryURL);
                    $catData = json_decode($response->getBody(), true);

                    $coverURL = $this->baseRepository . "/data/categories/" . $key . "/" . $catData['images']['cover'];
                    $thumbURL = $this->baseRepository . "/data/categories/" . $key . "/" . $catData['images']['thumbnail'];

                    if ($this->fileExists($coverURL) == false) {
                        // Use the default one
                        $coverURL = $this->baseRepository . "/data/categories/template/cover_page.jpg";
                    }

                    if ($this->fileExists($thumbURL) == false) {
                        // Use the default one
                        $thumbURL = $this->baseRepository . "/data/categories/template/thumbnail.jpg";
                    }

                    if ($catData != null) {
                        // TODO: require some validation

                        $c = new Category();
                        $c->title = $catData['title'];
                        $c->type = $catData['type'];
                        $c->category_code = $catData['code'];
                        $c->description = $catData['description'];
                        $c->cover_image = $coverURL;
                        $c->thumb_image = $thumbURL;
                        $c->filters = $catData['filters'];
                        $c->contact = $catData['contact'];
                        $c->save();

                        $resp['data'][$key] = $catData;

                    } else {
                        $resp['data'][$key] = ["error" => "$categoryURL not found"];
                    }
                }
            } else {
                $resp = ["error" => "file not found"];
            }
            $resp['status'] = "Success";

        } catch (Exception $e) {
            $resp['status'] = "Error";
            $resp['statusDetails'] = $e->getMessage();

        } finally {
            return response()->json($resp);
        }
    }

    public function updateProjects()
    {
        $categories = Category::all();
        Project::deleteAll();
        $resp = [];

        foreach ($categories as $category) {
            try {
                $category_code = $category->category_code;

                // foreach project
                // $request = Request::create(route('api.update.singleProject', [$this->organization, $this->repo_name]), 'GET');
                // $response = Route::dispatch($request);
                $repositories = [];

                // If the category_code is not in the database

                foreach ($category->filters as $pattern) {
                    // Filter with the given list of regex filters

                    $allRepos = $this->paginator->fetchAll($this->client->user(), 'repositories', [$pattern['organization']]);

                    $filtered = collect($allRepos)->filter(function ($value, $key) use ($pattern) {
                        return preg_match("/" . $pattern['filter'] . "/", $value['name']);
                    });

                    $category_code = $category->category_code;

                    $newRepositories = $filtered->mapWithKeys(function ($repo) use ($category_code) {
                        // This will filter out unwanted parameters from the repository list
                        $request = Request::create(route('api.update.singleProjectWithCategory', [$repo['owner']['login'], $repo['name'], $category_code]), 'GET');
                        $response = Route::dispatch($request);
                        $repoData = json_decode($response->getContent(), true);
                        return [$repoData['name'] => $repoData];//$this->prepareRepository($repo,$category_code);
                    });

                    // merge search results
                    $repositories = array_replace($repositories, $newRepositories->toArray());
                }

                $resp[$category_code] = $repositories;

            } catch (\Exception $ex) {
                // Error handler
            }
        }
        return response()->json($resp);
    }

    public function softUpdateProjects(){

        $categories = Category::all();
        $projects = Project::where('status','ACTIVE')->update(['status'=>'INACTIVE']);
        $resp = [];

        foreach ($categories as $category) {
            try {
                $category_code = $category->category_code;

                $request = Request::create(route('api.repository.filter', $category_code), 'GET');
                $response = Route::dispatch($request);
                $data = json_decode($response->getContent(), true);

                foreach ($data['repositories'] as $key => $project) {
                    // TODO: require some validation

                    $isNew = false;
                    $p = Project::getByName($project['name']);

                    // If project isn't exists, create one
                    if ($p == null) {
                        $isNew = true;
                        $p = new Project();
                    }

                    $p->title = $project['title'];
                    $p->status ='ACTIVE';
                    $p->name = $project['name'];
                    $p->repo_name = $project['full_name'];
                    $p->organization = $project['organization'];

                    $p->description = $project['description'];

                    $p->batch = $project['batch'];
                    $p->main_category = $project['category'];

                    $p->repoLink = $project['repoLink'];
                    $p->pageLink = $project['pageLink'];

                    $p->has_pages = $project['has_pages'];
                    $p->has_wiki = $project['has_wiki'];
                    $p->private = $project['private'];

                    $p->language = $project['language'];

                    $p->forks = $project['forks'];
                    $p->watchers = $project['watchers'];
                    $p->stars = $project['stars'];

                    //$p->languageData = Project::getRepoContributors();
                    //$p->contributorData = Project::getRepoContributors();

                    // Find for repository own image. If there isn't, use the default one
                    $p->image = ($project['coverImgLink'] != "") ? $project['coverImgLink'] : $category->cover_image;
                    $p->thumbnail = ($project['thumbImgLink'] != "") ? $project['thumbImgLink'] : $category->thumb_image;

                    $p->repo_created = date("Y-m-d h:i:s", strtotime($project['repo_created']));
                    $p->repo_updated = date("Y-m-d h:i:s", strtotime($project['repo_updated']));
                    $p->default_branch = $project['default_branch'];

                    $p->save();

                    if($isNew){
                        $p->categories()->attach($category->id);
                    }else{
                        //$p->categories()->sync();
                    }

                    $resp[$category_code][$project['title']] = $p;

                }

            } catch (\Exception $ex) {
                // Error handler
            }
        }

        // Delete non-existing projects
        $deletedProjects = Project::where('status','INACTIVE')->delete();
        return response()->json($resp);

        }

    public function updateSingleProject($organization, $title, $categoryParam=null)
    {
        // TODO: require some validation

        $request = Request::create(route('api.repository.show', [$organization, $title]), 'GET');
        $response = Route::dispatch($request);

        if ($response->getStatusCode() == 200) {

            $project = json_decode($response->getContent(), true);
            $categoryCode = ($categoryParam==null) ? $project['category'] : $categoryParam;
            $category = Category::getByCode($categoryCode);

            // Select default cover and thumbnail images
            if ($category == null) {
                $category_cover = $this->baseRepository . "/data/categories/template/cover_page.jpg";
                $category_thumb = $this->baseRepository . "/data/categories/template/thumbnail.jpg";
            } else {
                $category_cover = $category->cover_image;
                $category_thumb = $category->thumb_image;
            }

            $p = Project::getByName($project['name']);

            // If project isn't exists, create one
            if ($p == null) $p = new Project();

            $p->title = $project['title'];
            $p->name = $project['name'];
            $p->repo_name = $project['full_name'];
            $p->organization = $project['organization'];
            $p->description = $project['description'];
            $p->batch = $project['batch'];
            $p->main_category = ($categoryParam != null) ? $categoryParam : $project['category'];

            $p->repoLink = $project['repoLink'];
            $p->pageLink = $project['pageLink'];

            $p->has_pages = $project['has_pages'];
            $p->has_wiki = $project['has_wiki'];
            $p->private = $project['private'];

            $p->language = $project['language'];

            $p->forks = $project['forks'];
            $p->watchers = $project['watchers'];
            $p->stars = $project['stars'];

            // Find for repository own image. If there isn't, use the default one defined for the category
            $p->image = ($project['coverImgLink'] != "") ? $project['coverImgLink'] : $category_cover;
            $p->thumbnail = ($project['thumbImgLink'] != "") ? $project['thumbImgLink'] : $category_thumb;

            $p->languageData = $project['languages'];
            $p->contributorData = $project['contributors'];

            $p->repo_created = date("Y-m-d h:i:s", strtotime($project['repo_created']));
            $p->repo_updated = date("Y-m-d h:i:s", strtotime($project['repo_updated']));
            $p->default_branch = $project['default_branch'];

            $p->save();

            // TODO: Need to care this
            if($categoryParam != null) {
                $p->categories()->syncWithoutDetaching($category->id);
            }

            // Get project own configurations
            $projURL = "https://$organization.github.io/$title/data/";
            $client = new \GuzzleHttp\Client();

            try {
                $response = $client->request('GET', $projURL);

                if ($response->getStatusCode() == 200) {
                    $data = json_decode($response->getBody(), true);
                    $p->title = $data['title'];
                    $p->description = $data['description'];

                    $p->image = $projURL . $data['image'];
                    $p->thumbnail = $projURL . $data['thumbnail'];

                    $p->students = $data['team'];
                    $p->supervisors = $data['supervisors'];

                    $p->save();
                }
            } catch (\Exception $ex) {

            }

            $resp = $p;

        } else {
            // not found; delete the project from the database
            // TODO: Test the functionality
            $resp['error'] = "not found";
        }

        return response()->json($resp);
    }

    private function fileExists($url)
    {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_exec($ch);
        $responseCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        return ($responseCode == 200);
    }


}
