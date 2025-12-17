<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ServiceSchedule;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i:s',
            'message' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if slot is available
        $date = $request->appointment_date;
        $time = $request->appointment_time;
        $dayOfWeek = strtolower(\Carbon\Carbon::parse($date)->format('l'));

        $schedule = ServiceSchedule::where('service_id', $request->service_id)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->first();

        if (!$schedule) {
            return response()->json([
                'success' => false,
                'message' => 'No schedule available for the selected date'
            ], 400);
        }

        // Check slot capacity
        $bookedCount = Appointment::where('service_id', $request->service_id)
            ->where('appointment_date', $date)
            ->where('appointment_time', $time)
            ->whereIn('status', ['pending', 'confirmed'])
            ->count();

        if ($bookedCount >= $schedule->max_appointments_per_slot) {
            return response()->json([
                'success' => false,
                'message' => 'This time slot is fully booked. Please select another time.'
            ], 400);
        }

        $appointment = Appointment::create($request->all());



        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully',
            'data' => $appointment
        ], 201);
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
