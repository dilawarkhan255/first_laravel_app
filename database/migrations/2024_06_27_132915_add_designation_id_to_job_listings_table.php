<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDesignationIdToJobListingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->unsignedBigInteger('designation_id')->nullable()->after('company');
            $table->foreign('designation_id')->references('id')->on('job_designations')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['designation_id']);
            $table->dropColumn('designation_id');
        });
    }
}
