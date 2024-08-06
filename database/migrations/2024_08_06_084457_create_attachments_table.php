<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('attachments', function (Blueprint $table) {
            $table->id();
            $table->string('attachment_type'); // Type of the model (e.g., Product, User)
            $table->unsignedBigInteger('attachable_id'); // ID of the model
            $table->string('name'); // Original file name
            $table->string('link'); // URL or path to the file
            $table->string('type'); // MIME type of the file
            $table->string('extension'); // File extension
            $table->integer('size'); // File size in bytes
            $table->timestamps();

            $table->index(['attachable_id', 'attachment_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attachments');
    }
};
