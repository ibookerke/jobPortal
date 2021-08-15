<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('cv_id');
            $table->string('certificate_degree_name');
            $table->string('major')->nullable();
            $table->date('starting_date');
            $table->date('completing_date');
            $table->string('percentage')->nullable();
            $table->string('cgpa')->nullable();
            $table->foreign('cv_id', 'education_ibfk_1')->references('id')->on('cvs')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('user_id', 'education_ibfk_2')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('education');
    }
}
