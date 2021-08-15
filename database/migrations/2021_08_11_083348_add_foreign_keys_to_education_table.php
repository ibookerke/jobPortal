<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToEducationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('education', function (Blueprint $table) {
            $table->foreign('cv_id', 'education_ibfk_1')->references('id')->on('cvs')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('user_id', 'education_ibfk_2')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('education', function (Blueprint $table) {
            $table->dropForeign('education_ibfk_1');
            $table->dropForeign('education_ibfk_2');
        });
    }
}
