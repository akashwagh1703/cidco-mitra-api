<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lead;
use App\Models\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $totalLeads = Lead::count();
        $todayLeads = Lead::whereDate('created_at', today())->count();

        $leadsByStatus = Lead::select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $leadsLast7Days = Lead::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('count(*) as count')
            )
            ->where('created_at', '>=', now()->subDays(7))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $appointmentStats = [
            'total' => Appointment::count(),
            'pending' => Appointment::where('status', 'pending')->count(),
            'today' => Appointment::whereDate('appointment_date', today())->count(),
            'upcoming' => Appointment::upcoming()->count(),
        ];

        $advanceLeadStats = [
            'total_cases' => Lead::count(),
            'in_process' => Lead::where('case_status', 'In-Process at CIDCO')->count(),
            'completed' => Lead::where('case_status', 'Completed')->count(),
            'actions_due' => Lead::whereNotNull('next_action_date')
                ->whereDate('next_action_date', '<=', today())
                ->where(function ($q) {
                    $q->whereNull('case_status')
                        ->orWhere('case_status', '!=', 'Completed');
                })
                ->count(),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'total_leads' => $totalLeads,
                'today_leads' => $todayLeads,
                'leads_by_status' => $leadsByStatus,
                'leads_last_7_days' => $leadsLast7Days,
                'appointments' => $appointmentStats,
                'advance_leads' => $advanceLeadStats,
            ],
        ]);
    }

    /**
     * Operational charts for Appointments + Advance Lead (admin insights).
     */
    public function insights()
    {
        // --- Appointments ---
        $apptByStatus = Appointment::select('status', DB::raw('COUNT(*) as count'))
            ->groupBy('status')
            ->pluck('count', 'status');

        $apptTrendStart = now()->subDays(13)->startOfDay();
        $apptTrendRaw = Appointment::select(
                DB::raw('DATE(appointment_date) as date'),
                DB::raw('COUNT(*) as total'),
                DB::raw("SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending"),
                DB::raw("SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed"),
                DB::raw("SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed"),
                DB::raw("SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled")
            )
            ->where('appointment_date', '>=', $apptTrendStart->toDateString())
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $apptTrend = [];
        for ($d = $apptTrendStart->copy(); $d <= now()->endOfDay(); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $row = $apptTrendRaw->get($key);
            $apptTrend[] = [
                'date' => $key,
                'label' => $d->format('d M'),
                'total' => (int) ($row->total ?? 0),
                'pending' => (int) ($row->pending ?? 0),
                'confirmed' => (int) ($row->confirmed ?? 0),
                'completed' => (int) ($row->completed ?? 0),
                'cancelled' => (int) ($row->cancelled ?? 0),
            ];
        }

        $byService = Appointment::query()
            ->select('service_id', DB::raw('COUNT(*) as count'))
            ->whereNotNull('service_id')
            ->groupBy('service_id')
            ->orderByDesc('count')
            ->limit(8)
            ->get();

        $serviceIds = $byService->pluck('service_id')->all();
        $services = Service::whereIn('id', $serviceIds)->get()->keyBy('id');

        $apptByService = $byService->map(function ($row) use ($services) {
            $service = $services->get($row->service_id);
            $title = $service?->title;
            if (is_array($title)) {
                $name = $title['en'] ?? reset($title) ?: 'Service #' . $row->service_id;
            } elseif (is_string($title) && str_starts_with($title, '{')) {
                $decoded = json_decode($title, true);
                $name = is_array($decoded) ? ($decoded['en'] ?? 'Service #' . $row->service_id) : $title;
            } else {
                $name = $title ?: ('Service #' . $row->service_id);
            }

            return [
                'service_id' => $row->service_id,
                'name' => $name,
                'count' => (int) $row->count,
            ];
        })->values();

        // --- Advance Lead ops ---
        $caseByStatus = Lead::select('case_status', DB::raw('COUNT(*) as count'))
            ->whereNotNull('case_status')
            ->where('case_status', '!=', '')
            ->groupBy('case_status')
            ->pluck('count', 'case_status');

        $agingBase = Lead::query()
            ->where(function ($q) {
                $q->whereNull('case_status')
                    ->orWhere('case_status', '!=', 'Completed');
            });

        $openedExpr = "COALESCE(date_opened, DATE(created_at))";
        $aging = [
            '0_30' => (clone $agingBase)->whereRaw("{$openedExpr} IS NOT NULL AND DATEDIFF(CURDATE(), {$openedExpr}) <= 30")->count(),
            '31_60' => (clone $agingBase)->whereRaw("{$openedExpr} IS NOT NULL AND DATEDIFF(CURDATE(), {$openedExpr}) BETWEEN 31 AND 60")->count(),
            '61_90' => (clone $agingBase)->whereRaw("{$openedExpr} IS NOT NULL AND DATEDIFF(CURDATE(), {$openedExpr}) BETWEEN 61 AND 90")->count(),
            '90_plus' => (clone $agingBase)->whereRaw("{$openedExpr} IS NOT NULL AND DATEDIFF(CURDATE(), {$openedExpr}) > 90")->count(),
            'unknown' => (clone $agingBase)->whereRaw("{$openedExpr} IS NULL")->count(),
        ];

        $byNode = Lead::select('cidco_node', DB::raw('COUNT(*) as count'))
            ->whereNotNull('cidco_node')
            ->where('cidco_node', '!=', '')
            ->groupBy('cidco_node')
            ->orderByDesc('count')
            ->limit(8)
            ->get()
            ->map(fn ($r) => [
                'name' => $r->cidco_node,
                'count' => (int) $r->count,
            ]);

        $byServiceType = Lead::select('service_type', DB::raw('COUNT(*) as count'))
            ->whereNotNull('service_type')
            ->where('service_type', '!=', '')
            ->groupBy('service_type')
            ->orderByDesc('count')
            ->limit(8)
            ->get()
            ->map(fn ($r) => [
                'name' => $r->service_type,
                'count' => (int) $r->count,
            ]);

        $caseTrendStart = now()->subDays(13)->startOfDay();
        $caseTrendRaw = Lead::select(
                DB::raw('DATE(COALESCE(date_opened, DATE(created_at))) as date'),
                DB::raw('COUNT(*) as count')
            )
            ->whereRaw('COALESCE(date_opened, DATE(created_at)) >= ?', [$caseTrendStart->toDateString()])
            ->groupBy('date')
            ->orderBy('date')
            ->get()
            ->keyBy('date');

        $caseTrend = [];
        for ($d = $caseTrendStart->copy(); $d <= now()->endOfDay(); $d->addDay()) {
            $key = $d->format('Y-m-d');
            $caseTrend[] = [
                'date' => $key,
                'label' => $d->format('d M'),
                'count' => (int) ($caseTrendRaw->get($key)->count ?? 0),
            ];
        }

        $actionsDue = Lead::whereNotNull('next_action_date')
            ->whereDate('next_action_date', '<=', today())
            ->where(function ($q) {
                $q->whereNull('case_status')->orWhere('case_status', '!=', 'Completed');
            })
            ->count();

        $actionsUpcoming = Lead::whereNotNull('next_action_date')
            ->whereBetween('next_action_date', [today()->toDateString(), now()->addDays(7)->toDateString()])
            ->where(function ($q) {
                $q->whereNull('case_status')->orWhere('case_status', '!=', 'Completed');
            })
            ->count();

        return response()->json([
            'success' => true,
            'data' => [
                'appointments' => [
                    'summary' => [
                        'total' => Appointment::count(),
                        'pending' => (int) ($apptByStatus['pending'] ?? 0),
                        'confirmed' => (int) ($apptByStatus['confirmed'] ?? 0),
                        'completed' => (int) ($apptByStatus['completed'] ?? 0),
                        'cancelled' => (int) ($apptByStatus['cancelled'] ?? 0),
                        'today' => Appointment::whereDate('appointment_date', today())->count(),
                        'upcoming_7' => Appointment::whereBetween('appointment_date', [
                            today()->toDateString(),
                            now()->addDays(7)->toDateString(),
                        ])->whereIn('status', ['pending', 'confirmed'])->count(),
                    ],
                    'by_status' => [
                        ['name' => 'Pending', 'value' => (int) ($apptByStatus['pending'] ?? 0), 'color' => '#f59e0b'],
                        ['name' => 'Confirmed', 'value' => (int) ($apptByStatus['confirmed'] ?? 0), 'color' => '#0ea5e9'],
                        ['name' => 'Completed', 'value' => (int) ($apptByStatus['completed'] ?? 0), 'color' => '#10b981'],
                        ['name' => 'Cancelled', 'value' => (int) ($apptByStatus['cancelled'] ?? 0), 'color' => '#ef4444'],
                    ],
                    'trend' => $apptTrend,
                    'by_service' => $apptByService,
                ],
                'advance_leads' => [
                    'summary' => [
                        'total' => Lead::count(),
                        'in_process' => (int) ($caseByStatus['In-Process at CIDCO'] ?? 0),
                        'completed' => (int) ($caseByStatus['Completed'] ?? 0),
                        'actions_due' => $actionsDue,
                        'actions_upcoming' => $actionsUpcoming,
                        'aging_hot' => $aging['61_90'] + $aging['90_plus'],
                    ],
                    'by_case_status' => collect($caseByStatus)->map(fn ($count, $name) => [
                        'name' => $name ?: 'Unknown',
                        'value' => (int) $count,
                    ])->values(),
                    'aging' => [
                        ['name' => '0–30 days', 'value' => $aging['0_30'], 'color' => '#10b981'],
                        ['name' => '31–60 days', 'value' => $aging['31_60'], 'color' => '#f59e0b'],
                        ['name' => '61–90 days', 'value' => $aging['61_90'], 'color' => '#f97316'],
                        ['name' => '90+ days', 'value' => $aging['90_plus'], 'color' => '#ef4444'],
                    ],
                    'by_node' => $byNode,
                    'by_service_type' => $byServiceType,
                    'opened_trend' => $caseTrend,
                ],
            ],
        ]);
    }

    /**
     * Advance Lead Profit & Loss series grouped by day/week/month/year.
     */
    public function advanceLeadPnl(Request $request)
    {
        $period = $request->get('period', 'month');
        if (!in_array($period, ['day', 'week', 'month', 'year'], true)) {
            $period = 'month';
        }

        [$start, $bucketExpr] = match ($period) {
            'day' => [
                now()->subDays(29)->startOfDay(),
                "DATE(COALESCE(date_opened, DATE(created_at)))",
            ],
            'week' => [
                now()->subWeeks(11)->startOfWeek(),
                "DATE_FORMAT(COALESCE(date_opened, DATE(created_at)), '%x-W%v')",
            ],
            'year' => [
                now()->subYears(4)->startOfYear(),
                "YEAR(COALESCE(date_opened, DATE(created_at)))",
            ],
            default => [
                now()->subMonths(11)->startOfMonth(),
                "DATE_FORMAT(COALESCE(date_opened, DATE(created_at)), '%Y-%m')",
            ],
        };

        $dateCol = 'COALESCE(date_opened, DATE(created_at))';

        $rows = Lead::query()
            ->selectRaw("{$bucketExpr} as period_key")
            ->selectRaw('COUNT(*) as cases')
            ->selectRaw('COALESCE(SUM(gross_fee), 0) as gross_fee')
            ->selectRaw('COALESCE(SUM(paper_office_exp), 0) as paper_office_exp')
            ->selectRaw('COALESCE(SUM(cidco_fees), 0) as cidco_fees')
            ->selectRaw('COALESCE(SUM(paper_office_exp), 0) + COALESCE(SUM(cidco_fees), 0) as total_exp')
            ->selectRaw('COALESCE(SUM(gross_fee), 0) - (COALESCE(SUM(paper_office_exp), 0) + COALESCE(SUM(cidco_fees), 0)) as net_profit')
            ->whereRaw("{$dateCol} >= ?", [$start->toDateString()])
            ->groupBy('period_key')
            ->orderBy('period_key')
            ->get();

        $byKey = $rows->keyBy(fn ($row) => (string) $row->period_key);

        $series = [];
        $cursor = $start->copy();
        $end = now()->endOfDay();

        while ($cursor <= $end) {
            $key = match ($period) {
                'day' => $cursor->format('Y-m-d'),
                'week' => sprintf('%s-W%02d', $cursor->format('o'), (int) $cursor->format('W')),
                'year' => $cursor->format('Y'),
                default => $cursor->format('Y-m'),
            };

            $row = $byKey->get($key);
            $gross = round((float) ($row->gross_fee ?? 0), 2);
            $exp = round((float) ($row->total_exp ?? 0), 2);
            $profit = round((float) ($row->net_profit ?? 0), 2);

            $series[] = [
                'period_key' => $key,
                'label' => $this->formatPnlLabel($period, $key, $cursor),
                'cases' => (int) ($row->cases ?? 0),
                'gross_fee' => $gross,
                'total_exp' => $exp,
                'net_profit' => $profit,
                'loss' => $profit < 0 ? abs($profit) : 0,
                'profit' => $profit > 0 ? $profit : 0,
            ];

            $cursor = match ($period) {
                'day' => $cursor->addDay(),
                'week' => $cursor->addWeek(),
                'year' => $cursor->addYear(),
                default => $cursor->addMonth(),
            };
        }

        $summary = [
            'cases' => (int) collect($series)->sum('cases'),
            'gross_fee' => round((float) collect($series)->sum('gross_fee'), 2),
            'total_exp' => round((float) collect($series)->sum('total_exp'), 2),
            'net_profit' => round((float) collect($series)->sum('net_profit'), 2),
        ];

        return response()->json([
            'success' => true,
            'data' => [
                'period' => $period,
                'from' => $start->toDateString(),
                'to' => now()->toDateString(),
                'summary' => $summary,
                'series' => $series,
            ],
        ]);
    }

    private function formatPnlLabel(string $period, string $key, Carbon $cursor): string
    {
        return match ($period) {
            'day' => $cursor->format('d M'),
            'week' => 'W' . $cursor->format('W') . ' ' . $cursor->format('Y'),
            'year' => $cursor->format('Y'),
            default => $cursor->format('M Y'),
        };
    }
}
