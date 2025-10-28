<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('seo', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 255);
            $table->text('description')->nullable();
            $table->text('keyword')->nullable();
            $table->string('author', 255)->nullable();
            $table->string('image', 255)->nullable();
            $table->string('lang', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
