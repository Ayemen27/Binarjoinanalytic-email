<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plan_subscriptions', function (Blueprint $table) {
            $table->id('id')->integer();
            $table->string('tag', 255);
            $table->string('subscriber_type', 255);
            $table->unsignedBigInteger('subscriber_id');
            $table->unsignedInteger('plan_id')->nullable();
            $table->string('name', 255)->nullable();
            $table->string('description', 255)->nullable();
            $table->decimal('price', 8, 2)->default(0.00);
            $table->string('currency', 3);
            $table->unsignedSmallInteger('trial_period')->default(0);
            $table->string('trial_interval', 255)->default('day');
            $table->unsignedSmallInteger('grace_period')->default(0);
            $table->string('grace_interval', 255)->default('day');
            $table->unsignedSmallInteger('invoice_period')->default(1);
            $table->string('invoice_interval', 255)->default('month');
            $table->string('payment_method', 255)->nullable()->default('free');
            $table->unsignedMediumInteger('tier')->default(0);
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamp('cancels_at')->nullable();
            $table->timestamp('canceled_at')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plan_subscriptions');
    }
};
