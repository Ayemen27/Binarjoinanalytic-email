<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->boolean('to_admin')->default(0);
            $table->text('message');
            $table->string('icon', 255)->nullable();
            $table->boolean('is_read')->default(0);
            $table->string('action', 255)->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
