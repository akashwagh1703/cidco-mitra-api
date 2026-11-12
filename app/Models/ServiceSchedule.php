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
        $candidateTimes = [];
        $start = \Carbon\Carbon::parse($this->start_time);
        $end = \Carbon\Carbon::parse($this->end_time);

        while ($start->lt($end)) {
            $candidateTimes[] = $start->format('H:i:s');
            $start->addMinutes($this->slot_duration);
        }

        if (empty($candidateTimes)) {
            return [];
        }

        $bookedByTime = Appointment::query()
            ->select('appointment_time', \Illuminate\Support\Facades\DB::raw('COUNT(*) as booked'))
            ->where('service_id', $this->service_id)
            ->where('appointment_date', $date)
            ->whereIn('appointment_time', $candidateTimes)
            ->whereIn('status', ['pending', 'confirmed'])
            ->groupBy('appointment_time')
            ->pluck('booked', 'appointment_time');

        $slots = [];
        foreach ($candidateTimes as $slotTime) {
            $bookedCount = (int) ($bookedByTime[$slotTime] ?? 0);
            if ($bookedCount < $this->max_appointments_per_slot) {
                $slots[] = [
                    'time' => $slotTime,
                    'available' => true,
                    'booked' => $bookedCount,
                    'capacity' => $this->max_appointments_per_slot,
                ];
            }
        }

        return $slots;
    }
}
