<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->unsignedBigInteger('user_id')->unique('companies_user_id_unique');
            $table->string('company_name', 100);
            $table->string('profile_description', 1000);
            $table->date('establishment_date')->nullable();
            $table->string('company_website_url', 500)->nullable();
            $table->string('image')->nullable();
            $table->timestamps();
            $table->foreign('user_id', 'companies_ibfk_1')->references('id')->on('users')->onDelete('cascade')->onUpdate('restrict');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('companies');
    }
}
