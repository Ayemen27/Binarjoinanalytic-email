<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create(config('lobage.tables.plan_subscription_usage'), function (Blueprint $table) {
            $table->increments('id');
            $table->integer('subscription_id')->unsigned();
            $table->integer('feature_id')->unsigned();
            $table->unsignedInteger('used');
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();


            $table->unique(['subscription_id', 'feature_id']);
            $table->foreign('subscription_id')->references('id')->on(config('lobage.tables.plan_subscriptions'))
                ->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('feature_id')->references('id')->on(config('lobage.tables.plan_features'))
                ->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists(config('lobage.tables.plan_subscription_usage'));
    }
};