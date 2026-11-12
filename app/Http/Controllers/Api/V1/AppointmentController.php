<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\ServiceSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class AppointmentController extends Controller
{
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'service_id' => 'required|exists:services,id',
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => ['required', 'regex:/^[0-9]{10}$/'],
            'appointment_date' => 'required|date|after_or_equal:today',
            'appointment_time' => 'required|date_format:H:i:s',
            'message' => 'nullable|string|max:1000',
            'website' => 'nullable|max:0', // honeypot
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

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

        try {
            $appointment = DB::transaction(function () use ($request, $date, $time, $schedule) {
                $bookedCount = Appointment::where('service_id', $request->service_id)
                    ->where('appointment_date', $date)
                    ->where('appointment_time', $time)
                    ->whereIn('status', ['pending', 'confirmed'])
                    ->lockForUpdate()
                    ->count();

                if ($bookedCount >= $schedule->max_appointments_per_slot) {
                    throw new \RuntimeException('SLOT_FULL');
                }

                return Appointment::create([
                    'service_id' => $request->service_id,
                    'name' => $request->name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'appointment_date' => $date,
                    'appointment_time' => $time,
                    'message' => $request->message,
                    'status' => 'pending',
                ]);
            });
        } catch (\RuntimeException $e) {
            if ($e->getMessage() === 'SLOT_FULL') {
                return response()->json([
                    'success' => false,
                    'message' => 'This time slot is fully booked. Please select another time.'
                ], 400);
            }
            throw $e;
        }

        return response()->json([
            'success' => true,
            'message' => 'Appointment booked successfully',
            'data' => [
                'id' => $appointment->id,
                'appointment_date' => $appointment->appointment_date,
                'appointment_time' => $appointment->appointment_time,
                'status' => $appointment->status,
            ]
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
