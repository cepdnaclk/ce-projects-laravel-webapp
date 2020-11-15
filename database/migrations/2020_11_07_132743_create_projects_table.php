<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->string('title');                // Actual project name
            $table->string('name')->unique();       // This is used to show in the URL
            $table->text('description')->nullable();

            $table->char('batch',3);         // Ex: E15
            $table->string('category');             // Ex: 3yp, temporary column TODO: add one to many mapping

            $table->string('repoLink'); // repository url, full
            $table->string('pageLink'); // github page url, full

            $table->boolean('has_pages')->nullable();
            $table->boolean('has_wiki')->nullable();
            $table->boolean('private')->nullable();

            $table->string('language')->nullable();

            $table->smallInteger('forks')->default(0);
            $table->smallInteger('watchers')->default(0);
            $table->smallInteger('stars')->default(0);

            $table->string('image')->nullable();    // Relative or absolute URL of the image

            //$table->enum('status', ['ON_GOING', 'COMPLETED'])->default('ON_GOING');

            $table->dateTime('repo_created')->nullable();
            $table->dateTime('repo_updated')->nullable();
            $table->string('default_branch')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('projects');
    }
}
