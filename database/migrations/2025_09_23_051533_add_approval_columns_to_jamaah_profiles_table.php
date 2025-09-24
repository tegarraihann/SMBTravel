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
            $table->string('bukti_transfer')->nullable()->after('dp_paid');
            $table->boolean('data_approved_by_cs')->default(false)->after('bukti_transfer');
            $table->boolean('payment_approved_by_admin')->default(false)->after('data_approved_by_cs');
            $table->timestamp('cs_approval_at')->nullable()->after('payment_approved_by_admin');
            $table->timestamp('admin_approval_at')->nullable()->after('cs_approval_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jamaah_profiles', function (Blueprint $table) {
            $table->dropColumn([
                'bukti_transfer',
                'data_approved_by_cs',
                'payment_approved_by_admin',
                'cs_approval_at',
                'admin_approval_at'
            ]);
        });
    }
};
