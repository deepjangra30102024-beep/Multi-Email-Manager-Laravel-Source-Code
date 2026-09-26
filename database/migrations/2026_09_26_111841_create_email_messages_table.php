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
        Schema::create('email_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('email_account_id')->constrained()->cascadeOnDelete();
            $table->string('message_id')->unique();
            $table->string('thread_id')->nullable();
            $table->string('subject')->nullable();
            $table->string('sender')->nullable();
            $table->string('recipient')->nullable();
            $table->text('snippet')->nullable();
            $table->longText('body')->nullable();
            $table->boolean('is_read')->default(false);
            $table->string('labels')->nullable(); // Can store comma separated labels or JSON
            $table->timestamp('received_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('email_messages');
    }
};
