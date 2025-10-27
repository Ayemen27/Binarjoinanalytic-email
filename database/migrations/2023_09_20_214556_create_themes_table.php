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
        Schema::create('themes', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('unique_name')->unique();
            $table->string('logo')->nullable();
            $table->string('dark_logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('version')->nullable();
            $table->string('demo')->nullable();
            $table->longText('description')->nullable();
            $table->string('image')->nullable();
            $table->boolean('status')->default(0);
            $table->longText('custom_css')->nullable();
            $table->longText('custom_js')->nullable();
            $table->longText('colors')->nullable();
            $table->longText('images')->nullable();
            $table->timestamps();
        });

        $items = array(
            [
                "version" => "1.0",
                "name" => "Basic",
                "unique_name" => "basic",
                "description" => "Basic Theme",
                "status" => "1",

            ],
        );
        DB::table('themes')->insert($items);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('themes');
    }
};