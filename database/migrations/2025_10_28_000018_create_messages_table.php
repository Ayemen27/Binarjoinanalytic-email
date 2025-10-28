<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id('id');
            $table->string('message_id', 255);
            $table->longText('from_email')->nullable();
            $table->longText('subject')->nullable();
            $table->longText('from')->nullable();
            $table->longText('to')->nullable();
            $table->string('receivedAt', 255);
            $table->string('source', 255);
            $table->longText('attachments')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('messages');
    }
};
