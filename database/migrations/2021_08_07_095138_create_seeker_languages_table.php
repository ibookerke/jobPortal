<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeekerLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seeker_languages', function (Blueprint $table) {
            $table->id();

            $table->char("level", 1);

            $table->unsignedBigInteger('cv_id')->unsigned();
            $table->foreign("cv_id")->references('id')->on("cvs");

            $table->unsignedBigInteger('language_id')->unsigned();
            $table->foreign("language_id")->references('id')->on("languages");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seeker_languages');
    }
}
