<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_subscription_usage', function (Blueprint $table) {
            $table->id('id')->integer();
            $table->unsignedInteger('subscription_id');
            $table->unsignedInteger('feature_id');
            $table->unsignedInteger('used');
            $table->timestamp('valid_until')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_subscription_usage');
    }
};
