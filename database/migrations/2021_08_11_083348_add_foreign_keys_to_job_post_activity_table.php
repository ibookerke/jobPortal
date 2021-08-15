<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobPostActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_post_activity', function (Blueprint $table) {
            $table->foreign('cv_id')->references('id')->on('cvs');
            $table->foreign('job_post_id')->references('id')->on('job_post');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_post_activity', function (Blueprint $table) {
            $table->dropForeign('job_post_activity_cv_id_foreign');
            $table->dropForeign('job_post_activity_job_post_id_foreign');
        });
    }
}
