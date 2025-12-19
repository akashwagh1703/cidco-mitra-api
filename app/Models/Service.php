<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Service extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description', 
        'detailed_description',
        'requirements',
        'documents_required',
        'fees',
        'processing_time',
        'is_active',
        'is_online'
    ];

    protected $casts = [
        'requirements' => 'array',
        'documents_required' => 'array',
        'fees' => 'decimal:2',
        'is_active' => 'boolean',
        'is_online' => 'boolean'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ServiceCategory::class);
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ServiceSchedule::class);
    }
}