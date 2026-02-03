<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Get existing foreign keys
        $foreignKeys = DB::select("
            SELECT CONSTRAINT_NAME
            FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE()
            AND TABLE_NAME = 'reminders'
            AND COLUMN_NAME = 'pet_id'
            AND REFERENCED_TABLE_NAME IS NOT NULL
        ");

        Schema::table('reminders', function (Blueprint $table) use ($foreignKeys) {
            // Drop foreign key if exists
            foreach ($foreignKeys as $fk) {
                $table->dropForeign($fk->CONSTRAINT_NAME);
            }

            // Drop pet_id column if exists
            if (Schema::hasColumn('reminders', 'pet_id')) {
                $table->dropColumn('pet_id');
            }
        });

        // Add user_id in separate statement
        Schema::table('reminders', function (Blueprint $table) {
            if (!Schema::hasColumn('reminders', 'user_id')) {
                $table->foreignId('user_id')->after('id')->constrained()->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('reminders', function (Blueprint $table) {
            // Drop user_id foreign key and column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Restore pet_id column with foreign key
            $table->foreignId('pet_id')->after('id')->constrained()->onDelete('cascade');
            $table->index('pet_id');
        });
    }
};
