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
            $table->enum('payment_type', ['dp_only', 'full_payment'])->default('dp_only')->after('dp_amount_paid');
            $table->decimal('remaining_amount', 15, 2)->default(0)->after('payment_type');
            $table->boolean('is_full_payment_upfront')->default(false)->after('remaining_amount');
            $table->decimal('pelunasan_amount_paid', 15, 2)->default(0)->after('is_full_payment_upfront');
            $table->string('bukti_pelunasan')->nullable()->after('pelunasan_amount_paid');
            $table->timestamp('pelunasan_paid_at')->nullable()->after('bukti_pelunasan');
            $table->boolean('pelunasan_approved_by_admin')->default(false)->after('pelunasan_paid_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'payment_type',
                'remaining_amount',
                'is_full_payment_upfront',
                'pelunasan_amount_paid',
                'bukti_pelunasan',
                'pelunasan_paid_at',
                'pelunasan_approved_by_admin'
            ]);
        });
    }
};
