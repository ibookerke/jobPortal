<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('job_post_id');
            $table->foreign('job_post_id', 'job_post_skills_ibfk_1')->references('id')->on('job_post')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('skill_id', 'job_post_skills_ibfk_2')->references('id')->on('skills')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_post_skills');
    }
}
