<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        // TODO: Not Implemented
        Schema::create('students', function (Blueprint $table) {
            $table->id();
            //$table->string('initials');
            $table->string('name');
            $table->string('email')->unique();                // Must be eng.pdn.ac.lk email
            $table->string('eNumber', 8)->unique();    // Ex: E/15/140
            //$table->timestamps();
        });
        */
}

/**
* Reverse the migrations.
*
* @return void
*/
    public function down()
    {
        Schema::dropIfExists('students');
    }
}
