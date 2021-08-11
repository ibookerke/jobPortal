<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompaniesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('companies', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('user_id')->unsigned()->unique();
            $table->foreign("user_id")->references('id')->on("users");

            $table->string('company_name', 100);
            $table->text('profile_description');

            $table->date('establishment_date')->nullable();
            $table->string('company_website_url', 500)->nullable();
            $table->string("image", 255)->nullable();
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
