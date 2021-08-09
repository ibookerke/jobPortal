<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCompanyBusinessStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('company_business_stream', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('company_id')->unsigned();
            $table->foreign("company_id")->references('id')->on("companies");

            $table->unsignedBigInteger('business_stream_id')->unsigned();
            $table->foreign("business_stream_id")->references('id')->on("business_stream");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('company_business_stream');
    }
}
