<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'title' => [
                    'en' => 'Property Registration',
                    'mr' => 'मालमत्ता नोंदणी',
                    'hi' => 'संपत्ति पंजीकरण'
                ],
                'description' => [
                    'en' => 'Complete property registration and documentation services with legal assistance',
                    'mr' => 'कायदेशीर सहाय्यासह संपूर्ण मालमत्ता नोंदणी आणि दस्तऐवजीकरण सेवा',
                    'hi' => 'कानूनी सहायता के साथ पूर्ण संपत्ति पंजीकरण और दस्तावेज़ीकरण सेवाएं'
                ],
                'overview' => [
                    'en' => 'We provide end-to-end property registration services including document verification, legal consultation, and registration assistance. Our expert team ensures smooth and hassle-free property registration process.',
                    'mr' => 'आम्ही दस्तऐवज पडताळणी, कायदेशीर सल्ला आणि नोंदणी सहाय्यासह संपूर्ण मालमत्ता नोंदणी सेवा प्रदान करतो. आमची तज्ञ टीम सुरळीत आणि त्रासमुक्त मालमत्ता नोंदणी प्रक्रिया सुनिश्चित करते.',
                    'hi' => 'हम दस्तावेज़ सत्यापन, कानूनी परामर्श और पंजीकरण सहायता सहित संपत्ति पंजीकरण की संपूर्ण सेवाएं प्रदान करते हैं। हमारी विशेषज्ञ टीम सुचारू और परेशानी मुक्त संपत्ति पंजीकरण प्रक्रिया सुनिश्चित करती है।'
                ],
                'pricing' => [
                    'en' => "Starting from ₹50,000\nGovernment fees extra\nIncludes legal consultation",
                    'mr' => "₹50,000 पासून सुरुवात\nसरकारी शुल्क अतिरिक्त\nकायदेशीर सल्ला समाविष्ट",
                    'hi' => "₹50,000 से शुरू\nसरकारी शुल्क अतिरिक्त\nकानूनी परामर्श शामिल"
                ],
                'documents' => [
                    'en' => "- Aadhar Card\n- PAN Card\n- Property Documents\n- Sale Deed\n- NOC from Society\n- Property Tax Receipts",
                    'mr' => "- आधार कार्ड\n- पॅन कार्ड\n- मालमत्ता कागदपत्रे\n- विक्री विलेख\n- सोसायटीकडून एनओसी\n- मालमत्ता कर पावत्या",
                    'hi' => "- आधार कार्ड\n- पैन कार्ड\n- संपत्ति दस्तावेज़\n- बिक्री विलेख\n- सोसाइटी से एनओसी\n- संपत्ति कर रसीदें"
                ],
                'timeline' => [
                    'en' => "30-45 days\n- Document verification: 7 days\n- Legal review: 10 days\n- Registration: 15 days\n- Final approval: 7-15 days",
                    'mr' => "30-45 दिवस\n- कागदपत्र पडताळणी: 7 दिवस\n- कायदेशीर पुनरावलोकन: 10 दिवस\n- नोंदणी: 15 दिवस\n- अंतिम मंजुरी: 7-15 दिवस",
                    'hi' => "30-45 दिन\n- दस्तावेज़ सत्यापन: 7 दिन\n- कानूनी समीक्षा: 10 दिन\n- पंजीकरण: 15 दिन\n- अंतिम स्वीकृति: 7-15 दिन"
                ],
                'icon' => 'Building2',
                'phone' => '+91 9876543210',
                'whatsapp' => '+91 9876543210',
                'appointment_url' => 'https://calendly.com/cidco-mitra/property-registration',
                'status' => true,
                'order' => 1
            ],
            [
                'title' => [
                    'en' => 'Building Plan Approval',
                    'mr' => 'इमारत योजना मंजुरी',
                    'hi' => 'भवन योजना अनुमोदन'
                ],
                'description' => [
                    'en' => 'Fast-track building plan approval services with architectural consultation',
                    'mr' => 'वास्तुशास्त्रीय सल्ल्यासह जलद इमारत योजना मंजुरी सेवा',
                    'hi' => 'वास्तुशिल्प परामर्श के साथ तेज़ भवन योजना अनुमोदन सेवाएं'
                ],
                'overview' => [
                    'en' => 'Get your building plans approved quickly with our expert assistance. We handle all documentation, submissions, and follow-ups with municipal authorities.',
                    'mr' => 'आमच्या तज्ञ सहाय्याने तुमच्या इमारत योजना लवकर मंजूर करा. आम्ही सर्व दस्तऐवजीकरण, सबमिशन आणि नगरपालिका अधिकाऱ्यांसोबत फॉलो-अप हाताळतो.',
                    'hi' => 'हमारी विशेषज्ञ सहायता से अपनी भवन योजनाओं को जल्दी स्वीकृत करवाएं। हम सभी दस्तावेज़ीकरण, सबमिशन और नगरपालिका अधिकारियों के साथ फॉलो-अप संभालते हैं।'
                ],
                'pricing' => [
                    'en' => "Starting from ₹75,000\nVaries by plot size\nArchitectural consultation included",
                    'mr' => "₹75,000 पासून सुरुवात\nप्लॉट आकारानुसार बदलते\nवास्तुशास्त्रीय सल्ला समाविष्ट",
                    'hi' => "₹75,000 से शुरू\nप्लॉट के आकार के अनुसार भिन्न\nवास्तुशिल्प परामर्श शामिल"
                ],
                'documents' => [
                    'en' => "- Property Documents\n- Survey Plan\n- Architect Drawings\n- Structural Drawings\n- NOC from Fire Department\n- Environmental Clearance",
                    'mr' => "- मालमत्ता कागदपत्रे\n- सर्वेक्षण योजना\n- वास्तुविशारद रेखाचित्रे\n- संरचनात्मक रेखाचित्रे\n- अग्निशमन विभागाकडून एनओसी\n- पर्यावरण मंजुरी",
                    'hi' => "- संपत्ति दस्तावेज़\n- सर्वेक्षण योजना\n- वास्तुकार चित्र\n- संरचनात्मक चित्र\n- अग्निशमन विभाग से एनओसी\n- पर्यावरण मंजूरी"
                ],
                'timeline' => [
                    'en' => "45-60 days\n- Document preparation: 10 days\n- Submission: 5 days\n- Review process: 20-30 days\n- Approval: 10-15 days",
                    'mr' => "45-60 दिवस\n- दस्तऐवज तयारी: 10 दिवस\n- सबमिशन: 5 दिवस\n- पुनरावलोकन प्रक्रिया: 20-30 दिवस\n- मंजुरी: 10-15 दिवस",
                    'hi' => "45-60 दिन\n- दस्तावेज़ तैयारी: 10 दिन\n- सबमिशन: 5 दिन\n- समीक्षा प्रक्रिया: 20-30 दिन\n- अनुमोदन: 10-15 दिन"
                ],
                'icon' => 'FileText',
                'phone' => '+91 9876543211',
                'whatsapp' => '+91 9876543211',
                'appointment_url' => 'https://calendly.com/cidco-mitra/building-plan',
                'status' => true,
                'order' => 2
            ],
            [
                'title' => [
                    'en' => 'Property Tax Services',
                    'mr' => 'मालमत्ता कर सेवा',
                    'hi' => 'संपत्ति कर सेवाएं'
                ],
                'description' => [
                    'en' => 'Property tax assessment, payment, and dispute resolution services',
                    'mr' => 'मालमत्ता कर मूल्यांकन, देयक आणि विवाद निराकरण सेवा',
                    'hi' => 'संपत्ति कर मूल्यांकन, भुगतान और विवाद समाधान सेवाएं'
                ],
                'overview' => [
                    'en' => 'Comprehensive property tax services including assessment, online payment assistance, tax calculation, and dispute resolution with municipal authorities.',
                    'mr' => 'मूल्यांकन, ऑनलाइन देयक सहाय्य, कर गणना आणि नगरपालिका अधिकाऱ्यांसोबत विवाद निराकरणासह सर्वसमावेशक मालमत्ता कर सेवा.',
                    'hi' => 'मूल्यांकन, ऑनलाइन भुगतान सहायता, कर गणना और नगरपालिका अधिकारियों के साथ विवाद समाधान सहित व्यापक संपत्ति कर सेवाएं।'
                ],
                'pricing' => [
                    'en' => "Starting from ₹5,000\nAnnual service available\nDispute resolution extra",
                    'mr' => "₹5,000 पासून सुरुवात\nवार्षिक सेवा उपलब्ध\nविवाद निराकरण अतिरिक्त",
                    'hi' => "₹5,000 से शुरू\nवार्षिक सेवा उपलब्ध\nविवाद समाधान अतिरिक्त"
                ],
                'documents' => [
                    'en' => "- Property Documents\n- Previous Tax Receipts\n- Ownership Proof\n- Property Card",
                    'mr' => "- मालमत्ता कागदपत्रे\n- मागील कर पावत्या\n- मालकी पुरावा\n- मालमत्ता कार्ड",
                    'hi' => "- संपत्ति दस्तावेज़\n- पिछली कर रसीदें\n- स्वामित्व प्रमाण\n- संपत्ति कार्ड"
                ],
                'timeline' => [
                    'en' => "7-15 days\n- Assessment: 3-5 days\n- Payment processing: 2-3 days\n- Receipt generation: 2-7 days",
                    'mr' => "7-15 दिवस\n- मूल्यांकन: 3-5 दिवस\n- देयक प्रक्रिया: 2-3 दिवस\n- पावती निर्मिती: 2-7 दिवस",
                    'hi' => "7-15 दिन\n- मूल्यांकन: 3-5 दिन\n- भुगतान प्रसंस्करण: 2-3 दिन\n- रसीद निर्माण: 2-7 दिन"
                ],
                'icon' => 'Users',
                'phone' => '+91 9876543212',
                'whatsapp' => '+91 9876543212',
                'appointment_url' => 'https://calendly.com/cidco-mitra/property-tax',
                'status' => true,
                'order' => 3
            ],
            [
                'title' => [
                    'en' => 'Legal Consultation',
                    'mr' => 'कायदेशीर सल्ला',
                    'hi' => 'कानूनी परामर्श'
                ],
                'description' => [
                    'en' => 'Expert legal consultation for property and real estate matters',
                    'mr' => 'मालमत्ता आणि रिअल इस्टेट प्रकरणांसाठी तज्ञ कायदेशीर सल्ला',
                    'hi' => 'संपत्ति और रियल एस्टेट मामलों के लिए विशेषज्ञ कानूनी परामर्श'
                ],
                'overview' => [
                    'en' => 'Get expert legal advice on property transactions, disputes, documentation, and compliance. Our experienced lawyers provide comprehensive legal support.',
                    'mr' => 'मालमत्ता व्यवहार, विवाद, दस्तऐवजीकरण आणि अनुपालनावर तज्ञ कायदेशीर सल्ला घ्या. आमचे अनुभवी वकील सर्वसमावेशक कायदेशीर समर्थन प्रदान करतात.',
                    'hi' => 'संपत्ति लेनदेन, विवाद, दस्तावेज़ीकरण और अनुपालन पर विशेषज्ञ कानूनी सलाह प्राप्त करें। हमारे अनुभवी वकील व्यापक कानूनी सहायता प्रदान करते हैं।'
                ],
                'pricing' => [
                    'en' => "Starting from ₹10,000\nPer consultation\nPackages available",
                    'mr' => "₹10,000 पासून सुरुवात\nप्रति सल्ला\nपॅकेजेस उपलब्ध",
                    'hi' => "₹10,000 से शुरू\nप्रति परामर्श\nपैकेज उपलब्ध"
                ],
                'documents' => [
                    'en' => "- Property Documents\n- Previous Agreements\n- Court Orders (if any)\n- Correspondence Records",
                    'mr' => "- मालमत्ता कागदपत्रे\n- मागील करार\n- न्यायालयीन आदेश (असल्यास)\n- पत्रव्यवहार नोंदी",
                    'hi' => "- संपत्ति दस्तावेज़\n- पिछले समझौते\n- न्यायालय के आदेश (यदि कोई हो)\n- पत्राचार रिकॉर्ड"
                ],
                'timeline' => [
                    'en' => "Immediate to 7 days\n- Initial consultation: Same day\n- Document review: 2-3 days\n- Legal opinion: 3-7 days",
                    'mr' => "तात्काळ ते 7 दिवस\n- प्रारंभिक सल्ला: त्याच दिवशी\n- दस्तऐवज पुनरावलोकन: 2-3 दिवस\n- कायदेशीर मत: 3-7 दिवस",
                    'hi' => "तत्काल से 7 दिन\n- प्रारंभिक परामर्श: उसी दिन\n- दस्तावेज़ समीक्षा: 2-3 दिन\n- कानूनी राय: 3-7 दिन"
                ],
                'icon' => 'Settings',
                'phone' => '+91 9876543213',
                'whatsapp' => '+91 9876543213',
                'appointment_url' => 'https://calendly.com/cidco-mitra/legal-consultation',
                'status' => true,
                'order' => 4
            ]
        ];

        foreach ($services as $service) {
            Service::create($service);
        }
    }
}
