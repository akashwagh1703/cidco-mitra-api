<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Lead;
use App\Models\Service;
use App\Models\Appointment;
use App\Models\Setting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create test services
        $services = [
            [
                'title' => 'Property Registration',
                'description' => 'Complete property registration services',
                'status' => true,
                'order' => 1,
            ],
            [
                'title' => 'Building Permission',
                'description' => 'Building construction permission services',
                'status' => true,
                'order' => 2,
            ],
        ];

        foreach ($services as $service) {
            Service::create($service);
        }

        // Create service schedules
        $schedules = [
            // Property Registration - Monday to Friday
            ['service_id' => 1, 'day_of_week' => 1, 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'max_appointments' => 10],
            ['service_id' => 1, 'day_of_week' => 2, 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'max_appointments' => 10],
            ['service_id' => 1, 'day_of_week' => 3, 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'max_appointments' => 10],
            ['service_id' => 1, 'day_of_week' => 4, 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'max_appointments' => 10],
            ['service_id' => 1, 'day_of_week' => 5, 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'max_appointments' => 10],
            
            // Building Permission - Monday, Wednesday, Friday
            ['service_id' => 2, 'day_of_week' => 1, 'start_time' => '10:00:00', 'end_time' => '16:00:00', 'max_appointments' => 8],
            ['service_id' => 2, 'day_of_week' => 3, 'start_time' => '10:00:00', 'end_time' => '16:00:00', 'max_appointments' => 8],
            ['service_id' => 2, 'day_of_week' => 5, 'start_time' => '10:00:00', 'end_time' => '16:00:00', 'max_appointments' => 8],
        ];

        foreach ($schedules as $schedule) {
            DB::table('service_schedules')->insert(array_merge($schedule, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Create test leads
        $leads = [
            [
                'name' => 'Rajesh Sharma',
                'email' => 'rajesh@example.com',
                'phone' => '9876543210',
                'message' => 'Need help with property registration',
                'status' => 'new',
                'source' => 'website',
            ],
            [
                'name' => 'Priya Patel',
                'email' => 'priya@example.com',
                'phone' => '9876543211',
                'message' => 'Inquiry about building permission',
                'status' => 'contacted',
                'source' => 'phone',
            ],
            [
                'name' => 'Amit Kumar',
                'email' => 'amit@example.com',
                'phone' => '9876543212',
                'message' => 'General inquiry about services',
                'status' => 'follow_up',
                'source' => 'website',
            ],
        ];

        foreach ($leads as $lead) {
            Lead::create($lead);
        }

        // Create test appointments
        $appointments = [
            [
                'service_id' => 1,
                'name' => 'Suresh Patil',
                'email' => 'suresh@example.com',
                'phone' => '9876543213',
                'appointment_date' => Carbon::tomorrow(),
                'appointment_time' => '10:00:00',
                'message' => 'Property registration appointment',
                'status' => 'confirmed',
            ],
            [
                'service_id' => 2,
                'name' => 'Meera Shah',
                'email' => 'meera@example.com',
                'phone' => '9876543214',
                'appointment_date' => Carbon::tomorrow()->addDay(),
                'appointment_time' => '14:00:00',
                'message' => 'Building permission consultation',
                'status' => 'pending',
            ],
        ];

        foreach ($appointments as $appointment) {
            Appointment::create($appointment);
        }

        // Create basic settings
        $settings = [
            ['key' => 'site_name', 'value' => 'CIDCO Mitra'],
            ['key' => 'site_description', 'value' => 'CIDCO Citizen Services Portal'],
            ['key' => 'contact_email', 'value' => 'info@cidcomitra.gov.in'],
            ['key' => 'contact_phone', 'value' => '+91-22-1234-5678'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        echo "✅ Test data created successfully!\n";
        echo "- Services: " . Service::count() . "\n";
        echo "- Service Schedules: " . DB::table('service_schedules')->count() . "\n";
        echo "- Leads: " . Lead::count() . "\n";
        echo "- Appointments: " . Appointment::count() . "\n";
        echo "- Settings: " . Setting::count() . "\n";
    }
}