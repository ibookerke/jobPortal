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
            $table->bigIncrements('id');
            $table->unsignedBigInteger('cv_id')->index('job_post_activity_cv_id_foreign');
            $table->unsignedBigInteger('job_post_id')->index('job_post_activity_job_post_id_foreign');
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
        Schema::dropIfExists('job_post_activity');
    }
}
