<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForeignKeysToJobPostJobTypeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_post_job_type', function (Blueprint $table) {
            $table->foreign('job_post_id', 'job_post_job_type_ibfk_4')->references('id')->on('job_post')->onUpdate('NO ACTION')->onDelete('CASCADE');
            $table->foreign('job_type_id', 'job_post_job_type_ibfk_5')->references('id')->on('job_type')->onUpdate('NO ACTION')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_post_job_type', function (Blueprint $table) {
            $table->dropForeign('job_post_job_type_ibfk_4');
            $table->dropForeign('job_post_job_type_ibfk_5');
        });
    }
}
