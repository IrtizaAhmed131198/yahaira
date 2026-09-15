<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Payment;
use App\Models\Deal;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class FinancialController extends Controller
{
    public function index()
    {
        // 1. Revenue — This Month
        $startOfMonth = Carbon::now()->startOfMonth();
        $endOfMonth = Carbon::now()->endOfMonth();
        $revenueThisMonth = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startOfMonth, $endOfMonth])
            ->sum('amount');

        // 2. Revenue — YTD
        $startOfYear = Carbon::now()->startOfYear();
        $endOfYear = Carbon::now()->endOfYear();
        $revenueYTD = Payment::where('status', 'paid')
            ->whereBetween('paid_at', [$startOfYear, $endOfYear])
            ->sum('amount');

        // 3. Profit — This Month (Placeholder)
        // Hardcoded as 30% of revenue for now since expenses aren't tracked
        $profitThisMonth = $revenueThisMonth * 0.30;

        // 4. Renewal Rate (Placeholder)
        $renewalRate = 71; // Placeholder percentage

        // 5. Sales Performance by Setter
        $setters = User::role('setter')->get();
        $setterPerformance = [];

        foreach ($setters as $setter) {
            $totalDeals = Deal::where('assigned_setter_id', $setter->id)->count();
            $wonDeals = Deal::where('assigned_setter_id', $setter->id)->where('status', 'won')->count();
            
            $wonPercentage = $totalDeals > 0 ? round(($wonDeals / $totalDeals) * 100) : 0;

            $revenue = Payment::where('status', 'paid')
                ->whereHas('client.deal', function ($query) use ($setter) {
                    $query->where('assigned_setter_id', $setter->id);
                })
                ->sum('amount');

            // Only show setters who have actually been assigned deals
            if ($totalDeals > 0) {
                $setterPerformance[] = [
                    'name' => $setter->name,
                    'won_percentage' => $wonPercentage,
                    'revenue' => $revenue
                ];
            }
        }
        
        // Sort setters by highest revenue
        usort($setterPerformance, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        // 6. Revenue by Package
        $packageRevenues = Payment::where('status', 'paid')
            ->with('package')
            ->select('package_id', DB::raw('SUM(amount) as total_revenue'))
            ->groupBy('package_id')
            ->get();
            
        // Format to array for easier blade loop
        $revenueByPackage = [];
        foreach ($packageRevenues as $pr) {
            if ($pr->package) {
                $revenueByPackage[] = [
                    'package_name' => $pr->package->name,
                    'revenue' => $pr->total_revenue
                ];
            }
        }
        
        // Sort packages by highest revenue
        usort($revenueByPackage, function($a, $b) {
            return $b['revenue'] <=> $a['revenue'];
        });

        return view('financial.index', compact(
            'revenueThisMonth',
            'revenueYTD',
            'profitThisMonth',
            'renewalRate',
            'setterPerformance',
            'revenueByPackage'
        ));
    }
}
