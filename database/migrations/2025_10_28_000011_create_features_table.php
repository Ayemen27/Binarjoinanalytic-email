<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('features', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 255);
            $table->string('icon', 255);
            $table->longText('content')->nullable();
            $table->string('lang', 255);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->string('translate_id', 255)->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('features');
    }
};
