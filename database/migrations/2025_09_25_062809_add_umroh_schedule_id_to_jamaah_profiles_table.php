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
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            // Add reference to umroh_schedule instead of just rencana_keberangkatan date
            $table->foreignId('umroh_schedule_id')->nullable()->after('paket_id')->constrained()->onDelete('set null');

            // Keep rencana_keberangkatan for backward compatibility, but make it nullable
            $table->date('rencana_keberangkatan')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->dropForeign(['umroh_schedule_id']);
            $table->dropColumn('umroh_schedule_id');

            // Restore rencana_keberangkatan as required
            $table->date('rencana_keberangkatan')->nullable(false)->change();
        });
    }
};