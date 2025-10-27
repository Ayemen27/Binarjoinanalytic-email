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
        Schema::create('plugins', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('unique_name');
            $table->string('tag')->nullable();
            $table->string('logo')->nullable();
            $table->string('url')->nullable();
            $table->text('description')->nullable();
            $table->string('action')->nullable();
            $table->longText('code')->nullable();
            $table->string('version')->nullable();
            $table->string('license')->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};