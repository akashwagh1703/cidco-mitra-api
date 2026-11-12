<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadTimeline;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'message' => 'nullable|string|max:2000',
            'source' => 'nullable|string|max:50',
            'website' => 'nullable|max:0', // honeypot
        ]);

        unset($validated['website']);
        $validated['source'] = $validated['source'] ?? 'website';
        $validated['status'] = 'new';

        $lead = Lead::create($validated);

        // Create timeline entry
        LeadTimeline::create([
            'lead_id' => $lead->id,
            'event_type' => 'created',
            'event_data' => ['message' => 'Lead created'],
        ]);

        // Notify admins
        $admins = User::role(['Super Admin', 'Admin'])->get();
        foreach ($admins as $admin) {
            Notification::create([
                'user_id' => $admin->id,
                'type' => 'lead',
                'title' => 'New Lead Received',
                'message' => "{$lead->name} submitted a new inquiry",
                'data' => ['lead_id' => $lead->id],
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Lead submitted successfully',
            'data' => ['id' => $lead->id],
        ], 201);
    }
}
