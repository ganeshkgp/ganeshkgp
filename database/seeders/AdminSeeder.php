<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default super admin
        Admin::create([
            'name' => 'Super Admin',
            'email' => 'admin@admin.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_active' => true,
            'permissions' => [
                'manage_users',
                'manage_blogs',
                'manage_projects',
                'manage_services',
                'manage_contacts',
                'manage_settings',
                'view_analytics',
                'manage_admins',
            ],
            'remember_token' => Str::random(10),
        ]);

        // Create content manager
        Admin::create([
            'name' => 'Content Manager',
            'email' => 'content@spaceportfolio.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_active' => true,
            'permissions' => [
                'manage_blogs',
                'manage_projects',
                'manage_services',
                'view_analytics',
            ],
            'remember_token' => Str::random(10),
        ]);

        // Create support admin
        Admin::create([
            'name' => 'Support Admin',
            'email' => 'support@spaceportfolio.com',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'is_active' => true,
            'permissions' => [
                'manage_contacts',
                'view_analytics',
            ],
            'remember_token' => Str::random(10),
        ]);

        $this->command->info('✅ Admin accounts created successfully!');
        $this->command->info('');
        $this->command->info('🚀 Default Admin Login:');
        $this->command->info('   Email: admin@spaceportfolio.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('📝 Content Manager Login:');
        $this->command->info('   Email: content@spaceportfolio.com');
        $this->command->info('   Password: password');
        $this->command->info('');
        $this->command->info('🎧 Support Admin Login:');
        $this->command->info('   Email: support@spaceportfolio.com');
        $this->command->info('   Password: password');
    }
}
