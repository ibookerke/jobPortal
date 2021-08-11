<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobPostJobTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_post_job_type', function (Blueprint $table) {
            $table->bigInteger('id', true);
            $table->unsignedBigInteger('job_post_id')->index('job_post_id');
            $table->unsignedBigInteger('job_type_id')->index('job_type_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_post_job_type');
    }
}
