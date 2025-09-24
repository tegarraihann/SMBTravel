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
        Schema::create('installment_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('jamaah_profile_id')->constrained()->onDelete('cascade');
            $table->tinyInteger('installment_number')->comment('1,2,3,4,5');
            $table->date('due_date');
            $table->decimal('amount', 12, 2);
            $table->enum('status', ['pending', 'paid', 'overdue', 'waived'])->default('pending');
            $table->timestamp('paid_at')->nullable();
            $table->string('payment_proof')->nullable()->comment('Path to uploaded payment proof file');
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->text('notes')->nullable()->comment('Admin notes or jamaah payment notes');
            $table->timestamps();

            // Indexes for better performance
            $table->index(['jamaah_profile_id', 'installment_number']);
            $table->index('due_date');
            $table->index('status');

            // Unique constraint to prevent duplicate installment numbers for same jamaah
            $table->unique(['jamaah_profile_id', 'installment_number'], 'unique_installment_per_jamaah');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('installment_payments');
    }
};