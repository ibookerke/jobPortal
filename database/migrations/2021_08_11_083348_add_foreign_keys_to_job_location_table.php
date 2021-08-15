<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobLocationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_location', function (Blueprint $table) {
            $table->foreign('city_id', 'job_location_ibfk_1')->references('id')->on('cities')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('country_id', 'job_location_ibfk_2')->references('id')->on('countries')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('state_id', 'job_location_ibfk_3')->references('id')->on('states')->onUpdate('NO ACTION')->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_location', function (Blueprint $table) {
            $table->dropForeign('job_location_ibfk_1');
            $table->dropForeign('job_location_ibfk_2');
            $table->dropForeign('job_location_ibfk_3');
        });
    }
}
