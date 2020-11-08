<?php

namespace App\Http\Controllers;

// https://laravel.com/docs/8.x/http-client
use App\Category;
use App\Project;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

use Illuminate\Http\Request;

class MaintainController extends Controller
{

    protected $githubOrg = "https://github.com/cepdnaclk/";
    protected $githubPage = "https://cepdnaclk.github.io/";

    protected $baseRepository = "http://nuwanj.github.io/ce-projects-data-repository/";

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function updateCategories()
    {
        $url = $this->baseRepository . "data/categories/list.json";

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody(), true);

        if ($response->getStatusCode() != 200 || $data != null) {

            //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Category::truncate(); // clear the table

            foreach ($data as $key => $category) {
                // fetch each project, one by one and add to the categories table
                $categoryURL = $this->baseRepository . "data/categories/" . $key . "/index.json";
                $response = $client->request('GET', $categoryURL);
                $catData = json_decode($response->getBody(), true);

                if ($catData != null) {

                    // TODO: Need to run validator before create

                    $c = new Category();
                    $c->title = $catData['title'];
                    $c->category_code = $catData['code'];
                    $c->desc = $catData['description'];
                    $c->cover_image = $catData['images']['cover'];
                    $c->save();
                    echo "$key: success<br>";
                } else {
                    echo "$key: not found<br>";
                }
            }
        } else {
            echo "file not found";
        }

        //dd($data);

    }


    public function updateProjects()
    {
        $url = $this->baseRepository . "data/projects/list.json";

        $client = new \GuzzleHttp\Client();
        $response = $client->request('GET', $url);
        $data = json_decode($response->getBody(), true);

        if ($response->getStatusCode() != 200 || $data != null) {

            //DB::statement('SET FOREIGN_KEY_CHECKS=0;');
            Project::truncate(); // clear the table

            foreach ($data as $key => $project) {
                // fetch each project, one by one and add to the projects table

                $projectURL = $this->baseRepository . "data/projects/" . $key . "/index.json";
                $response = $client->request('GET', $projectURL);
                $projectData = json_decode($response->getBody(), true);

                if ($projectData != null) {

                    // TODO: Need to run validator before create

                    $c = new Project();
                    $c->title = $projectData['title'];
                    $c->desc = $projectData['description'];
                    $c->repository = $projectData['url'];
                    $c->link = Project::getBrowserLink($projectData['title']);
                    $c->image = $projectData['images']['cover'];
                    $c->repository = $projectData['batch'];
                    $c->repository = 'ON_GOING';
                    $c->repository = $projectData['lastUpdate'];
                    $c->save();

                    echo "$key: success<br>";
                } else {
                    echo "$key: failed<br>";
                }
            }

        } else {
            echo "failed: " . $response->getStatusCode() . "<br>" . $response->getBody();
        }
    }

    public function test()
    {
        echo Project::getBrowserLink("My Sample Project");

    }
}
