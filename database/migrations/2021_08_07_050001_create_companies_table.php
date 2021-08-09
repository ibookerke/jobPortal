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
            $table->string('profile_description', 1000);
            $table->unsignedBigInteger('business_stream_id')->unsigned();
            $table->foreign("business_stream_id")->references('id')->on("business_stream");
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
