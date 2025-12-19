<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\User;
use App\Models\ServiceCategory;
use App\Models\Service;
use App\Models\ServiceSchedule;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Setting;

class ServicesDataSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('appointments')->truncate();
        DB::table('service_schedules')->truncate();
        DB::table('services')->truncate();
        DB::table('service_categories')->truncate();
        DB::table('leads')->truncate();
        DB::table('settings')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // Create service categories
        $categories = [
            [
                'id' => 1,
                'name' => 'Property Registration',
                'description' => 'All property and land registration related services including documentation, verification, and legal formalities.',
                'icon' => 'building',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'name' => 'Building Permissions',
                'description' => 'Construction and building approval services including plan approval, NOC, and occupancy certificates.',
                'icon' => 'hammer',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 3,
                'name' => 'Water & Sewerage',
                'description' => 'Water connection and sewerage services including new connections, repairs, and maintenance.',
                'icon' => 'droplet',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'name' => 'Trade License',
                'description' => 'Business and trade licensing services for shops, factories, and commercial establishments.',
                'icon' => 'briefcase',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 5,
                'name' => 'Tax & Revenue',
                'description' => 'Property tax, professional tax, and other revenue related services.',
                'icon' => 'calculator',
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('service_categories')->insert($categories);

        // Create comprehensive services
        $services = [
            // Property Registration Services
            [
                'id' => 1,
                'category_id' => 1,
                'name' => 'Property Registration',
                'description' => 'Complete property registration and documentation service with legal verification.',
                'detailed_description' => 'This comprehensive service covers complete property registration including document verification, stamp duty calculation, registration fee payment, and final registration certificate issuance. Our expert team ensures all legal formalities are completed accurately and efficiently.',
                'requirements' => json_encode([
                    'Original Sale Deed with seller signature',
                    'Property Card/7/12 Extract (latest)',
                    'Survey Settlement Record',
                    'Non-Agricultural Permission (if applicable)',
                    'Identity Proof (Aadhar Card - original + copy)',
                    'Address Proof (Utility bill/Bank statement)',
                    'PAN Card (original + copy)',
                    'Passport Size Photographs (4 copies)',
                    'Stamp duty payment receipt',
                    'Registration fee payment receipt'
                ]),
                'documents_required' => json_encode([
                    'Sale Deed (Original + 3 certified copies)',
                    'Property Documents (Title deed, previous registrations)',
                    'Identity & Address Proof of all parties',
                    'PAN Card copies of all parties',
                    'Passport size photographs',
                    'Witness identity proofs (2 witnesses required)'
                ]),
                'fees' => 5000.00,
                'processing_time' => '15-20 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 2,
                'category_id' => 1,
                'name' => 'Property Transfer',
                'description' => 'Property ownership transfer services with complete legal documentation.',
                'detailed_description' => 'Professional service for transferring property ownership from one party to another including all legal formalities, documentation verification, and registration procedures.',
                'requirements' => json_encode([
                    'Transfer Deed executed on stamp paper',
                    'Original Property Documents',
                    'No Objection Certificate from society/builder',
                    'Tax Clearance Certificate',
                    'Identity Proof of both parties',
                    'Address Proof of both parties',
                    'PAN Cards of both parties'
                ]),
                'documents_required' => json_encode([
                    'Transfer Deed (properly executed)',
                    'Property Documents (original + copies)',
                    'NOC Certificate from relevant authority',
                    'Tax Clearance Certificate',
                    'ID Proofs of transferor and transferee',
                    'Witness documents'
                ]),
                'fees' => 3500.00,
                'processing_time' => '10-15 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Building Permissions
            [
                'id' => 3,
                'category_id' => 2,
                'name' => 'Building Plan Approval',
                'description' => 'New construction building plan approval with architectural verification.',
                'detailed_description' => 'Complete building plan approval service for new construction including architectural plan verification, structural design approval, FSI calculation, and construction permission issuance.',
                'requirements' => json_encode([
                    'Architectural Drawings (5 sets)',
                    'Structural Drawings with engineer signature',
                    'Site Plan with survey details',
                    'Property Documents (clear title)',
                    'NOC from Fire Department',
                    'Environmental Clearance (if required)',
                    'Parking layout plan',
                    'Drainage and plumbing layout'
                ]),
                'documents_required' => json_encode([
                    'Building Plans (5 complete sets)',
                    'Property Documents with clear title',
                    'Site Survey by licensed surveyor',
                    'NOC Certificates (Fire, Environment)',
                    'Application Form (duly filled)',
                    'Architect/Engineer license copies'
                ]),
                'fees' => 8000.00,
                'processing_time' => '30-45 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 4,
                'category_id' => 2,
                'name' => 'Occupancy Certificate',
                'description' => 'Building occupancy certificate issuance after construction completion.',
                'detailed_description' => 'Occupancy certificate for completed buildings ensuring compliance with approved plans, safety regulations, and building codes.',
                'requirements' => json_encode([
                    'Completion Certificate from architect',
                    'Approved Building Plans (original)',
                    'Structural Safety Certificate',
                    'Fire Safety Certificate',
                    'Electrical Safety Certificate',
                    'Plumbing completion certificate',
                    'Lift safety certificate (if applicable)'
                ]),
                'documents_required' => json_encode([
                    'Completion Certificate (architect signed)',
                    'All Safety Certificates',
                    'Approved Plans with amendments',
                    'Inspection Reports',
                    'Utility connection certificates'
                ]),
                'fees' => 4500.00,
                'processing_time' => '15-20 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Water & Sewerage
            [
                'id' => 5,
                'category_id' => 3,
                'name' => 'New Water Connection',
                'description' => 'New domestic water connection service with meter installation.',
                'detailed_description' => 'Complete service for new domestic water connection including application processing, site inspection, pipeline connection, meter installation, and connection activation.',
                'requirements' => json_encode([
                    'Property Ownership Documents',
                    'Building Plan Approval copy',
                    'Identity Proof (Aadhar Card)',
                    'Address Proof (latest utility bill)',
                    'Site Plan with water line marking',
                    'Plumbing Layout approved by engineer',
                    'Security deposit payment receipt'
                ]),
                'documents_required' => json_encode([
                    'Property Documents (ownership proof)',
                    'Building Approval Certificate',
                    'ID & Address Proof',
                    'Site Plan with measurements',
                    'Plumber license (for internal work)'
                ]),
                'fees' => 2500.00,
                'processing_time' => '7-10 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 6,
                'category_id' => 3,
                'name' => 'Sewerage Connection',
                'description' => 'New sewerage line connection service with inspection chamber.',
                'detailed_description' => 'New sewerage connection service including pipeline connection, inspection chamber installation, connection to main sewerage line, and system testing.',
                'requirements' => json_encode([
                    'Property Documents (clear title)',
                    'Water Connection Certificate',
                    'Drainage Plan approved by engineer',
                    'Site Survey Report',
                    'Environmental Clearance',
                    'Building plan with drainage layout'
                ]),
                'documents_required' => json_encode([
                    'Property Documents',
                    'Water Connection Certificate',
                    'Drainage Plan (engineer approved)',
                    'Survey Report with levels',
                    'Environmental NOC'
                ]),
                'fees' => 3500.00,
                'processing_time' => '10-15 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Trade License
            [
                'id' => 7,
                'category_id' => 4,
                'name' => 'Shop License',
                'description' => 'New shop establishment license for retail businesses.',
                'detailed_description' => 'Complete shop license service for retail businesses including application processing, site verification, compliance check, and license issuance.',
                'requirements' => json_encode([
                    'Shop Ownership/Rent Agreement',
                    'Business Registration Certificate',
                    'Identity Proof (Aadhar Card)',
                    'Address Proof of shop and owner',
                    'Fire Safety Certificate',
                    'Pollution Control Certificate',
                    'GST Registration (if applicable)',
                    'Partnership deed (if partnership)'
                ]),
                'documents_required' => json_encode([
                    'Shop Documents (ownership/rent)',
                    'Business Registration',
                    'ID Proofs of all partners',
                    'Safety Certificates (Fire, Pollution)',
                    'Bank account details'
                ]),
                'fees' => 1500.00,
                'processing_time' => '7-10 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 8,
                'category_id' => 4,
                'name' => 'Factory License',
                'description' => 'Industrial factory establishment license with environmental clearance.',
                'detailed_description' => 'Complete factory license for industrial establishments including environmental clearance, safety compliance, operational permits, and regulatory approvals.',
                'requirements' => json_encode([
                    'Land/Factory Documents (clear title)',
                    'Industrial License from state govt',
                    'Environmental Clearance Certificate',
                    'Fire Safety Certificate',
                    'Pollution Control Board NOC',
                    'Factory Layout Plan (detailed)',
                    'Machinery installation certificates',
                    'Labor license and compliance'
                ]),
                'documents_required' => json_encode([
                    'Factory Documents (ownership/lease)',
                    'Industrial License',
                    'Environmental NOC',
                    'Safety Certificates (Fire, Electrical)',
                    'Layout Plans (factory and machinery)',
                    'Compliance certificates'
                ]),
                'fees' => 15000.00,
                'processing_time' => '45-60 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            // Tax & Revenue
            [
                'id' => 9,
                'category_id' => 5,
                'name' => 'Property Tax Assessment',
                'description' => 'Property tax calculation and assessment service.',
                'detailed_description' => 'Professional property tax assessment service including property valuation, tax calculation, assessment certificate issuance, and payment processing.',
                'requirements' => json_encode([
                    'Property Documents (latest)',
                    'Previous tax receipts',
                    'Property measurement certificate',
                    'Construction completion certificate',
                    'Occupancy certificate',
                    'Identity proof of owner'
                ]),
                'documents_required' => json_encode([
                    'Property Documents',
                    'Previous tax records',
                    'Measurement certificate',
                    'Construction certificates',
                    'Owner ID proof'
                ]),
                'fees' => 1000.00,
                'processing_time' => '5-7 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'id' => 10,
                'category_id' => 5,
                'name' => 'Professional Tax Registration',
                'description' => 'Professional tax registration for businesses and professionals.',
                'detailed_description' => 'Complete professional tax registration service for businesses, professionals, and employees including registration, certificate issuance, and compliance guidance.',
                'requirements' => json_encode([
                    'Business registration certificate',
                    'PAN Card of business/individual',
                    'Address proof of business',
                    'Employee list (if applicable)',
                    'Salary structure details',
                    'Bank account details'
                ]),
                'documents_required' => json_encode([
                    'Business registration',
                    'PAN Card',
                    'Address proof',
                    'Employee details',
                    'Bank account proof'
                ]),
                'fees' => 800.00,
                'processing_time' => '3-5 working days',
                'is_active' => true,
                'is_online' => true,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('services')->insert($services);

        // Create service schedules for each service
        $schedules = [];
        $days = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'];
        
        foreach ($services as $service) {
            foreach ($days as $day) {
                $schedules[] = [
                    'service_id' => $service['id'],
                    'day_of_week' => $day,
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'slot_duration' => 30,
                    'max_appointments_per_slot' => 5,
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        DB::table('service_schedules')->insert($schedules);

        // Create sample appointments
        $appointments = [
            [
                'service_id' => 1,
                'name' => 'Rajesh Kumar',
                'email' => 'rajesh.kumar@email.com',
                'phone' => '+91 9876543210',
                'appointment_date' => Carbon::now()->addDays(2)->format('Y-m-d'),
                'appointment_time' => '10:00:00',
                'status' => 'confirmed',
                'notes' => 'Property registration for new flat purchase',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_id' => 3,
                'name' => 'Priya Sharma',
                'email' => 'priya.sharma@email.com',
                'phone' => '+91 9876543211',
                'appointment_date' => Carbon::now()->addDays(3)->format('Y-m-d'),
                'appointment_time' => '11:30:00',
                'status' => 'pending',
                'notes' => 'Building plan approval for residential construction',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_id' => 5,
                'name' => 'Amit Patel',
                'email' => 'amit.patel@email.com',
                'phone' => '+91 9876543212',
                'appointment_date' => Carbon::now()->addDays(1)->format('Y-m-d'),
                'appointment_time' => '14:00:00',
                'status' => 'confirmed',
                'notes' => 'New water connection for residential property',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('appointments')->insert($appointments);

        // Create sample leads
        $leads = [
            [
                'name' => 'Sunita Desai',
                'email' => 'sunita.desai@email.com',
                'phone' => '+91 9876543213',
                'message' => 'I need help with property registration for my new house. Please contact me.',
                'source' => 'website',
                'status' => 'new',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'Vikram Singh',
                'email' => 'vikram.singh@email.com',
                'phone' => '+91 9876543214',
                'message' => 'Looking for building plan approval services. What documents are required?',
                'source' => 'phone',
                'status' => 'contacted',
                'created_at' => now()->subDays(1),
                'updated_at' => now()
            ],
            [
                'name' => 'Meera Joshi',
                'email' => 'meera.joshi@email.com',
                'phone' => '+91 9876543215',
                'message' => 'Need water connection for my new shop. How long does it take?',
                'source' => 'website',
                'status' => 'converted',
                'created_at' => now()->subDays(3),
                'updated_at' => now()
            ]
        ];

        DB::table('leads')->insert($leads);

        // Create system settings
        $settings = [
            [
                'key' => 'general',
                'value' => json_encode([
                    'site_name' => 'CIDCO Mitra',
                    'site_description' => 'Your trusted partner for all CIDCO related services',
                    'contact_email' => 'info@cidcomitra.com',
                    'contact_phone' => '+91 22 1234 5678',
                    'address' => 'CIDCO Bhavan, CBD Belapur, Navi Mumbai - 400614',
                    'working_hours' => 'Monday to Saturday: 9:00 AM - 5:00 PM'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'branding',
                'value' => json_encode([
                    'logo' => '/images/logo.png',
                    'favicon' => '/images/favicon.ico',
                    'primary_color' => '#1e40af',
                    'secondary_color' => '#3b82f6'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'seo',
                'value' => json_encode([
                    'meta_title' => 'CIDCO Mitra - Official CIDCO Services Portal',
                    'meta_description' => 'Get all CIDCO related services including property registration, building permissions, water connections, and trade licenses through our official portal.',
                    'meta_keywords' => 'CIDCO, property registration, building permission, water connection, trade license, Navi Mumbai'
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'key' => 'homepage',
                'value' => json_encode([
                    'hero_title' => 'Welcome to CIDCO Mitra',
                    'hero_subtitle' => 'Your one-stop solution for all CIDCO related services',
                    'hero_description' => 'We provide comprehensive services for property registration, building permissions, utility connections, and business licenses in CIDCO areas.',
                    'features' => [
                        'Online appointment booking',
                        'Document verification',
                        'Expert guidance',
                        'Quick processing'
                    ]
                ]),
                'created_at' => now(),
                'updated_at' => now()
            ]
        ];

        DB::table('settings')->insert($settings);

        echo "\n=== CIDCO Mitra Services Data Seeded Successfully! ===\n";
        echo "📁 Categories: " . count($categories) . " created\n";
        echo "🔧 Services: " . count($services) . " created\n";
        echo "📅 Schedules: " . count($schedules) . " created\n";
        echo "📋 Appointments: " . count($appointments) . " created\n";
        echo "👥 Leads: " . count($leads) . " created\n";
        echo "⚙️ Settings: " . count($settings) . " created\n";
        echo "\n✅ All data with relationships loaded successfully!\n";
    }
}