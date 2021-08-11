<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobPostSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_post_skills', function (Blueprint $table) {
            $table->foreign('job_post_id', 'job_post_skills_ibfk_1')->references('id')->on('job_post')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('skill_id', 'job_post_skills_ibfk_2')->references('id')->on('skills')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_post_skills', function (Blueprint $table) {
            $table->dropForeign('job_post_skills_ibfk_1');
            $table->dropForeign('job_post_skills_ibfk_2');
        });
    }
}
