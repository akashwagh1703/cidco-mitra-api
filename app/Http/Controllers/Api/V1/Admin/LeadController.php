<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\LeadNote;
use App\Models\LeadTimeline;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    private function caseFieldRules(bool $creating = false): array
    {
        $req = $creating ? 'required' : 'sometimes';

        return [
            'name' => "$req|string|max:255",
            'email' => 'nullable|email|max:255',
            'phone' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'message' => 'nullable|string',
            'source' => 'nullable|string|max:100',
            'status' => 'nullable|in:new,contacted,follow_up,converted,not_interested',
            'date_opened' => 'nullable|date',
            'client_location' => 'nullable|string|max:255',
            'cidco_node' => 'nullable|string|max:255',
            'ado_name' => 'nullable|string|max:255',
            'ado_contact' => ['nullable', 'regex:/^[0-9]{10}$/'],
            'cfc_file_no' => 'nullable|string|max:255',
            'service_type' => 'nullable|string|max:255',
            'case_status' => 'nullable|string|max:100',
            'gross_fee' => 'nullable|numeric|min:0',
            'paper_office_exp' => 'nullable|numeric|min:0',
            'cidco_fees' => 'nullable|numeric|min:0',
            'next_action_date' => 'nullable|date',
            'next_action_item' => 'nullable|string|max:500',
        ];
    }

    private function applyFilters($query, Request $request)
    {
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('case_status')) {
            $query->where('case_status', $request->case_status);
        }

        if ($request->filled('source')) {
            $query->where('source', $request->source);
        }

        if ($request->filled('cidco_node')) {
            $query->where('cidco_node', $request->cidco_node);
        }

        if ($request->filled('service_type')) {
            $query->where('service_type', $request->service_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cfc_file_no', 'like', "%{$search}%")
                    ->orWhere('ado_name', 'like', "%{$search}%")
                    ->orWhere('client_location', 'like', "%{$search}%");
            });
        }

        if ($request->filled('date_from')) {
            $query->whereDate($request->boolean('use_opened_date') ? 'date_opened' : 'created_at', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate($request->boolean('use_opened_date') ? 'date_opened' : 'created_at', '<=', $request->date_to);
        }

        return $query;
    }

    private function trackerColumns(): array
    {
        return [
            'id',
            'name',
            'email',
            'phone',
            'source',
            'status',
            'date_opened',
            'client_location',
            'cidco_node',
            'ado_name',
            'ado_contact',
            'cfc_file_no',
            'service_type',
            'case_status',
            'gross_fee',
            'paper_office_exp',
            'cidco_fees',
            'next_action_date',
            'next_action_item',
            'created_at',
            'updated_at',
        ];
    }

    public function index(Request $request)
    {
        $query = $this->applyFilters(Lead::query(), $request);

        // Slim payload for Master Case Tracker / list views
        $query->select($this->trackerColumns());

        $perPage = min((int) $request->get('per_page', 10), 100);
        $sortBy = $request->get('sort_by', 'created_at');
        $sortDir = $request->get('sort_dir', 'desc') === 'asc' ? 'asc' : 'desc';

        $allowedSorts = [
            'id', 'created_at', 'date_opened', 'name', 'case_status',
            'cidco_node', 'service_type', 'source', 'gross_fee',
        ];
        if (!in_array($sortBy, $allowedSorts, true)) {
            $sortBy = 'created_at';
        }

        $leads = $query->orderBy($sortBy, $sortDir)->orderBy('id', 'asc')->paginate($perPage);

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

    private function normalizePhoneFields(Request $request): void
    {
        foreach (['phone', 'ado_contact'] as $field) {
            if ($request->exists($field) && trim((string) $request->input($field)) === '') {
                $request->merge([$field => null]);
            }
        }
    }

    public function store(Request $request)
    {
        $this->normalizePhoneFields($request);
        $validated = $request->validate($this->caseFieldRules(true));

        $validated['source'] = $validated['source'] ?? 'admin';
        $validated['status'] = $validated['status'] ?? 'new';
        $validated['email'] = !empty($validated['email']) ? $validated['email'] : 'na@cidcomitra.local';
        // leads.phone is NOT NULL — store blank when omitted
        $validated['phone'] = !empty($validated['phone']) ? $validated['phone'] : '';
        $validated['date_opened'] = $validated['date_opened'] ?? now()->toDateString();
        $validated['case_status'] = $validated['case_status'] ?? 'In-Process at CIDCO';
        $validated['gross_fee'] = $validated['gross_fee'] ?? 0;
        $validated['paper_office_exp'] = $validated['paper_office_exp'] ?? 0;
        $validated['cidco_fees'] = $validated['cidco_fees'] ?? 0;

        $lead = Lead::create($validated);

        LeadTimeline::create([
            'lead_id' => $lead->id,
            'event_type' => 'created',
            'event_data' => [
                'message' => 'Case created from Advance Lead tracker',
                'source' => $validated['source'],
                'case_status' => $validated['case_status'],
            ],
            'created_by' => auth()->id(),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully',
            'data' => $lead->fresh(),
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $this->normalizePhoneFields($request);
        $validated = $request->validate($this->caseFieldRules(false));

        if (array_key_exists('email', $validated) && !$validated['email']) {
            $validated['email'] = 'na@cidcomitra.local';
        }
        if (array_key_exists('phone', $validated) && !$validated['phone']) {
            $validated['phone'] = '';
        }
        if (array_key_exists('ado_contact', $validated) && !$validated['ado_contact']) {
            $validated['ado_contact'] = null;
        }

        $changed = [];
        foreach ($validated as $key => $value) {
            if ($lead->{$key} != $value) {
                $changed[$key] = ['old' => $lead->{$key}, 'new' => $value];
            }
        }

        if (!empty($validated)) {
            $lead->fill($validated);
            $lead->save();
        }

        // Cell-level tracker edits skip timeline writes (keeps inline save snappy).
        // Still log meaningful status changes and multi-field form updates.
        $isTrackerCell = $request->header('X-Tracker-Cell') === '1' || $request->boolean('tracker_cell');
        $shouldLogTimeline = !empty($changed) && (
            !$isTrackerCell
            || isset($changed['case_status'])
            || isset($changed['status'])
        );

        if ($shouldLogTimeline) {
            LeadTimeline::create([
                'lead_id' => $lead->id,
                'event_type' => 'case_updated',
                'event_data' => ['changes' => $changed],
                'created_by' => auth()->id(),
            ]);
        }

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
            'data' => $lead->fresh(),
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
        $query = $this->applyFilters(Lead::query(), $request)->orderBy('date_opened', 'asc')->orderBy('id', 'asc');

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="master-case-tracker-' . date('Y-m-d') . '.csv"',
        ];

        return response()->stream(function () use ($query) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, [
                'Sr No', 'Date Opened', 'Month', 'Customer Name', 'Contact No', 'Client Location',
                'CIDCO Node', 'ADO Name', 'ADO Contact', 'CFC/File No', 'Service Type', 'Lead Source',
                'Current Status', 'Gross Fee (A)', 'Paper/Office Exp (B)', 'CIDCO Fees (C)',
                'Total Exp (B+C)', 'Net Profit', 'Margin %', 'Days Open', 'Next Action Date', 'Next Action Item',
            ]);

            $i = 1;
            $query->chunk(500, function ($leads) use ($handle, &$i) {
                foreach ($leads as $lead) {
                    fputcsv($handle, [
                        $i++,
                        $lead->date_opened ? $lead->date_opened->format('d-m-y') : '',
                        $lead->month ?? '',
                        $lead->name ?? '',
                        $lead->phone ?? '',
                        $lead->client_location ?? '',
                        $lead->cidco_node ?? '',
                        $lead->ado_name ?? '',
                        $lead->ado_contact ?? '',
                        $lead->cfc_file_no ?? '',
                        $lead->service_type ?? '',
                        $lead->source ?? '',
                        $lead->case_status ?? '',
                        $lead->gross_fee ?? 0,
                        $lead->paper_office_exp ?? 0,
                        $lead->cidco_fees ?? 0,
                        $lead->total_exp,
                        $lead->net_profit,
                        $lead->margin_percent !== null ? $lead->margin_percent . '%' : '',
                        $lead->days_open ?? '',
                        $lead->next_action_date ? $lead->next_action_date->format('d-m-y') : '',
                        $lead->next_action_item ?? '',
                    ]);
                }
            });

            fclose($handle);
        }, 200, $headers);
    }
}
