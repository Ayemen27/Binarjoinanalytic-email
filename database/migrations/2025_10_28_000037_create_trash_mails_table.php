<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trash_mails', function (Blueprint $table) {
            $table->id('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('email', 255);
            $table->string('domain', 255);
            $table->string('ip', 255)->nullable();
            $table->string('fingerprint', 255)->nullable();
            $table->timestamp('expire_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trash_mails');
    }
};
