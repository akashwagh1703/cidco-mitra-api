<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadTimeline;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->orderBy('created_at', 'desc')->paginate(10);

        return response()->json([
            'success' => true,
            'data' => $leads,
        ]);
    }

    public function show($id)
    {
        $lead = Lead::with(['notes.creator', 'timeline.creator'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'data' => $lead,
        ]);
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|max:255',
            'phone' => 'sometimes|string|max:20',
            'message' => 'nullable|string',
        ]);

        $lead->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully',
            'data' => $lead,
        ]);
    }

    public function updateStatus(Request $request, $id)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,contacted,follow_up,converted,not_interested',
        ]);

        $lead = Lead::findOrFail($id);
        $oldStatus = $lead->status;
        $lead->update(['status' => $validated['status']]);

        // Create timeline entry
        LeadTimeline::create([
            'lead_id' => $lead->id,
            'event_type' => 'status_change',
            'event_data' => [
                'old_status' => $oldStatus,
                'new_status' => $validated['status'],
            ],
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead status updated successfully',
            'data' => $lead,
        ]);
    }

    public function addNote(Request $request, $id)
    {
        $validated = $request->validate([
            'note' => 'required|string',
        ]);

        $lead = Lead::findOrFail($id);

        $note = LeadNote::create([
            'lead_id' => $lead->id,
            'note' => $validated['note'],
            'created_by' => auth()->id(),
        ]);

        // Create timeline entry
        LeadTimeline::create([
            'lead_id' => $lead->id,
            'event_type' => 'note_added',
            'event_data' => ['note' => $validated['note']],
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Note added successfully',
            'data' => $note->load('creator'),
        ]);
    }

    public function timeline($id)
    {
        $lead = Lead::findOrFail($id);
        $timeline = $lead->timeline()->with('creator')->get();

        return response()->json([
            'success' => true,
            'data' => $timeline,
        ]);
    }

    public function destroy($id)
    {
        $lead = Lead::findOrFail($id);
        $lead->delete();

        return response()->json([
            'success' => true,
            'message' => 'Lead deleted successfully',
        ]);
    }

    public function export(Request $request)
    {
        $query = Lead::query();

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        if ($request->has('source')) {
            $query->where('source', $request->source);
        }

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->has('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }

        if ($request->has('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $leads = $query->orderBy('created_at', 'desc')->get();

        // Generate CSV
        $csv = "Name,Email,Phone,Message,Source,Status,Created At\n";
        
        foreach ($leads as $lead) {
            $csv .= sprintf(
                "\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                str_replace('"', '""', $lead->name),
                str_replace('"', '""', $lead->email),
                str_replace('"', '""', $lead->phone),
                str_replace('"', '""', $lead->message ?? ''),
                str_replace('"', '""', $lead->source),
                str_replace('"', '""', $lead->status),
                $lead->created_at->format('Y-m-d H:i:s')
            );
        }

        return response($csv, 200)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="leads-' . date('Y-m-d') . '.csv"');
    }
}
