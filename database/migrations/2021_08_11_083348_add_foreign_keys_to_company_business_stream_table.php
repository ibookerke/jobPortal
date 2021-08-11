<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToCompanyBusinessStreamTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('company_business_stream', function (Blueprint $table) {
            $table->foreign('business_stream_id', 'company_business_stream_ibfk_1')->references('id')->on('business_stream')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('company_id', 'company_business_stream_ibfk_2')->references('id')->on('companies')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('company_business_stream', function (Blueprint $table) {
            $table->dropForeign('company_business_stream_ibfk_1');
            $table->dropForeign('company_business_stream_ibfk_2');
        });
    }
}
