<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('themes', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('unique_name', 255);
            $table->string('logo', 255)->nullable();
            $table->string('dark_logo', 255)->nullable();
            $table->string('favicon', 255)->nullable();
            $table->string('version', 255)->nullable();
            $table->string('demo', 255)->nullable();
            $table->longText('description')->nullable();
            $table->string('image', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->longText('colors')->nullable();
            $table->longText('images')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};
