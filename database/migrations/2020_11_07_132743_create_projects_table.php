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
            $table->text('desc');
            $table->string('repository')->unique(); // repository url, full
            $table->string('link')->unique();       // Name shown in the URL,
            $table->string('image')->nullable();    // Relative or absolute URL of the image
            $table->char('batch',3);         // Ex: E15
            $table->enum('status', ['ON_GOING', 'COMPLETED'])->default('ON_GOING');
            $table->dateTime('last_update')->nullable();
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
