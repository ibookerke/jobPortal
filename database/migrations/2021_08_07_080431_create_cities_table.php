<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->string("name", 255);

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
