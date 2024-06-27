<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropDesignationIdFromJobListingsTable extends Migration
{
    public function up()
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->dropForeign(['designation_id']); // Ensure this matches the correct foreign key name
            $table->dropColumn('designation_id');
        });
    }

    public function down()
    {
        Schema::table('job_listings', function (Blueprint $table) {
            $table->unsignedBigInteger('designation_id')->nullable();
            $table->foreign('designation_id')->references('id')->on('job_designations')->onDelete('set null');
        });
    }
}