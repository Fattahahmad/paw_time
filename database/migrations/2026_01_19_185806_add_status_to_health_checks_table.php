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
        Schema::table('health_checks', function (Blueprint $table) {
            $table->enum('status', ['pending', 'on_progress', 'done'])->default('pending')->after('complaint');
            $table->string('doctor_name')->nullable()->after('status');
            $table->string('doctor_phone')->nullable()->after('doctor_name');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('health_checks', function (Blueprint $table) {
            $table->dropColumn(['status', 'doctor_name', 'doctor_phone']);
        });
    }
};
