<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('company_id');
            $table->unsignedBigInteger('business_stream_id');
            $table->foreign('business_stream_id', 'company_business_stream_ibfk_1')->references('id')->on('business_stream')->onDelete('cascade')->onUpdate('restrict');
            $table->foreign('company_id', 'company_business_stream_ibfk_2')->references('id')->on('companies')->onDelete('cascade')->onUpdate('restrict');
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
