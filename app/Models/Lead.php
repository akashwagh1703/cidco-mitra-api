<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'message',
        'status',
        'source',
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
    ];

    protected $appends = [
        'month',
        'total_exp',
        'net_profit',
        'margin_percent',
        'days_open',
    ];

    protected function casts(): array
    {
        return [
            'date_opened' => 'date',
            'next_action_date' => 'date',
            'gross_fee' => 'decimal:2',
            'paper_office_exp' => 'decimal:2',
            'cidco_fees' => 'decimal:2',
            'created_at' => 'datetime',
            'updated_at' => 'datetime',
            'deleted_at' => 'datetime',
        ];
    }

    public function notes()
    {
        return $this->hasMany(LeadNote::class);
    }

    public function timeline()
    {
        return $this->hasMany(LeadTimeline::class)->orderBy('created_at', 'desc');
    }

    public function getMonthAttribute(): ?string
    {
        return $this->date_opened?->format('M');
    }

    public function getTotalExpAttribute(): float
    {
        return round((float) ($this->paper_office_exp ?? 0) + (float) ($this->cidco_fees ?? 0), 2);
    }

    public function getNetProfitAttribute(): float
    {
        return round((float) ($this->gross_fee ?? 0) - $this->total_exp, 2);
    }

    public function getMarginPercentAttribute(): ?float
    {
        $gross = (float) ($this->gross_fee ?? 0);
        if ($gross <= 0) {
            return null;
        }

        return round(($this->net_profit / $gross) * 100, 2);
    }

    public function getDaysOpenAttribute(): ?int
    {
        if (!$this->date_opened) {
            return null;
        }

        $status = strtolower(trim((string) $this->case_status));
        if ($status === 'completed') {
            return null;
        }

        return $this->date_opened->copy()->startOfDay()->diffInDays(Carbon::today());
    }
}
