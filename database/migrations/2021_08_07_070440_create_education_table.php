<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('education', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')->on("users");

            $table->unsignedBigInteger('cv_id')->unsigned();
            $table->foreign("cv_id")->references('id')->on("cvs");

            $table->string("certificate_degree_name", 255);
            $table->string("major", 255)->nullable();
            $table->date("starting_date");
            $table->date("completing_date");

            $table->string("percentage")->nullable();
            $table->string("cgpa")->nullable();
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
