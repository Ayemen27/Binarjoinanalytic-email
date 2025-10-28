<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('sections', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('title', 255);
            $table->integer('status')->default(0);
            $table->integer('position')->default(0);
            $table->string('type', 255)->nullable();
            $table->longText('content')->nullable();
            $table->string('lang', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sections');
    }
};
