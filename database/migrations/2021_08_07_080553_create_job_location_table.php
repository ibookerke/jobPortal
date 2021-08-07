<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_location', function (Blueprint $table) {
            $table->id();
            $table->string("address", 255);

            $table->unsignedBigInteger('city_id')->unsigned();
            $table->foreign("city_id")->references('id')->on("cities");

            $table->unsignedBigInteger('state_id')->unsigned();
            $table->foreign("state_id")->references('id')->on("states");

            $table->unsignedBigInteger('country_id')->unsigned();
            $table->foreign("country_id")->references('id')->on("countries");

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
