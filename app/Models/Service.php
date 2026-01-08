<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Service extends Model
{
    protected $fillable = [
        'title',
        'description',
        'overview',
        'pricing',
        'documents',
        'timeline',
        'icon',
        'phone',
        'whatsapp',
        'appointment_url',
        'status',
        'order'
    ];

    protected $casts = [
        'title' => 'array',
        'description' => 'array',
        'overview' => 'array',
        'pricing' => 'array',
        'documents' => 'array',
        'timeline' => 'array',
        'status' => 'boolean'
    ];

    public function appointments(): HasMany
    {
        return $this->hasMany(Appointment::class);
    }
    
    public function schedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class);
    }
}