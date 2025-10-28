<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('translation_jobs', function (Blueprint $table) {
            $table->id('id');
            $table->string('job_id', 255);
            $table->integer('total_chunks')->default(0);
            $table->integer('processed_chunks')->default(0);
            $table->integer('success_count')->default(0);
            $table->integer('error_count')->default(0);
            $table->integer('total_characters')->default(0);
            $table->longText('results')->nullable();
            $table->string('message', 255)->nullable();
            $table->string('status', 255)->default('pending');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('translation_jobs');
    }
};
