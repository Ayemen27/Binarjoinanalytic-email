<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plugins', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('unique_name', 255);
            $table->string('tag', 255)->nullable();
            $table->string('logo', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->text('description')->nullable();
            $table->string('action', 255)->nullable();
            $table->longText('code')->nullable();
            $table->string('version', 255)->nullable();
            $table->string('license', 255)->nullable();
            $table->boolean('status')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plugins');
    }
};
