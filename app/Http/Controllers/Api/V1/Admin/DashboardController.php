<?php

namespace App\Http\Controllers\Api\V1\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
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

        return response()->json([
            'success' => true,
            'data' => [
                'total_leads' => $totalLeads,
                'today_leads' => $todayLeads,
                'leads_by_status' => $leadsByStatus,
                'leads_last_7_days' => $leadsLast7Days,
            ],
        ]);
    }
}
