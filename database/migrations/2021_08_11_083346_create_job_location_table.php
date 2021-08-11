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
            $table->bigIncrements('id');
            $table->string('address');
            $table->unsignedBigInteger('city_id')->index('city_id');
            $table->unsignedBigInteger('state_id')->index('state_id');
            $table->unsignedBigInteger('country_id')->index('country_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('job_location');
    }
}
