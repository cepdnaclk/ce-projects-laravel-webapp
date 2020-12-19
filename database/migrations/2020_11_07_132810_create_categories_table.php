<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categories', function (Blueprint $table) {
            //$table->engine = 'InnoDB';
            $table->id();
            
            $table->string('category_code')->unique();        // Ex: FYP, 3YP
            $table->string('title');
            $table->enum('type', ['COURSE', 'DEPARTMENT']);
            $table->text('description');
            $table->string('cover_image')->nullable();
            $table->string('thumb_image')->nullable();
            $table->json('filters')->nullable();
            $table->text('contact')->nullable();
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
        Schema::dropIfExists('categories');
    }
}
