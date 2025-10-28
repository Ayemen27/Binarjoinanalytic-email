<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->id('id');
            $table->string('name', 255);
            $table->string('icon', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->string('lang', 255);
            $table->integer('position')->default(0);
            $table->integer('parent')->default(0);
            $table->integer('type')->default(0);
            $table->integer('is_external')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
