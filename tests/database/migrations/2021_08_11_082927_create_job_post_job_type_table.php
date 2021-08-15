<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->bigInteger('id')->primary();
            $table->unsignedBigInteger('job_post_id');
            $table->unsignedBigInteger('job_type_id');
            $table->foreign('job_post_id', 'job_post_job_type_ibfk_4')->references('id')->on('job_post')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('job_type_id', 'job_post_job_type_ibfk_5')->references('id')->on('job_type')->onDelete('cascade')->onUpdate('restrict');
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
