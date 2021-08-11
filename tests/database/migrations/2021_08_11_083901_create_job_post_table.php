<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJobPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_post', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('location_id');
            $table->boolean('is_active');
            $table->bigInteger('work_experience_type');
            $table->text('job_description');
            $table->string('job_title');
            $table->integer('salary')->nullable();
            $table->timestamps();
            $table->foreign('location_id', 'job_post_ibfk_2')->references('id')->on('job_location')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('work_experience_type', 'job_post_ibfk_4')->references('id')->on('work_experience_type')->onDelete('restrict')->onUpdate('restrict');
            $table->foreign('company_id', 'job_post_ibfk_5')->references('id')->on('companies')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('user_id', 'job_post_ibfk_6')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_post');
    }
}
