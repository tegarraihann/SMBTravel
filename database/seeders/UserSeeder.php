<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get admin role
        $adminRole = Role::where('name', 'admin')->first();

        if (!$adminRole) {
            $this->command->error('Admin role not found. Please run RoleSeeder first.');
            return;
        }

        // Create admin user
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@travelumroh.com'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin123'),
                'role_id' => $adminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('Admin user created successfully:');
        $this->command->info('Email: admin@travelumroh.com');
        $this->command->info('Password: admin123');

        // Create additional admin users if needed
        $superAdminRole = Role::where('name', 'admin')->first();

        if ($superAdminRole) {
            User::updateOrCreate(
                ['email' => 'superadmin@travelumroh.com'],
                [
                    'name' => 'Super Admin',
                    'password' => Hash::make('superadmin123'),
                    'role_id' => $superAdminRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info('Super Admin user created successfully:');
            $this->command->info('Email: superadmin@travelumroh.com');
            $this->command->info('Password: superadmin123');
        }

        // Create marketing user for testing
        $marketingRole = Role::where('name', 'marketing')->first();

        if ($marketingRole) {
            User::updateOrCreate(
                ['email' => 'marketing@travelumroh.com'],
                [
                    'name' => 'Marketing User',
                    'password' => Hash::make('marketing123'),
                    'role_id' => $marketingRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info('Marketing user created successfully:');
            $this->command->info('Email: marketing@travelumroh.com');
            $this->command->info('Password: marketing123');
        }

        // Create cs user for testing
        $csRole = Role::where('name', 'cs')->first();

        if ($csRole) {
            User::updateOrCreate(
                ['email' => 'cs@travelumroh.com'],
                [
                    'name' => 'Customer Service',
                    'password' => Hash::make('cs123'),
                    'role_id' => $csRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info('CS user created successfully:');
            $this->command->info('Email: cs@travelumroh.com');
            $this->command->info('Password: cs123');
        }

        // Create agent user for testing
        $agentRole = Role::where('name', 'agent')->first();

        if ($agentRole) {
            User::updateOrCreate(
                ['email' => 'agent@travelumroh.com'],
                [
                    'name' => 'Travel Agent',
                    'password' => Hash::make('agent123'),
                    'role_id' => $agentRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info('Agent user created successfully:');
            $this->command->info('Email: agent@travelumroh.com');
            $this->command->info('Password: agent123');
        }

        // Create pimpinan user for testing
        $pimpinanRole = Role::where('name', 'pimpinan')->first();

        if ($pimpinanRole) {
            User::updateOrCreate(
                ['email' => 'pimpinan@travelumroh.com'],
                [
                    'name' => 'Pimpinan',
                    'password' => Hash::make('pimpinan123'),
                    'role_id' => $pimpinanRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info('Pimpinan user created successfully:');
            $this->command->info('Email: pimpinan@travelumroh.com');
            $this->command->info('Password: pimpinan123');
        }

        // Create sample jamaah user for testing
        $jamaahRole = Role::where('name', 'jamaah')->first();

        if ($jamaahRole) {
            User::updateOrCreate(
                ['email' => 'jamaah@test.com'],
                [
                    'name' => 'Jamaah Test',
                    'password' => Hash::make('jamaah123'),
                    'role_id' => $jamaahRole->id,
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );

            $this->command->info('Test Jamaah user created successfully:');
            $this->command->info('Email: jamaah@test.com');
            $this->command->info('Password: jamaah123');
        }

        // Create operasional users
        $operasionalRole = Role::where('name', 'operasional')->first();

        if ($operasionalRole) {
            $operasionalUsers = [
                [
                    'name' => 'Ahmad Operasional',
                    'email' => 'operasional1@travelumroh.com',
                    'password' => Hash::make('operasional123'),
                    'phone' => '6281234567890',
                ]
            ];

            foreach ($operasionalUsers as $userData) {
                User::updateOrCreate(
                    ['email' => $userData['email']],
                    array_merge($userData, [
                        'role_id' => $operasionalRole->id,
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ])
                );
            }

            $this->command->info('Operasional users created successfully:');
            $this->command->info('Email: operasional1@travelumroh.com | Password: operasional123');
            $this->command->info('Email: operasional2@travelumroh.com | Password: operasional123');
            $this->command->info('Email: operasional3@travelumroh.com | Password: operasional123');
            $this->command->line('⚠️  PENTING: Ganti password default setelah login pertama!');
            $this->command->line('📱 WhatsApp: Pastikan nomor phone di database sudah benar untuk notifikasi');
        }
    }
}
