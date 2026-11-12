<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Notification;
use App\Models\Service;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;

class PublicController extends Controller
{
    public function getSettings()
    {
        $settings = Setting::all()->pluck('value', 'key');
        
        // Add full URLs for branding images
        $branding = $settings['branding'] ?? [];
        if (isset($branding['logo'])) {
            $branding['logo_url'] = \Illuminate\Support\Facades\Storage::url($branding['logo']);
        }
        if (isset($branding['favicon'])) {
            $branding['favicon_url'] = \Illuminate\Support\Facades\Storage::url($branding['favicon']);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'general' => $settings['general'] ?? [],
                'branding' => $branding,
                'homepage' => $settings['homepage'] ?? [],
                'seo' => $settings['seo'] ?? [],
            ],
        ]);
    }

    public function submitLead(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'message' => 'required|string|max:2000',
            'website' => 'nullable|max:0', // honeypot
        ]);

        $lead = Lead::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'] ?? null,
            'message' => $validated['message'],
            'source' => 'website',
            'status' => 'new',
        ]);

        // Create notification for all users with manage_leads permission
        $users = User::permission('manage_leads')->get();
        
        foreach ($users as $user) {
            Notification::create([
                'user_id' => $user->id,
                'type' => 'new_lead',
                'title' => 'New Lead from Website',
                'message' => "New lead received from {$lead->name} ({$lead->email})",
                'data' => [
                    'lead_id' => $lead->id,
                    'lead_name' => $lead->name,
                    'lead_email' => $lead->email,
                    'source' => 'website',
                ],
                'read_at' => null,
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Thank you! Your message has been received. We will contact you soon.',
            'data' => [
                'id' => $lead->id,
            ],
        ], 201);
    }

    public function getStats()
    {
        // Public marketing stats — do not expose raw lead counts
        $homepage = Setting::get('homepage') ?? [];

        return response()->json([
            'success' => true,
            'data' => [
                'total_clients' => (int) ($homepage['stat_clients'] ?? 500),
                'success_rate' => (int) ($homepage['stat_success_rate'] ?? 95),
                'years_experience' => (int) ($homepage['stat_years'] ?? 15),
                'awards_won' => (int) ($homepage['stat_awards'] ?? 50),
            ],
        ]);
    }

    public function getServices()
    {
        $services = Service::where('status', true)
            ->orderBy('order')
            ->get();
            
        return response()->json([
            'success' => true, 
            'data' => $services
        ]);
    }
}
