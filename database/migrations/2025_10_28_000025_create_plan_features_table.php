<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_features', function (Blueprint $table) {
            $table->id('id')->integer();
            $table->string('tag', 255);
            $table->unsignedInteger('plan_id');
            $table->string('name', 255);
            $table->string('description', 255)->nullable();
            $table->string('value', 255);
            $table->boolean('is_unlimited')->default(0);
            $table->unsignedSmallInteger('resettable_period')->default(0);
            $table->string('resettable_interval', 255)->default('month');
            $table->unsignedMediumInteger('sort_order')->default(0);
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_features');
    }
};
