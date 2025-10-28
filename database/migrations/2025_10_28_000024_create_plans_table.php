<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plans', function (Blueprint $table) {
            $table->id('id')->integer();
            $table->string('tag', 255);
            $table->string('name', 255);
            $table->string('description', 255)->nullable();
            $table->boolean('is_active')->default(1);
            $table->decimal('price', 8, 2)->default(0.00);
            $table->decimal('signup_fee', 8, 2)->default(0.00);
            $table->string('currency', 3);
            $table->unsignedSmallInteger('trial_period')->default(0);
            $table->string('trial_interval', 255)->default('day');
            $table->string('trial_mode', 255)->default('outside');
            $table->unsignedSmallInteger('grace_period')->default(0);
            $table->string('grace_interval', 255)->default('day');
            $table->unsignedSmallInteger('invoice_period')->default(1);
            $table->string('invoice_interval', 255)->default('month');
            $table->unsignedMediumInteger('tier')->default(0);
            $table->boolean('is_lifetime')->default(0);
            $table->boolean('is_featured')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
            $table->timestamp('deleted_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};
