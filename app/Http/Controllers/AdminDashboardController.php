<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Invoice;

use Illuminate\Support\Facades\Cache;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('admin_dashboard_stats', 60, function () {
            return [
                'clients' => Client::count(),
                'active_contracts' => Contract::where('status', 'active')->count(),
                'pending_contracts' => Contract::where('status', 'pending')->count(),
                'pending_invoices' => Invoice::where('status', 'pending')->count(),
                'monthly_revenue' => Invoice::whereMonth('date', now()->month)
                    ->whereYear('date', now()->year)
                    ->sum('amount'),
                'total_invoiced' => Invoice::sum('amount'),
                'outstanding' => Invoice::where('status', 'pending')->sum('amount'),
            ];
        });

        $pendingContracts = Contract::with(['client.company'])
            ->where('status', 'pending')
            ->latest()
            ->take(8)
            ->get();

        $fifteenDaysFromNow = now()->addDays(15)->toDateString();

        $expiringContracts = Contract::with(['client.company'])
            ->where('status', 'active')
            ->where('end_date', '<=', $fifteenDaysFromNow)
            ->where('end_date', '>=', now()->toDateString())
            ->whereDoesntHave('renewals')
            ->orderBy('end_date', 'asc')
            ->get();

        // Optimized Chart Data with Caching
        $chartData = Cache::remember('admin_dashboard_charts', 300, function () {
            $revenueData = [];
            $months = [];
            for ($i = 11; $i >= 0; $i--) {
                $month = now()->subMonths($i);
                $months[] = $month->translatedFormat('M');
                $revenueData[] = Invoice::whereMonth('date', $month->month)
                    ->whereYear('date', $month->year)
                    ->sum('amount');
            }

            $durations = Contract::groupBy('duration')
                ->selectRaw('duration, count(*) as count')
                ->pluck('count', 'duration')
                ->toArray();

            return [
                'revenue' => [
                    'labels' => $months,
                    'data' => $revenueData
                ],
                'durations' => [
                    'labels' => array_keys($durations),
                    'data' => array_values($durations)
                ]
            ];
        });

        return view('pages.dashboard', compact('stats', 'pendingContracts', 'expiringContracts', 'chartData'));
    }
}
