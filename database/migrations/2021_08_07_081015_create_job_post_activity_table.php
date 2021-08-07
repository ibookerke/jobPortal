<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_post_activity', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('cv_id')->unsigned();
            $table->foreign("cv_id")->references('id')->on("cvs");

            $table->unsignedBigInteger('job_post_id')->unsigned();
            $table->foreign("job_post_id")->references('id')->on("job_post");

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
