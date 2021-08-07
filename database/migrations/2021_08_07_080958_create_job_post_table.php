<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')->on("users");

            $table->unsignedBigInteger('job_type_id')->unsigned();
            $table->foreign("job_type_id")->references('id')->on("job_type");

            $table->unsignedBigInteger('company_id')->unsigned();
            $table->foreign("company_id")->references('id')->on("companies");

            $table->unsignedBigInteger('location_id')->unsigned();
            $table->foreign("location_id")->references('id')->on("job_location");

            $table->char("is_active", 1);
            $table->string("job_description", 3000);

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
