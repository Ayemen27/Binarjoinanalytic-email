<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('imaps', function (Blueprint $table) {
            $table->id('id');
            $table->string('tag', 255);
            $table->string('host', 255)->nullable();
            $table->string('username', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('encryption', 255)->nullable();
            $table->integer('validate_certificates')->nullable();
            $table->integer('port')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('imaps');
    }
};
