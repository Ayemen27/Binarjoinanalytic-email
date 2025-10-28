<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id('id');
            $table->string('title', 255);
            $table->text('description');
            $table->string('slug', 255);
            $table->longText('content');
            $table->string('image', 255)->nullable();
            $table->string('small_image', 255)->nullable();
            $table->integer('status')->default(1);
            $table->integer('views')->default(0);
            $table->text('meta_description')->nullable();
            $table->string('meta_title', 255)->nullable();
            $table->string('tags', 255)->nullable();
            $table->string('lang', 255);
            $table->unsignedBigInteger('category_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};
