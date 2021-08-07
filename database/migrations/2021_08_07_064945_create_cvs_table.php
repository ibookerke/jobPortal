<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCvsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cvs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unsigned();
            $table->foreign("user_id")->references('id')->on("users");

            $table->string("job_title", 255);
            $table->string("firstname", 255);
            $table->string("lastname", 255);

            $table->date("date_of_birth")->nullable();
            $table->char("gender", 1)->nullable();
            $table->string("phone", 20)->nullable();
            $table->string("salary", 255)->nullable();
            $table->char("currency", 50)->nullable();

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
