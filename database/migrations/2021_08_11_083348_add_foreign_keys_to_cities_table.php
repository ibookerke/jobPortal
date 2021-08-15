<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->foreign('country_id', 'cities_ibfk_3')->references('id')->on('countries')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('state_id', 'cities_ibfk_4')->references('id')->on('states')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cities', function (Blueprint $table) {
            $table->dropForeign('cities_ibfk_3');
            $table->dropForeign('cities_ibfk_4');
        });
    }
}
