<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('translates', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('collection')->default('general');
            $table->text('key');
            $table->longText('value')->nullable();
            $table->boolean('type')->default(0);
            $table->string('lang');
            $table->foreign('lang')->references('code')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translates');
    }
};
