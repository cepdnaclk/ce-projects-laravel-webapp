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
        $this->client = $manager->connection();
        $this->paginator = new \Github\ResultPager($this->client);

        //$this->middleware('auth');
    }

    public function updateCategories()
    {
        set_time_limit(0);
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
                        $coverURL = $this->baseRepository . "/data/categories/template/cover_page.jpg";
                    }
                    if ($this->fileExists($thumbURL) == false) {
                        $thumbURL = $this->baseRepository . "/data/categories/template/thumbnail.jpg";
                    }

                    if ($catData != null) {

                        // TODO: Need to run validator before create

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
        set_time_limit(0);
        $categories = Category::all();
        Project::deleteAll();
        $resp = [];

        foreach ($categories as $category) {
            try {
                $category_code = $category->category_code;

                $request = Request::create(route('api.repository.filter', $category_code), 'GET');
                $response = Route::dispatch($request);
                $data = json_decode($response->getContent(), true);

                foreach ($data['repositories'] as $key => $project) {

                    // TODO: require some validation

                    $p = Project::getByName($project['name']);

                    // If project isn't exists, create one
                    if ($p == null) {
                        $p = new Project();
                    }

                    $p->title = $project['title'];
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

                    //$p->repo_created = $project['repo_created'];
                    //$p->repo_updated = $project['repo_updated'];
                    $p->default_branch = $project['default_branch'];

                    $p->save();
                    $p->categories()->attach($category->id);

                    $resp[$category_code][$project['title']] = $p;
                }

            } catch (\Exception $ex) {
                // Error handler
            }
        }
        return response()->json($resp);
    }

    public function updateSingleProjects($organization, $title)
    {
        // TODO: require some validation

        $request = Request::create(route('api.repository.show', [$organization, $title]), 'GET');
        $response = Route::dispatch($request);

        if ($response->getStatusCode() == 200) {

            $project = json_decode($response->getContent(), true);
            $category = Category::getByCode($project['category']);

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
            if ($p == null) {
                $p = new Project();
            }

            $p->title = $project['title'];
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

            // Find for repository own image. If there isn't, use the default one
            $p->image = ($project['coverImgLink'] != "") ? $project['coverImgLink'] : $category_cover;
            $p->thumbnail = ($project['thumbImgLink'] != "") ? $project['thumbImgLink'] : $category_thumb;

            $p->languageData = $project['languages'];
            $p->contributorData = $project['contributors'];

            //$p->repo_created = $project['repo_created'];
            //$p->repo_updated = $project['repo_updated'];
            $p->default_branch = $project['default_branch'];

            $p->save();
            $p->categories()->sync($category->id);

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
                    //dd($data);
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
