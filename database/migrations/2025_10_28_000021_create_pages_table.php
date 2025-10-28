<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pages', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 255);
            $table->string('slug', 255);
            $table->longText('content')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->integer('views')->default(0);
            $table->string('lang', 255);
            $table->integer('status')->default(1);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pages');
    }
};
