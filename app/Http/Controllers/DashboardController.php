<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Stats
        $totalInvoices = $user->invoices()->invoices()->count();
        $totalReceipts = $user->invoices()->receipts()->count();
        $totalRevenue = $user->invoices()->where('status', 'paid')->sum('total');
        $pendingAmount = $user->invoices()->whereIn('status', ['sent', 'draft'])->sum('total');
        $overdueCount = $user->invoices()->where('status', 'sent')
            ->where('due_date', '<', now())
            ->count();
        $paidThisMonth = $user->invoices()
            ->where('status', 'paid')
            ->whereMonth('updated_at', now()->month)
            ->whereYear('updated_at', now()->year)
            ->sum('total');

        // Recent invoices
        $recentInvoices = $user->invoices()
            ->with('client')
            ->latest()
            ->take(10)
            ->get();

        // Monthly revenue chart data (last 6 months)
        $monthlyRevenue = $user->invoices()
            ->where('status', 'paid')
            ->where('issue_date', '>=', now()->subMonths(6))
            ->select(
                DB::raw("DATE_FORMAT(issue_date, '%Y-%m') as month"),
                DB::raw('SUM(total) as revenue')
            )
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('revenue', 'month')
            ->toArray();

        return view('dashboard', compact(
            'totalInvoices', 'totalReceipts', 'totalRevenue',
            'pendingAmount', 'overdueCount', 'paidThisMonth',
            'recentInvoices', 'monthlyRevenue'
        ));
    }
}
