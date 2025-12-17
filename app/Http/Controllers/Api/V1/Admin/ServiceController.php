<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceController extends Controller
{
    public function index(Request $request)
    {
        $query = Service::orderBy('order');
        
        // Include appointment counts if requested
        if ($request->boolean('include_appointments')) {
            $query->withCount('appointments');
        }
        
        $services = $query->get();
        return response()->json(['success' => true, 'data' => $services]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'description' => 'required|array',
            'description.en' => 'required|string',
            'overview' => 'nullable|array',
            'pricing' => 'nullable|array',
            'documents' => 'nullable|array',
            'timeline' => 'nullable|array',
            'icon' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'appointment_url' => 'nullable|url|max:255',
            'status' => 'boolean',
            'order' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $service = Service::create($request->all());
        return response()->json(['success' => true, 'data' => $service, 'message' => 'Service created successfully']);
    }

    public function show(string $id)
    {
        $service = Service::findOrFail($id);
        return response()->json(['success' => true, 'data' => $service]);
    }

    public function update(Request $request, string $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|array',
            'title.en' => 'required|string|max:255',
            'description' => 'required|array',
            'description.en' => 'required|string',
            'overview' => 'nullable|array',
            'pricing' => 'nullable|array',
            'documents' => 'nullable|array',
            'timeline' => 'nullable|array',
            'icon' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'whatsapp' => 'nullable|string|max:20',
            'appointment_url' => 'nullable|url|max:255',
            'status' => 'boolean',
            'order' => 'integer'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $service = Service::findOrFail($id);
        $service->update($request->all());
        return response()->json(['success' => true, 'data' => $service, 'message' => 'Service updated successfully']);
    }

    public function destroy(string $id)
    {
        $service = Service::findOrFail($id);
        $service->delete();
        return response()->json(['success' => true, 'message' => 'Service deleted successfully']);
    }
}
