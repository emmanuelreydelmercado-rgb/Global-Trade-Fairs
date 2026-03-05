<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('community_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('receiver_id')->constrained('users')->onDelete('cascade');
            // Original message as entered by the sender
            $table->text('message');
            // Translated to English (if sender typed in another language)
            $table->text('message_en')->nullable();
            // Translated to receiver's selected language
            $table->text('message_translated')->nullable();
            // Language code of receiver's preference when message was sent
            $table->string('receiver_lang', 10)->nullable();
            $table->boolean('is_edited')->default(false);
            $table->boolean('is_deleted')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('community_messages');
    }
};
