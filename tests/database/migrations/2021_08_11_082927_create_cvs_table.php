<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('user_id');
            $table->string('job_title');
            $table->string('firstname');
            $table->string('lastname');
            $table->date('date_of_birth')->nullable();
            $table->char('gender', 1)->nullable();
            $table->string('phone', 20)->nullable();
            $table->decimal('salary', 10, 0)->nullable();
            $table->timestamps();
            $table->foreign('user_id', 'cvs_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cvs');
    }
}
