<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadTimeline;
use App\Models\Service;
use App\Models\ServiceSchedule;
use App\Models\Appointment;
use App\Models\Notification;
use App\Models\Setting;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class CompleteDataSeeder extends Seeder
{
    public function run(): void
    {
        // Create Permissions
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
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        // Create Roles
        $superAdmin = Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web']);
        $superAdmin->syncPermissions($permissions);

        $admin = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $admin->syncPermissions(['view_dashboard', 'manage_leads', 'update_lead_status', 'view_notifications', 'manage_website_settings', 'manage_email_settings', 'manage_users']);

        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions(['view_dashboard', 'manage_leads', 'update_lead_status', 'view_notifications']);

        $agent = Role::firstOrCreate(['name' => 'agent', 'guard_name' => 'web']);
        $agent->syncPermissions(['view_dashboard', 'update_lead_status', 'view_notifications']);

        // Create Users
        $users = [
            [
                'name' => 'Rajesh Kumar',
                'email' => 'admin@cidcomitra.gov.in',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'status' => true,
            ],
            [
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@cidcomitra.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'status' => true,
            ],
            [
                'name' => 'Amit Patel',
                'email' => 'amit.patel@cidcomitra.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'manager',
                'status' => true,
            ],
            [
                'name' => 'Sneha Desai',
                'email' => 'sneha.desai@cidcomitra.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'agent',
                'status' => true,
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh@cidcomitra.gov.in',
                'password' => Hash::make('password123'),
                'role' => 'agent',
                'status' => true,
            ],
        ];

        foreach ($users as $userData) {
            $user = User::firstOrCreate(
                ['email' => $userData['email']],
                [
                    'name' => $userData['name'],
                    'password' => $userData['password'],
                    'status' => $userData['status'],
                ]
            );
            $user->assignRole($userData['role']);
        }

        // Create Services
        $services = [
            [
                'title' => json_encode([
                    'en' => 'Building Permit Application',
                    'mr' => 'इमारत परवाना अर्ज',
                    'hi' => 'भवन अनुमति आवेदन'
                ]),
                'description' => json_encode([
                    'en' => 'Apply for building construction permits and approvals for residential and commercial projects',
                    'mr' => 'निवासी आणि व्यावसायिक प्रकल्पांसाठी इमारत बांधकाम परवाने आणि मंजुरीसाठी अर्ज करा',
                    'hi' => 'आवासीय और वाणिज्यिक परियोजनाओं के लिए भवन निर्माण परमिट और अनुमोदन के लिए आवेदन करें'
                ]),
                'overview' => json_encode([
                    'en' => 'Complete building permit processing service with expert guidance',
                    'mr' => 'तज्ञ मार्गदर्शनासह संपूर्ण इमारत परवाना प्रक्रिया सेवा',
                    'hi' => 'विशेषज्ञ मार्गदर्शन के साथ पूर्ण भवन परमिट प्रसंस्करण सेवा'
                ]),
                'pricing' => json_encode([
                    'en' => 'Starting from ₹5,000 (varies by project size)',
                    'mr' => '₹5,000 पासून सुरू (प्रकल्प आकारानुसार बदलते)',
                    'hi' => '₹5,000 से शुरू (परियोजना के आकार के अनुसार भिन्न होता है)'
                ]),
                'documents' => json_encode([
                    'en' => 'Property documents, ID proof, Address proof, Site plan, Architect drawings',
                    'mr' => 'मालमत्ता कागदपत्रे, ओळखपत्र, पत्ता पुरावा, साइट योजना, वास्तुविशारद रेखाचित्रे',
                    'hi' => 'संपत्ति दस्तावेज, पहचान प्रमाण, पता प्रमाण, साइट योजना, वास्तुकार चित्र'
                ]),
                'timeline' => json_encode([
                    'en' => '30-45 working days',
                    'mr' => '30-45 कामकाजाचे दिवस',
                    'hi' => '30-45 कार्य दिवस'
                ]),
                'icon' => 'Building2',
                'phone' => '+91 22 2757 2000',
                'whatsapp' => '+91 98765 43210',
                'status' => true,
                'order' => 1,
            ],
            [
                'title' => json_encode([
                    'en' => 'Property Tax Assessment',
                    'mr' => 'मालमत्ता कर मूल्यांकन',
                    'hi' => 'संपत्ति कर मूल्यांकन'
                ]),
                'description' => json_encode([
                    'en' => 'Get your property assessed for tax purposes and receive official valuation certificates',
                    'mr' => 'कर उद्देशांसाठी तुमच्या मालमत्तेचे मूल्यांकन करा आणि अधिकृत मूल्यांकन प्रमाणपत्रे मिळवा',
                    'hi' => 'कर उद्देश्यों के लिए अपनी संपत्ति का मूल्यांकन करवाएं और आधिकारिक मूल्यांकन प्रमाणपत्र प्राप्त करें'
                ]),
                'pricing' => json_encode([
                    'en' => '₹2,000 - ₹10,000',
                    'mr' => '₹2,000 - ₹10,000',
                    'hi' => '₹2,000 - ₹10,000'
                ]),
                'timeline' => json_encode([
                    'en' => '15-20 working days',
                    'mr' => '15-20 कामकाजाचे दिवस',
                    'hi' => '15-20 कार्य दिवस'
                ]),
                'icon' => 'FileText',
                'phone' => '+91 22 2757 2001',
                'status' => true,
                'order' => 2,
            ],
            [
                'title' => json_encode([
                    'en' => 'Land Acquisition Services',
                    'mr' => 'जमीन संपादन सेवा',
                    'hi' => 'भूमि अधिग्रहण सेवाएं'
                ]),
                'description' => json_encode([
                    'en' => 'Assistance with land acquisition processes, documentation, and legal compliance',
                    'mr' => 'जमीन संपादन प्रक्रिया, दस्तऐवजीकरण आणि कायदेशीर अनुपालनासाठी सहाय्य',
                    'hi' => 'भूमि अधिग्रहण प्रक्रियाओं, दस्तावेज़ीकरण और कानूनी अनुपालन में सहायता'
                ]),
                'pricing' => json_encode([
                    'en' => 'As per government rates',
                    'mr' => 'सरकारी दरानुसार',
                    'hi' => 'सरकारी दरों के अनुसार'
                ]),
                'timeline' => json_encode([
                    'en' => '60-90 working days',
                    'mr' => '60-90 कामकाजाचे दिवस',
                    'hi' => '60-90 कार्य दिवस'
                ]),
                'icon' => 'MapPin',
                'phone' => '+91 22 2757 2002',
                'status' => true,
                'order' => 3,
            ],
        ];

        $createdServices = [];
        foreach ($services as $serviceData) {
            $service = Service::create($serviceData);
            $createdServices[] = $service;
        }

        // Create Service Schedules
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday'];
        foreach ($createdServices as $service) {
            foreach ($days as $day) {
                ServiceSchedule::create([
                    'service_id' => $service->id,
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'slot_duration' => 30,
                    'max_appointments_per_slot' => 1,
                    'is_active' => true,
                ]);
            }
        }

        // Create Leads
        $leads = [
            [
                'name' => 'Rahul Mehta',
                'email' => 'rahul.mehta@example.com',
                'phone' => '+91 98765 11111',
                'message' => 'I need information about building permit for my residential project in Navi Mumbai.',
                'source' => 'Website',
                'status' => 'new',
            ],
            [
                'name' => 'Anjali Verma',
                'email' => 'anjali.verma@example.com',
                'phone' => '+91 98765 22222',
                'message' => 'Looking for property tax assessment services for my commercial property.',
                'source' => 'Website',
                'status' => 'contacted',
            ],
            [
                'name' => 'Suresh Patil',
                'email' => 'suresh.patil@example.com',
                'phone' => '+91 98765 33333',
                'message' => 'Need assistance with land acquisition process. Please contact me.',
                'source' => 'Phone',
                'status' => 'follow_up',
            ],
            [
                'name' => 'Meera Joshi',
                'email' => 'meera.joshi@example.com',
                'phone' => '+91 98765 44444',
                'message' => 'Interested in building permit application. What documents are required?',
                'source' => 'Website',
                'status' => 'converted',
            ],
            [
                'name' => 'Kiran Naik',
                'email' => 'kiran.naik@example.com',
                'phone' => '+91 98765 55555',
                'message' => 'I want to know about the timeline for property tax assessment.',
                'source' => 'Email',
                'status' => 'new',
            ],
        ];

        foreach ($leads as $leadData) {
            $lead = Lead::create($leadData);
            
            // Add timeline entry
            LeadTimeline::create([
                'lead_id' => $lead->id,
                'event_type' => 'created',
                'event_data' => json_encode(['message' => 'Lead created from ' . $lead->source]),
                'created_by' => 1,
            ]);

            if ($lead->status !== 'new') {
                LeadTimeline::create([
                    'lead_id' => $lead->id,
                    'event_type' => 'status_changed',
                    'event_data' => json_encode(['message' => 'Status changed to ' . $lead->status]),
                    'created_by' => 1,
                ]);
            }
        }

        // Create Appointments
        $appointments = [
            [
                'service_id' => 1,
                'name' => 'Deepak Sharma',
                'email' => 'deepak.sharma@example.com',
                'phone' => '+91 98765 66666',
                'appointment_date' => now()->addDays(2)->format('Y-m-d'),
                'appointment_time' => '10:00:00',
                'message' => 'Need consultation for residential building permit',
                'status' => 'confirmed',
                'assigned_to' => 3,
                'confirmed_at' => now(),
            ],
            [
                'service_id' => 2,
                'name' => 'Pooja Reddy',
                'email' => 'pooja.reddy@example.com',
                'phone' => '+91 98765 77777',
                'appointment_date' => now()->addDays(3)->format('Y-m-d'),
                'appointment_time' => '11:00:00',
                'message' => 'Property tax assessment required',
                'status' => 'pending',
            ],
            [
                'service_id' => 1,
                'name' => 'Anil Kumar',
                'email' => 'anil.kumar@example.com',
                'phone' => '+91 98765 88888',
                'appointment_date' => now()->addDays(5)->format('Y-m-d'),
                'appointment_time' => '14:00:00',
                'message' => 'Commercial building permit consultation',
                'status' => 'confirmed',
                'assigned_to' => 4,
                'confirmed_at' => now(),
            ],
        ];

        foreach ($appointments as $appointmentData) {
            Appointment::create($appointmentData);
        }

        // Create Notifications
        $notifications = [
            [
                'user_id' => 1,
                'type' => 'new_lead',
                'title' => 'New Lead Received',
                'message' => 'New lead from Rahul Mehta for building permit',
                'data' => ['lead_id' => 1],
                'read_at' => null,
            ],
            [
                'user_id' => 1,
                'type' => 'new_appointment',
                'title' => 'New Appointment Booked',
                'message' => 'Deepak Sharma booked appointment for ' . now()->addDays(2)->format('d M Y'),
                'data' => ['appointment_id' => 1],
                'read_at' => null,
            ],
            [
                'user_id' => 1,
                'type' => 'lead_status_updated',
                'title' => 'Lead Status Updated',
                'message' => 'Meera Joshi lead marked as converted',
                'data' => ['lead_id' => 4],
                'read_at' => now(),
            ],
        ];

        foreach ($notifications as $notificationData) {
            Notification::create($notificationData);
        }

        // Create Settings
        $settings = [
            ['key' => 'site_name', 'value' => ['en' => 'CIDCO Mitra']],
            ['key' => 'site_description', 'value' => ['en' => 'City and Industrial Development Corporation']],
            ['key' => 'contact_email', 'value' => ['en' => 'info@cidcomitra.gov.in']],
            ['key' => 'contact_phone', 'value' => ['en' => '+91 22 2757 2000']],
            ['key' => 'contact_address', 'value' => ['en' => 'CIDCO Bhavan, CBD Belapur, Navi Mumbai - 400614']],
            ['key' => 'logo_url', 'value' => ['en' => '/images/logo.png']],
            ['key' => 'favicon_url', 'value' => ['en' => '/images/favicon.ico']],
            ['key' => 'primary_color', 'value' => ['en' => '#2563eb']],
            ['key' => 'secondary_color', 'value' => ['en' => '#64748b']],
            ['key' => 'meta_title', 'value' => ['en' => 'CIDCO Mitra - Urban Development Services']],
            ['key' => 'meta_description', 'value' => ['en' => 'Official portal for CIDCO services including building permits, property tax, and land acquisition']],
            ['key' => 'smtp_host', 'value' => ['en' => 'smtp.gmail.com']],
            ['key' => 'smtp_port', 'value' => ['en' => '587']],
            ['key' => 'smtp_username', 'value' => ['en' => '']],
            ['key' => 'smtp_password', 'value' => ['en' => '']],
        ];

        foreach ($settings as $setting) {
            Setting::firstOrCreate(
                ['key' => $setting['key']],
                $setting
            );
        }

        $this->command->info('✅ Complete data seeded successfully!');
        $this->command->info('📊 Created:');
        $this->command->info('   - 5 Users (1 Super Admin, 1 Admin, 1 Manager, 2 Agents)');
        $this->command->info('   - 4 Roles with Permissions');
        $this->command->info('   - 3 Services with Schedules');
        $this->command->info('   - 5 Leads with Timeline');
        $this->command->info('   - 3 Appointments');
        $this->command->info('   - 3 Notifications');
        $this->command->info('   - 15 Settings');
    }
}
