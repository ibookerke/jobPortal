<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('cv_id');
            $table->unsignedBigInteger('job_post_id');
            $table->timestamps();
            $table->foreign('cv_id', 'job_post_activity_cv_id_foreign')->references('id')->on('cvs');
            $table->foreign('job_post_id', 'job_post_activity_job_post_id_foreign')->references('id')->on('job_post');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_post_activity');
    }
}
