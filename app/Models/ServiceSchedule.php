<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ServiceSchedule extends Model
{
    protected $fillable = [
        'service_id',
        'day_of_week',
        'start_time',
        'end_time',
        'slot_duration',
        'max_appointments_per_slot',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'slot_duration' => 'integer',
        'max_appointments_per_slot' => 'integer',
    ];

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }

    public function getAvailableSlots($date)
    {
        $slots = [];
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        while ($start->lt($end)) {
            $slotTime = $start->format('H:i:s');
            
            // Check existing appointments for this slot
            $bookedCount = Appointment::where('service_id', $this->service_id)
                ->where('appointment_date', $date)
                ->where('appointment_time', $slotTime)
                ->whereIn('status', ['pending', 'confirmed'])
                ->count();

            if ($bookedCount < $this->max_appointments_per_slot) {
                $slots[] = [
                    'time' => $slotTime,
                    'available' => true,
                    'booked' => $bookedCount,
                    'capacity' => $this->max_appointments_per_slot,
                ];
            }

            $start->addMinutes($this->slot_duration);
        }

        return $slots;
    }
}
