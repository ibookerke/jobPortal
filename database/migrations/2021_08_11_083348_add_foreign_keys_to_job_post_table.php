<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobPostTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_post', function (Blueprint $table) {
            $table->foreign('location_id', 'job_post_ibfk_2')->references('id')->on('job_location')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('work_experience_type', 'job_post_ibfk_4')->references('id')->on('work_experience_type')->onUpdate('NO ACTION')->onDelete('NO ACTION');
            $table->foreign('company_id', 'job_post_ibfk_5')->references('id')->on('companies')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('user_id', 'job_post_ibfk_6')->references('id')->on('users')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_post', function (Blueprint $table) {
            $table->dropForeign('job_post_ibfk_2');
            $table->dropForeign('job_post_ibfk_4');
            $table->dropForeign('job_post_ibfk_5');
            $table->dropForeign('job_post_ibfk_6');
        });
    }
}
