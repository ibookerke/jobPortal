<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_post_skills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('skill_id')->unsigned();
            $table->foreign("skill_id")->references('id')->on("skills");

            $table->unsignedBigInteger('job_post_id')->unsigned();
            $table->foreign("job_post_id")->references('id')->on("job_post");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
