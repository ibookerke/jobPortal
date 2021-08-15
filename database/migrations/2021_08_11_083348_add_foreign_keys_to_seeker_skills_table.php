<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToSeekerSkillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('seeker_skills', function (Blueprint $table) {
            $table->foreign('skill_id', 'seeker_skills_ibfk_1')->references('id')->on('skills')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('cv_id', 'seeker_skills_ibfk_2')->references('id')->on('cvs')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('seeker_skills', function (Blueprint $table) {
            $table->dropForeign('seeker_skills_ibfk_1');
            $table->dropForeign('seeker_skills_ibfk_2');
        });
    }
}
