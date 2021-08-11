<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('skill_id');
            $table->unsignedBigInteger('cv_id');
            $table->timestamps();
            $table->foreign('skill_id', 'seeker_skills_ibfk_1')->references('id')->on('skills')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('cv_id', 'seeker_skills_ibfk_2')->references('id')->on('cvs')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seeker_skills');
    }
}
