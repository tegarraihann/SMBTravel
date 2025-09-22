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
        $adminUser = User::create([
            'name' => 'Administrator',
            'email' => 'admin@travelumroh.com',
            'password' => Hash::make('admin123'),
            'role_id' => $adminRole->id,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $this->command->info('Admin user created successfully:');
        $this->command->info('Email: admin@travelumroh.com');
        $this->command->info('Password: admin123');

        // Create additional admin users if needed
        $superAdminRole = Role::where('name', 'admin')->first();

        if ($superAdminRole) {
            User::create([
                'name' => 'Super Admin',
                'email' => 'superadmin@travelumroh.com',
                'password' => Hash::make('superadmin123'),
                'role_id' => $superAdminRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Super Admin user created successfully:');
            $this->command->info('Email: superadmin@travelumroh.com');
            $this->command->info('Password: superadmin123');
        }

        // Create marketing user for testing
        $marketingRole = Role::where('name', 'marketing')->first();

        if ($marketingRole) {
            User::create([
                'name' => 'Marketing User',
                'email' => 'marketing@travelumroh.com',
                'password' => Hash::make('marketing123'),
                'role_id' => $marketingRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Marketing user created successfully:');
            $this->command->info('Email: marketing@travelumroh.com');
            $this->command->info('Password: marketing123');
        }

        // Create cs user for testing
        $csRole = Role::where('name', 'cs')->first();

        if ($csRole) {
            User::create([
                'name' => 'Customer Service',
                'email' => 'cs@travelumroh.com',
                'password' => Hash::make('cs123'),
                'role_id' => $csRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('CS user created successfully:');
            $this->command->info('Email: cs@travelumroh.com');
            $this->command->info('Password: cs123');
        }

        // Create agent user for testing
        $agentRole = Role::where('name', 'agent')->first();

        if ($agentRole) {
            User::create([
                'name' => 'Travel Agent',
                'email' => 'agent@travelumroh.com',
                'password' => Hash::make('agent123'),
                'role_id' => $agentRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Agent user created successfully:');
            $this->command->info('Email: agent@travelumroh.com');
            $this->command->info('Password: agent123');
        }

        // Create pimpinan user for testing
        $pimpinanRole = Role::where('name', 'pimpinan')->first();

        if ($pimpinanRole) {
            User::create([
                'name' => 'Pimpinan',
                'email' => 'pimpinan@travelumroh.com',
                'password' => Hash::make('pimpinan123'),
                'role_id' => $pimpinanRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Pimpinan user created successfully:');
            $this->command->info('Email: pimpinan@travelumroh.com');
            $this->command->info('Password: pimpinan123');
        }

        // Create sample jamaah user for testing
        $jamaahRole = Role::where('name', 'jamaah')->first();

        if ($jamaahRole) {
            User::create([
                'name' => 'Jamaah Test',
                'email' => 'jamaah@test.com',
                'password' => Hash::make('jamaah123'),
                'role_id' => $jamaahRole->id,
                'is_active' => true,
                'email_verified_at' => now(),
            ]);

            $this->command->info('Test Jamaah user created successfully:');
            $this->command->info('Email: jamaah@test.com');
            $this->command->info('Password: jamaah123');
        }
    }
}
