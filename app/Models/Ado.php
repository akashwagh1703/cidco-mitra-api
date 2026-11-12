<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ado extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'contact',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'status' => 'boolean',
        ];
    }

    public function scopeActive($query)
    {
        return $query->where('status', true);
    }
}
