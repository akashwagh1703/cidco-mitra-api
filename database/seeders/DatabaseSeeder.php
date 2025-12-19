<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Create permissions
        $permissions = [
            'view_dashboard',
            'manage_leads',
            'update_lead_status',
            'view_notifications',
            'manage_website_settings',
            'manage_email_settings',
            'manage_users',
            'manage_roles',
            'access_reports',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Create roles
        $superAdmin = Role::create(['name' => 'Super Admin']);
        $superAdmin->givePermissionTo(Permission::all());

        $admin = Role::create(['name' => 'Admin']);
        $admin->givePermissionTo([
            'view_dashboard',
            'manage_leads',
            'update_lead_status',
            'view_notifications',
            'manage_website_settings',
            'manage_email_settings',
            'manage_users',
        ]);

        $manager = Role::create(['name' => 'Manager']);
        $manager->givePermissionTo([
            'view_dashboard',
            'manage_leads',
            'update_lead_status',
            'view_notifications',
            'access_reports',
        ]);

        $agent = Role::create(['name' => 'Agent']);
        $agent->givePermissionTo([
            'view_dashboard',
            'update_lead_status',
            'view_notifications',
        ]);

        // Create default admin user
        $user = User::create([
            'name' => 'Admin User',
            'email' => 'admin@cidcomitra.gov.in',
            'password' => bcrypt('admin123'),
            'status' => true,
        ]);

        $user->assignRole('Super Admin');
    }
}
