<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ServiceScheduleController extends Controller
{
    public function index($serviceId)
    {
        $schedules = ServiceSchedule::where('service_id', $serviceId)
            ->orderBy('day_of_week')
            ->get();

        return response()->json([
            'success' => true,
            'data' => $schedules
        ]);
    }

    public function store(Request $request, $serviceId)
    {
        $validator = Validator::make($request->all(), [
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'slot_duration' => 'required|integer|min:15|max:240',
            'max_appointments_per_slot' => 'required|integer|min:1|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = ServiceSchedule::create([
            'service_id' => $serviceId,
            ...$request->all()
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Schedule created successfully',
            'data' => $schedule
        ], 201);
    }

    public function update(Request $request, $serviceId, $id)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'slot_duration' => 'sometimes|integer|min:15|max:240',
            'max_appointments_per_slot' => 'sometimes|integer|min:1|max:50',
            'is_active' => 'boolean',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $schedule = ServiceSchedule::where('service_id', $serviceId)->findOrFail($id);
        $schedule->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Schedule updated successfully',
            'data' => $schedule
        ]);
    }

    public function destroy($serviceId, $id)
    {
        $schedule = ServiceSchedule::where('service_id', $serviceId)->findOrFail($id);
        $schedule->delete();

        return response()->json([
            'success' => true,
            'message' => 'Schedule deleted successfully'
        ]);
    }

    public function availableSlots(Request $request, $serviceId)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date|after_or_equal:today',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $date = $request->date;
        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));

        $schedule = ServiceSchedule::where('service_id', $serviceId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'No schedule available for this day'
            ]);
        }

        $slots = $schedule->getAvailableSlots($date);

        return response()->json([
            'success' => true,
            'data' => $slots
        ]);
    }
}
