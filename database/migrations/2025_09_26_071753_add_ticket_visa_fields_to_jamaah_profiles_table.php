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
            // Ticket fields
            $table->enum('ticket_status', ['pending', 'processing', 'completed'])->default('pending')->after('pelunasan_approved_by_admin');
            $table->unsignedBigInteger('ticket_processed_by')->nullable()->after('ticket_status');
            $table->timestamp('ticket_processed_at')->nullable()->after('ticket_processed_by');
            $table->text('ticket_notes')->nullable()->after('ticket_processed_at');
            $table->string('ticket_file')->nullable()->after('ticket_notes');

            // Visa fields
            $table->enum('visa_status', ['pending', 'processing', 'completed'])->default('pending')->after('ticket_file');
            $table->unsignedBigInteger('visa_processed_by')->nullable()->after('visa_status');
            $table->timestamp('visa_processed_at')->nullable()->after('visa_processed_by');
            $table->text('visa_notes')->nullable()->after('visa_processed_at');
            $table->string('visa_file')->nullable()->after('visa_notes');

            // Foreign keys
            $table->foreign('ticket_processed_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('visa_processed_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->dropForeign(['ticket_processed_by']);
            $table->dropForeign(['visa_processed_by']);
            $table->dropColumn([
                'ticket_status',
                'ticket_processed_by',
                'ticket_processed_at',
                'ticket_notes',
                'ticket_file',
                'visa_status',
                'visa_processed_by',
                'visa_processed_at',
                'visa_notes',
                'visa_file'
            ]);
        });
    }
};
