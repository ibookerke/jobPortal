<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')->on("users");

            $table->unsignedBigInteger('cv_id')->unsigned();
            $table->foreign("cv_id")->references('id')->on("cvs");

            $table->char("is_current_job", 1);
            $table->date("start_date");
            $table->date("end_date")->nullable();
            $table->string("job_title", 255);
            $table->string("company_name", 255);
            $table->string("job_description", 4000);

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
