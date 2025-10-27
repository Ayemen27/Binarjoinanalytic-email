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
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string("title");
            $table->Text("description")->nullable();
            $table->string("slug")->unique();
            $table->longText("content");
            $table->string("image")->nullable();
            $table->string("small_image")->nullable();
            $table->integer("status")->default(1);
            $table->integer("views")->default(0);
            $table->text('meta_description')->nullable();
            $table->string('meta_title')->nullable();
            $table->string('tags')->nullable();
            $table->string('lang');
            $table->foreign('lang')->references('code')->on('languages')->onDelete('cascade')->onUpdate('cascade');
            $table->unsignedBigInteger('category_id');
            $table->foreign('category_id')->references('id')->on("blog_categories")->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_posts');
    }
};