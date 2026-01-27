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
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->string('title', 100);
            $table->text('description')->nullable();
            $table->dateTime('remind_date');
            $table->enum('category', ['vaccination', 'grooming', 'feeding', 'medication', 'checkup', 'other'])->default('feeding');
            $table->enum('repeat_type', ['none', 'daily', 'weekly', 'monthly', 'yearly'])->default('none');
            $table->enum('status', ['pending', 'done', 'skipped'])->default('pending');
            $table->timestamp('created_at')->useCurrent();

            $table->index('pet_id');
            $table->index('status');
            $table->index('remind_date');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
