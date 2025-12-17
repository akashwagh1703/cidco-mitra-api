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
            'phone' => 'required|string|max:20',
            'message' => 'nullable|string',
            'source' => 'nullable|string|default:website',
        ]);

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
            'data' => $lead,
        ], 201);
    }
}
