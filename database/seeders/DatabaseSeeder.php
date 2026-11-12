<?php

namespace Database\Seeders;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
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

        $superAdmin = Role::firstOrCreate(['name' => 'Super Admin']);
        $superAdmin->syncPermissions(Permission::all());

        $admin = Role::firstOrCreate(['name' => 'Admin']);
        $admin->syncPermissions([
            'view_dashboard',
            'manage_leads',
            'update_lead_status',
            'view_notifications',
            'manage_website_settings',
            'manage_email_settings',
            'manage_users',
        ]);

        $manager = Role::firstOrCreate(['name' => 'Manager']);
        $manager->syncPermissions([
            'view_dashboard',
            'manage_leads',
            'update_lead_status',
            'view_notifications',
            'access_reports',
        ]);

        $agent = Role::firstOrCreate(['name' => 'Agent']);
        $agent->syncPermissions([
            'view_dashboard',
            'update_lead_status',
            'view_notifications',
        ]);

        $user = User::firstOrCreate(
            ['email' => 'admin@cidcomitra.gov.in'],
            [
                'name' => 'Admin User',
                'password' => bcrypt('admin123'),
                'status' => true,
            ]
        );

        if (!$user->hasRole('Super Admin')) {
            $user->assignRole('Super Admin');
        }

        Setting::set('general', [
            'site_name' => 'CIDCO Mitra',
            'contact_email' => 'info@cidcomitra.gov.in',
            'contact_phone' => '+91 22 2757 2000',
            'address' => 'CIDCO Bhavan, CBD Belapur, Navi Mumbai - 400614',
        ]);

        Setting::set('branding', [
            'primary_color' => '#0066CC',
            'secondary_color' => '#004C99',
            'font_family' => 'Inter',
        ]);

        Setting::set('homepage', [
            'hero_title' => 'Your Trusted Partner for CIDCO Services',
            'hero_subtitle' => 'Fast and easy access to government services',
        ]);

        Setting::set('seo', [
            'meta_title' => 'CIDCO Mitra - Government Services',
            'meta_description' => 'Fast and easy access to CIDCO government services',
        ]);

        $this->call(ServiceSeeder::class);
    }
}
