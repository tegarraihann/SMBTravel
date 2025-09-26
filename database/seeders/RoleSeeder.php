<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'admin',
                'display_name' => 'Administrator',
                'description' => 'Full system administrator with all permissions',
                'permissions' => [
                    'manage_users',
                    'manage_jamaah',
                    'manage_packages',
                    'view_all_reports',
                    'manage_roles',
                    'system_settings',
                    'approve_payments',
                    'manage_agents',
                    'manage_marketing',
                ],
                'is_active' => true
            ],
            [
                'name' => 'pimpinan',
                'display_name' => 'Pimpinan',
                'description' => 'Company leadership with executive access',
                'permissions' => [
                    'view_executive_dashboard',
                    'view_financial_reports',
                    'view_performance_reports',
                    'view_all_reports',
                    'approve_high_value_transactions'
                ],
                'is_active' => true
            ],
            [
                'name' => 'marketing',
                'display_name' => 'Marketing',
                'description' => 'Marketing team with promotional and lead management access',
                'permissions' => [
                    'manage_leads',
                    'create_promotions',
                    'view_reports',
                    'manage_content',
                    'view_jamaah_list'
                ],
                'is_active' => true
            ],
            [
                'name' => 'cs',
                'display_name' => 'Customer Service',
                'description' => 'Customer service with jamaah support access',
                'permissions' => [
                    'view_jamaah_list',
                    'respond_to_inquiries',
                    'manage_complaints',
                    'update_jamaah_status',
                    'view_payments'
                ],
                'is_active' => true
            ],
            [
                'name' => 'agent',
                'display_name' => 'Travel Agent',
                'description' => 'Travel agents with jamaah registration and commission access',
                'permissions' => [
                    'register_jamaah',
                    'view_assigned_jamaah',
                    'earn_commission',
                    'update_jamaah_info'
                ],
                'is_active' => true
            ],
            [
                'name' => 'operasional',
                'display_name' => 'Operasional',
                'description' => 'Operations team for ticket and visa processing',
                'permissions' => [
                    'view_approved_jamaah',
                    'process_tickets',
                    'process_visas',
                    'upload_ticket_documents',
                    'upload_visa_documents',
                    'update_ticket_status',
                    'update_visa_status',
                    'receive_jamaah_notifications',
                    'send_process_notifications'
                ],
                'is_active' => true
            ],
            [
                'name' => 'jamaah',
                'display_name' => 'Jamaah',
                'description' => 'End users who book umroh packages',
                'permissions' => [
                    'view_own_profile',
                    'update_own_profile',
                    'make_payments',
                    'upload_documents',
                    'view_own_bookings',
                    'contact_support'
                ],
                'is_active' => true
            ]
        ];

        foreach ($roles as $roleData) {
            Role::updateOrCreate(
                ['name' => $roleData['name']],
                $roleData
            );
        }

        $this->command->info('Roles seeded successfully.');
    }
}
