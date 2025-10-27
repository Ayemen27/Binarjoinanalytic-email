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
        Schema::create('seo', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('title');
            $table->text('description')->nullable();
            $table->text('keyword')->nullable();
            $table->string('author')->nullable();
            $table->string('image')->nullable();
            $table->string('lang');
            $table->foreign('lang')->references('code')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->timestamps();
        });

        $items = array(
            ["title" => env('APP_NAME', 'Title'), "description" => "Lobage", "keyword" => "Lobage", "lang" => env('DEFAULT_LANG', 'en')],
        );
        DB::table('seo')->insert($items);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('seo');
    }
};
