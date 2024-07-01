<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('job_listings', function (Blueprint $table) {
        $table->unsignedBigInteger('designation_id')->nullable()->after('company'); // Make it nullable
        $table->foreign('designation_id')->references('id')->on('job_designations')->onDelete('cascade');
    });
}

public function down()
{
    Schema::table('job_listings', function (Blueprint $table) {
        $table->dropForeign(['designation_id']);
        $table->dropColumn('designation_id');
    });
}

};