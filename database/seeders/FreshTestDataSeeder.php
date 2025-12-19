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

class FreshTestDataSeeder extends Seeder
{
    public function run(): void
    {
        echo "Creating fresh test data...\n";
        
        // Create test services
        $service1 = Service::create([
            'title' => 'Property Registration',
            'description' => 'Complete property registration and documentation services for citizens',
            'status' => true,
            'order' => 1,
        ]);

        $service2 = Service::create([
            'title' => 'Building Permission',
            'description' => 'Building construction permission and approval services',
            'status' => true,
            'order' => 2,
        ]);

        $service3 = Service::create([
            'title' => 'Trade License',
            'description' => 'Business and trade license registration services',
            'status' => true,
            'order' => 3,
        ]);

        // Create service schedules
        $schedules = [
            // Property Registration - Monday to Friday
            ['service_id' => $service1->id, 'day_of_week' => 'monday', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'slot_duration' => 60, 'max_appointments_per_slot' => 2],
            ['service_id' => $service1->id, 'day_of_week' => 'tuesday', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'slot_duration' => 60, 'max_appointments_per_slot' => 2],
            ['service_id' => $service1->id, 'day_of_week' => 'wednesday', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'slot_duration' => 60, 'max_appointments_per_slot' => 2],
            ['service_id' => $service1->id, 'day_of_week' => 'thursday', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'slot_duration' => 60, 'max_appointments_per_slot' => 2],
            ['service_id' => $service1->id, 'day_of_week' => 'friday', 'start_time' => '09:00:00', 'end_time' => '17:00:00', 'slot_duration' => 60, 'max_appointments_per_slot' => 2],
            
            // Building Permission - Monday, Wednesday, Friday
            ['service_id' => $service2->id, 'day_of_week' => 'monday', 'start_time' => '10:00:00', 'end_time' => '16:00:00', 'slot_duration' => 45, 'max_appointments_per_slot' => 1],
            ['service_id' => $service2->id, 'day_of_week' => 'wednesday', 'start_time' => '10:00:00', 'end_time' => '16:00:00', 'slot_duration' => 45, 'max_appointments_per_slot' => 1],
            ['service_id' => $service2->id, 'day_of_week' => 'friday', 'start_time' => '10:00:00', 'end_time' => '16:00:00', 'slot_duration' => 45, 'max_appointments_per_slot' => 1],
            
            // Trade License - Tuesday, Thursday
            ['service_id' => $service3->id, 'day_of_week' => 'tuesday', 'start_time' => '11:00:00', 'end_time' => '15:00:00', 'slot_duration' => 30, 'max_appointments_per_slot' => 1],
            ['service_id' => $service3->id, 'day_of_week' => 'thursday', 'start_time' => '11:00:00', 'end_time' => '15:00:00', 'slot_duration' => 30, 'max_appointments_per_slot' => 1],
        ];

        foreach ($schedules as $schedule) {
            DB::table('service_schedules')->insert(array_merge($schedule, [
                'created_at' => now(),
                'updated_at' => now(),
            ]));
        }

        // Create test leads
        $leads = [
            Lead::create([
                'name' => 'Rajesh Kumar Sharma',
                'email' => 'rajesh.sharma@example.com',
                'phone' => '9876543210',
                'message' => 'I need help with property registration process. Please guide me through the required documents.',
                'status' => 'new',
                'source' => 'website',
            ]),
            Lead::create([
                'name' => 'Priya Patel',
                'email' => 'priya.patel@example.com',
                'phone' => '9876543211',
                'message' => 'Inquiry about building permission for residential construction',
                'status' => 'contacted',
                'source' => 'phone',
            ]),
            Lead::create([
                'name' => 'Amit Kumar Singh',
                'email' => 'amit.singh@example.com',
                'phone' => '9876543212',
                'message' => 'Need information about trade license for my new business',
                'status' => 'follow_up',
                'source' => 'website',
            ]),
            Lead::create([
                'name' => 'Sunita Devi',
                'email' => 'sunita.devi@example.com',
                'phone' => '9876543213',
                'message' => 'Property registration completed successfully. Thank you for the service.',
                'status' => 'converted',
                'source' => 'referral',
            ]),
        ];

        // Get admin user for appointments
        $adminUser = User::where('email', 'admin@cidcomitra.gov.in')->first();

        // Create test appointments
        $appointments = [
            Appointment::create([
                'service_id' => $service1->id,
                'name' => 'Suresh Patil',
                'email' => 'suresh.patil@example.com',
                'phone' => '9876543214',
                'appointment_date' => Carbon::tomorrow(),
                'appointment_time' => '10:00:00',
                'message' => 'Property registration appointment for flat purchase',
                'status' => 'confirmed',
                'assigned_to' => $adminUser?->id,
                'confirmed_at' => now(),
            ]),
            Appointment::create([
                'service_id' => $service2->id,
                'name' => 'Meera Shah',
                'email' => 'meera.shah@example.com',
                'phone' => '9876543215',
                'appointment_date' => Carbon::tomorrow()->addDay(),
                'appointment_time' => '14:00:00',
                'message' => 'Building permission consultation for house construction',
                'status' => 'pending',
            ]),
            Appointment::create([
                'service_id' => $service3->id,
                'name' => 'Ravi Gupta',
                'email' => 'ravi.gupta@example.com',
                'phone' => '9876543216',
                'appointment_date' => Carbon::today()->addDays(3),
                'appointment_time' => '12:00:00',
                'message' => 'Trade license application for restaurant business',
                'status' => 'confirmed',
                'assigned_to' => $adminUser?->id,
            ]),
        ];

        // Create comprehensive settings
        $settings = [
            ['key' => 'site_name', 'value' => 'CIDCO Mitra'],
            ['key' => 'site_tagline', 'value' => 'Your Digital Gateway to CIDCO Services'],
            ['key' => 'site_description', 'value' => 'CIDCO Citizen Services Portal - Simplifying government services for citizens'],
            ['key' => 'contact_email', 'value' => 'info@cidcomitra.gov.in'],
            ['key' => 'contact_phone', 'value' => '+91-22-1234-5678'],
            ['key' => 'office_address', 'value' => 'CIDCO Bhavan, CBD Belapur, Navi Mumbai - 400614'],
            ['key' => 'office_hours', 'value' => 'Monday to Friday: 9:00 AM - 5:00 PM'],
            ['key' => 'emergency_contact', 'value' => '+91-22-9876-5432'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }

        echo "✅ Fresh test data created successfully!\n";
        echo "📊 Summary:\n";
        echo "- Services: " . Service::count() . "\n";
        echo "- Service Schedules: " . DB::table('service_schedules')->count() . "\n";
        echo "- Leads: " . Lead::count() . "\n";
        echo "- Appointments: " . Appointment::count() . "\n";
        echo "- Settings: " . Setting::count() . "\n";
        echo "- Users: " . User::count() . "\n";
    }
}