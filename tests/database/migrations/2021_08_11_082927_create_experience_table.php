<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('experience', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cv_id');
            $table->char('is_current_job', 1);
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->string('job_title');
            $table->string('company_name');
            $table->string('job_description', 4000);
            $table->foreign('user_id', 'experience_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('cv_id', 'experience_ibfk_2')->references('id')->on('cvs')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('experience');
    }
}
