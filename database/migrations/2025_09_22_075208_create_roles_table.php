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
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->string('display_name');
            $table->text('description')->nullable();
            $table->json('permissions')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            [
                'name' => 'jamaah',
                'display_name' => 'Jamaah',
                'description' => 'Jamaah umroh yang mendaftar',
                'permissions' => json_encode([
                    'view_own_profile',
                    'update_own_profile',
                    'view_own_bookings',
                    'upload_documents',
                    'make_payments'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'marketing',
                'display_name' => 'Marketing',
                'description' => 'Tim marketing untuk promosi dan penjualan',
                'permissions' => json_encode([
                    'view_jamaah_list',
                    'view_bookings',
                    'create_promotions',
                    'manage_leads',
                    'view_reports'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'admin',
                'display_name' => 'Admin',
                'description' => 'Administrator sistem dengan akses penuh',
                'permissions' => json_encode([
                    'manage_users',
                    'manage_jamaah',
                    'manage_bookings',
                    'manage_payments',
                    'manage_packages',
                    'view_all_reports',
                    'system_settings'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'pimpinan',
                'display_name' => 'Pimpinan',
                'description' => 'Pimpinan perusahaan dengan akses dashboard eksekutif',
                'permissions' => json_encode([
                    'view_executive_dashboard',
                    'view_financial_reports',
                    'view_performance_reports',
                    'approve_major_decisions',
                    'view_all_data'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'cs',
                'display_name' => 'Customer Service',
                'description' => 'Customer service untuk melayani jamaah',
                'permissions' => json_encode([
                    'view_jamaah_list',
                    'update_jamaah_status',
                    'respond_to_inquiries',
                    'manage_complaints',
                    'view_booking_details'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'agent',
                'display_name' => 'Agent',
                'description' => 'Agen travel untuk membantu jamaah',
                'permissions' => json_encode([
                    'register_jamaah',
                    'view_assigned_jamaah',
                    'update_jamaah_info',
                    'process_bookings',
                    'earn_commission'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('roles');
    }
};
