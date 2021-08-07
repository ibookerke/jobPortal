<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_languages', function (Blueprint $table) {
            $table->id();

            $table->char("level", 1);

            $table->unsignedBigInteger('job_post_id')->unsigned();
            $table->foreign("job_post_id")->references('id')->on("job_post");

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
        Schema::dropIfExists('job_languages');
    }
}
