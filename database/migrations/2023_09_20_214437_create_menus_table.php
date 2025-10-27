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
        Schema::create('menus', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('icon')->nullable();
            $table->string('url')->nullable();
            $table->string('lang');
            $table->foreign('lang')->references('code')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->integer("position")->default(0);
            $table->integer("parent")->default(0);
            $table->integer("type")->default(0);
            $table->integer("is_external")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menus');
    }
};
