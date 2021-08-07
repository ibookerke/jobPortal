<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSeekerSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seeker_skills', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('skill_id')->unsigned();
            $table->foreign("skill_id")->references('id')->on("skills");

            $table->unsignedBigInteger('cv_id')->unsigned();
            $table->foreign("cv_id")->references('id')->on("cvs");

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

    }
}
